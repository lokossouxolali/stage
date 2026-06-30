<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidature;
use App\Models\Offre;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\NouvelleCandidature;
use App\Mail\CandidatureAcceptee;
use App\Mail\CandidatureRefusee;

class CandidatureController extends Controller
{
    public function index()
    {
        $candidatures = Candidature::with('etudiant', 'offre')->paginate(10);
        return view('candidatures.index', compact('candidatures'));
    }

    public function create(Request $request, Offre $offre = null)
    {
        // Si l'offre n'est pas fournie via la route, chercher via le paramètre de requête
        if (!$offre && $request->has('offre_id')) {
            $offre = Offre::findOrFail($request->get('offre_id'));
        }
        
        // Si toujours pas d'offre, rediriger vers la liste des offres
        if (!$offre) {
            return redirect()->route('offres.index')
                ->with('error', 'Veuillez sélectionner une offre pour postuler.');
        }

        $existingCandidature = Candidature::where('etudiant_id', auth()->id())
            ->where('offre_id', $offre->id)
            ->first();

        if ($existingCandidature) {
            return redirect()
                ->route('candidatures.show', $existingCandidature)
                ->with('error', 'Vous avez déjà postulé à cette offre.');
        }
        
        $offre->load('entreprise');
        return view('candidatures.create', compact('offre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'lettre_motivation' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cv_path' => 'required|file|mimes:pdf|max:5120', // 5MB en kilobytes
        ]);

        $existingCandidature = Candidature::where('etudiant_id', auth()->id())
            ->where('offre_id', $request->offre_id)
            ->first();

        if ($existingCandidature) {
            return redirect()
                ->route('candidatures.show', $existingCandidature)
                ->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        $data = $request->all();
        $data['etudiant_id'] = auth()->id();
        $data['statut'] = 'en_attente';
        $data['date_candidature'] = now();

        if ($request->hasFile('cv_path')) {
            $data['cv_path'] = $request->file('cv_path')->store('cvs', 'public');
        }

        if ($request->hasFile('lettre_motivation')) {
            $data['lettre_motivation_path'] = $request->file('lettre_motivation')->store('lettres_motivation', 'public');
            unset($data['lettre_motivation']);
        }

        if ($request->hasFile('lettre_recommandation')) {
            $data['lettre_recommandation_path'] = $request->file('lettre_recommandation')->store('lettres_recommandation', 'public');
        }

        $candidature = Candidature::create($data);
        $candidature->load('etudiant', 'offre.entreprise');

        // Envoyer une notification et un email à l'entreprise
        if ($candidature->offre && $candidature->offre->entreprise) {
            // Récupérer tous les utilisateurs de l'entreprise
            $usersEntreprise = User::where('entreprise_id', $candidature->offre->entreprise_id)
                ->where('est_actif', true)
                ->get();

            foreach ($usersEntreprise as $userEntreprise) {
                // Créer une notification
                Notification::create([
                    'user_id' => $userEntreprise->id,
                    'type' => 'nouvelle_candidature',
                    'titre' => 'Nouvelle candidature reçue',
                    'message' => $candidature->etudiant->name . ' a postulé pour l\'offre "' . $candidature->offre->titre . '"',
                    'lien' => route('candidatures.show', $candidature),
                ]);

                // Envoyer un email
                try {
                    Mail::to($userEntreprise->email)->send(new NouvelleCandidature($candidature));
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'envoi de l\'email de nouvelle candidature : ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('candidatures.mes')
            ->with('success', 'Candidature soumise avec succès');
    }

    public function show(Candidature $candidature)
    {
        $candidature->load('etudiant', 'offre.entreprise');
        return view('candidatures.show', compact('candidature'));
    }

    public function mesCandidatures()
    {
        $candidatures = Candidature::where('etudiant_id', auth()->id())
            ->with('offre')
            ->paginate(10);
        return view('candidatures.mes', compact('candidatures'));
    }

    public function candidaturesRecues()
    {
        $user = auth()->user();
        $candidatures = Candidature::whereHas('offre', function($query) use ($user) {
            $query->where('entreprise_id', $user->entreprise_id);
        })
        ->with('etudiant', 'offre')
        ->paginate(10);
        
        return view('candidatures.recues', compact('candidatures'));
    }

    public function accepter(Candidature $candidature)
    {
        $candidature->load('offre.entreprise', 'etudiant');
        $user = auth()->user();
        
        // Vérifier les permissions : l'entreprise de l'offre ou un admin
        if (!$user->isAdmin() && (!$user->isEntreprise() || !$candidature->offre || $candidature->offre->entreprise_id !== $user->entreprise_id)) {
            abort(403, 'Vous n\'êtes pas autorisé à accepter cette candidature');
        }
        
        $candidature->update([
            'statut' => 'acceptee',
            'date_reponse' => now()
        ]);

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $candidature->etudiant_id,
            'type' => 'candidature_acceptee',
            'titre' => 'Candidature acceptée',
            'message' => 'Votre candidature a été acceptée',
            'lien' => route('candidatures.show', $candidature),
        ]);

        // Envoyer un email à l'étudiant
        try {
            Mail::to($candidature->etudiant->email)->send(new CandidatureAcceptee($candidature));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de candidature acceptée : ' . $e->getMessage());
        }
        
        return back()->with('success', 'Candidature acceptée avec succès');
    }

    public function refuser(Candidature $candidature)
    {
        $candidature->load('offre.entreprise', 'etudiant');
        $user = auth()->user();
        
        // Vérifier les permissions : l'entreprise de l'offre ou un admin
        if (!$user->isAdmin() && (!$user->isEntreprise() || !$candidature->offre || $candidature->offre->entreprise_id !== $user->entreprise_id)) {
            abort(403, 'Vous n\'êtes pas autorisé à refuser cette candidature');
        }
        
        $candidature->update([
            'statut' => 'refusee',
            'date_reponse' => now()
        ]);

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $candidature->etudiant_id,
            'type' => 'candidature_refusee',
            'titre' => 'Réponse à votre candidature',
            'message' => 'Votre candidature pour l\'offre "' . $candidature->offre->titre . '" n\'a pas été retenue.',
            'lien' => route('candidatures.show', $candidature),
        ]);

        // Envoyer un email à l'étudiant
        try {
            Mail::to($candidature->etudiant->email)->send(new CandidatureRefusee($candidature));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de candidature refusée : ' . $e->getMessage());
        }
        
        return back()->with('success', 'Candidature refusée');
    }

