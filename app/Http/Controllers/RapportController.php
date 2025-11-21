<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rapport;
use App\Models\Stage;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $rapports = Rapport::with(['etudiant', 'stage.candidature.offre.entreprise']);
        
        // Admin voit tous les rapports qui lui sont destinés
        if ($user->isAdmin()) {
            $rapports->where(function($query) {
                $query->where('destinataire', 'admin')
                      ->orWhere('destinataire', 'les_deux');
            });
        } elseif ($user->isEtudiant()) {
            // Étudiant voit ses propres rapports
            $rapports->where('etudiant_id', $user->id);
        }
        
        $rapports = $rapports->paginate(10);
        return view('rapports.index', compact('rapports'));
    }

    public function create()
    {
        return view('rapports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_rapport' => 'required|in:memoire,proposition_theme',
            'titre' => 'required|string|max:255',
            'fichier' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'destinataire' => 'required|in:admin,directeur_memoire,les_deux',
        ]);

        $data = $request->all();
        $data['statut'] = 'soumis';
        $data['date_soumission'] = now();
        $data['etudiant_id'] = auth()->id(); // Enregistrer l'étudiant connecté

        if ($request->hasFile('fichier')) {
            $data['fichier_path'] = $request->file('fichier')->store('rapports', 'public');
        }

        Rapport::create($data);

        return redirect()->route('rapports.index')
            ->with('success', 'Rapport soumis avec succès');
    }

    public function show(Rapport $rapport)
    {
        $rapport->load(['etudiant', 'stage.candidature.offre.entreprise']);
        return view('rapports.show', compact('rapport'));
    }

    public function valider(Rapport $rapport)
    {
        $user = auth()->user();
        
        // Vérifier les permissions
        if (!$user->isAdmin() && !$user->isEnseignant()) {
            abort(403, 'Vous n\'êtes pas autorisé à valider ce rapport');
        }

        $rapport->update([
            'statut' => 'valide',
            'date_validation' => now()
        ]);

        // Créer une notification pour l'étudiant
        if ($rapport->etudiant_id) {
            Notification::create([
                'user_id' => $rapport->etudiant_id,
                'type' => 'rapport_valide',
                'titre' => 'Rapport validé',
                'message' => 'Votre rapport "' . $rapport->titre . '" a été validé.',
                'lien' => route('rapports.show', $rapport->id),
            ]);
        }
        
        return back()->with('success', 'Rapport validé avec succès');
    }

    public function rejeter(Request $request, Rapport $rapport)
    {
        $user = auth()->user();
        
        // Vérifier les permissions
        if (!$user->isAdmin() && !$user->isEnseignant()) {
            abort(403, 'Vous n\'êtes pas autorisé à rejeter ce rapport');
        }

        $request->validate([
            'commentaire' => 'required|string',
        ]);

        $rapport->update([
            'statut' => 'rejete',
            'commentaires_encadreur_academique' => $user->isEnseignant() ? $request->commentaire : $rapport->commentaires_encadreur_academique,
        ]);

        // Créer une notification pour l'étudiant
        if ($rapport->etudiant_id) {
            Notification::create([
                'user_id' => $rapport->etudiant_id,
                'type' => 'rapport_rejete',
                'titre' => 'Rapport rejeté',
                'message' => 'Votre rapport "' . $rapport->titre . '" a été rejeté. Commentaires : ' . $request->commentaire,
                'lien' => route('rapports.show', $rapport->id),
            ]);
        }
        
        return back()->with('success', 'Rapport rejeté avec succès');
    }

    public function mesRapports()
    {
        $user = auth()->user();
        $rapports = Rapport::where('etudiant_id', $user->id)
            ->with(['etudiant', 'stage.candidature.offre.entreprise'])
            ->paginate(10);
        
        return view('rapports.mes', compact('rapports'));
    }

    public function rapportsEncadres()
    {
        $user = auth()->user();
        
        if ($user->isEnseignant()) {
            // Enseignant voit les rapports des étudiants qu'il encadre (directeur de mémoire)
            $rapports = Rapport::where(function($query) use ($user) {
                $query->whereHas('etudiant', function($q) use ($user) {
                    $q->where('directeur_memoire_id', $user->id);
                })->orWhereHas('stage', function($q) use ($user) {
                    $q->where('encadreur_academique_id', $user->id);
                });
            })->with(['etudiant', 'stage.candidature.offre.entreprise'])->paginate(10);
        } elseif ($user->isEntreprise()) {
            // Entreprise voit les rapports des stages de ses offres
            $rapports = Rapport::whereHas('stage', function($query) use ($user) {
                $query->whereHas('candidature', function($q2) use ($user) {
                    $q2->whereHas('offre', function($q3) use ($user) {
                        $q3->where('entreprise_id', $user->entreprise_id);
                    });
                });
            })->with(['etudiant', 'stage.candidature.offre.entreprise'])->paginate(10);
        } else {
            $rapports = Rapport::where('id', 0)->paginate(10); // Empty result
        }
        
        return view('rapports.encadres', compact('rapports'));
    }

    public function ajouterCommentaire(Request $request, Rapport $rapport)
    {
        $user = auth()->user();
        
        if ($user->isEnseignant()) {
            $rapport->update(['commentaires_encadreur_academique' => $request->commentaire]);
        } elseif ($user->isEntreprise()) {
            $rapport->update(['commentaires_encadreur_entreprise' => $request->commentaire]);
        }
        
        return back()->with('success', 'Commentaire ajouté avec succès');
    }

    public function download(Rapport $rapport)
    {
        if (!$rapport->fichier_path) {
            abort(404, 'Fichier non trouvé');
        }

        // Vérifier d'abord dans le disque public (nouveaux fichiers)
        if (Storage::disk('public')->exists($rapport->fichier_path)) {
            return Storage::disk('public')->download($rapport->fichier_path);
        }
        
        // Sinon, vérifier dans storage/app/rapports (anciens fichiers)
        $oldPath = storage_path('app/' . $rapport->fichier_path);
        if (file_exists($oldPath)) {
            return response()->download($oldPath);
        }

        abort(404, 'Fichier non trouvé');
    }
}