    public function destroy(Candidature $candidature)
    {
        // Seul l'étudiant propriétaire peut supprimer sa candidature
        if (!auth()->user()->isSuperAdmin() && auth()->id() !== $candidature->etudiant_id) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette candidature');
        }

        // Ne peut supprimer que si la candidature est en attente
        if (!auth()->user()->isSuperAdmin() && $candidature->statut !== 'en_attente') {
            return back()->with('error', 'Vous ne pouvez supprimer que les candidatures en attente');
        }

        foreach (['cv_path', 'lettre_motivation_path', 'lettre_recommandation_path'] as $fileField) {
            if ($candidature->{$fileField}) {
                Storage::disk('public')->delete($candidature->{$fileField});
            }
        }

        $candidature->delete();

        return redirect()->route('candidatures.mes')
            ->with('success', 'Candidature supprimée avec succès');
    }

    public function downloadCv(Candidature $candidature)
    {
        $candidature->load('etudiant', 'offre.entreprise');
        $this->authorizeFileDownload($candidature);

        if (!$candidature->cv_path) {
            abort(404, 'CV non trouvé');
        }

        if (!Storage::disk('public')->exists($candidature->cv_path)) {
            abort(404, 'Fichier non trouvé à l\'emplacement : ' . $candidature->cv_path);
        }

        $fileName = 'CV_' . str_replace(' ', '_', $candidature->etudiant->name) . '.pdf';
        return Storage::disk('public')->download($candidature->cv_path, $fileName);
    }

    public function downloadLettreRecommandation(Candidature $candidature)
    {
        $candidature->load('etudiant', 'offre.entreprise');
        $this->authorizeFileDownload($candidature);

        if (!$candidature->lettre_recommandation_path) {
            abort(404, 'Lettre de recommandation non trouvée');
        }

        if (!Storage::disk('public')->exists($candidature->lettre_recommandation_path)) {
            abort(404, 'Fichier non trouvé à l\'emplacement : ' . $candidature->lettre_recommandation_path);
        }

        $fileName = 'Lettre_recommandation_' . str_replace(' ', '_', $candidature->etudiant->name) . '.pdf';
        return Storage::disk('public')->download($candidature->lettre_recommandation_path, $fileName);
    }

    public function downloadLettreMotivation(Candidature $candidature)
    {
        $candidature->load('etudiant', 'offre.entreprise');
        $this->authorizeFileDownload($candidature);

        if (!$candidature->lettre_motivation_path) {
            abort(404, 'Lettre de motivation non trouvée');
        }

        if (!Storage::disk('public')->exists($candidature->lettre_motivation_path)) {
            abort(404, 'Fichier non trouvé à l\'emplacement : ' . $candidature->lettre_motivation_path);
        }

        $extension = pathinfo($candidature->lettre_motivation_path, PATHINFO_EXTENSION);
        $fileName = 'Lettre_motivation_' . str_replace(' ', '_', $candidature->etudiant->name) . '.' . $extension;

        return Storage::disk('public')->download($candidature->lettre_motivation_path, $fileName);
    }

    private function authorizeFileDownload(Candidature $candidature): void
    {
        $user = auth()->user();

        $canDownload = $user->isAdmin()
            || ($user->isEtudiant() && $candidature->etudiant_id === $user->id)
            || ($user->isEntreprise() && $candidature->offre && $candidature->offre->entreprise_id === $user->entreprise_id);

        if (!$canDownload) {
            abort(403, 'Vous n\'êtes pas autorisé à télécharger ce fichier');
        }
    }

    public function export()
    {
        // Logique pour l'export des candidatures
        return response()->download('candidatures.csv');
    }
}
