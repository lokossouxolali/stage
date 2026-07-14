<?php

namespace App\Http\Controllers;

use App\Jobs\NotifierEtudiantsNouvelleOffre;
use App\Models\Candidature;
use App\Models\Entreprise;
use App\Models\Filiere;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OffreController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Les entreprises voient toutes les offres (lecture seule)
        // Les admins voient toutes les offres
        $offres = Offre::with(['entreprise', 'filiere'])->paginate(10);

        return view('offres.index', compact('offres'));
    }

    public function create()
    {
        $type_stage = $this->typesStage();
        $entreprises = auth()->user()->isAdmin() ? Entreprise::orderBy('nom')->get() : collect();
        $filieres = Filiere::orderBy('nom')->get(['id', 'nom', 'code']);

        return view('offres.create', compact('type_stage', 'entreprises', 'filieres'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Si l'utilisateur est une entreprise, forcer l'entreprise_id à son entreprise
        if ($user->isEntreprise()) {
            if (! $user->entreprise_id) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Votre compte entreprise n\'est pas associé à une entreprise. Veuillez contacter un responsable pedagogique.']);
            }
            $request->merge(['entreprise_id' => $user->entreprise_id]);
        } elseif (! $user->isAdmin()) {
            abort(403, 'Seules les entreprises et les responsables pedagogiques peuvent creer des offres');
        }

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'missions' => 'required|string',
            'competences_requises' => 'nullable|string',
            'duree' => 'required|integer|min:1|max:12',
            'type_stage' => ['required', Rule::in($this->typesStage())],
            'niveau_etude' => 'required|in:L1,L2,L3,M1,M2',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'nombre_places' => 'nullable|integer|min:1|max:10',
            'lieu' => 'nullable|string|max:255',
            'date_limite_candidature' => 'nullable|date',
            'statut' => 'nullable|in:active,fermee,suspendue',
            'entreprise_id' => $user->isAdmin() ? 'required|exists:entreprises,id' : 'nullable|exists:entreprises,id',
            'filiere_id' => 'required|integer|exists:filieres,id',
        ]);

        // S'assurer que l'entreprise_id est bien défini avant la création
        if (! isset($data['entreprise_id']) || ! $data['entreprise_id']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erreur : l\'entreprise n\'a pas pu être identifiée.']);
        }

        $data['statut'] = 'active';

        $offre = Offre::create($data);

        if ($offre->statut === 'active') {
            $this->notifierPublication($offre);
        }

        return redirect()->route('offres.mes')
            ->with('success', 'Offre créée avec succès');
    }

    public function show(Offre $offre)
    {
        $offre->load('entreprise', 'filiere', 'candidatures');
        $candidatureExistante = null;

        if (auth()->user()->isEtudiant()) {
            $candidatureExistante = Candidature::where('etudiant_id', auth()->id())
                ->where('offre_id', $offre->id)
                ->first();
        }

        return view('offres.show', compact('offre', 'candidatureExistante'));
    }

    public function edit(Offre $offre)
    {
        $user = auth()->user();

        // Vérifier que l'entreprise peut modifier cette offre
        if ($user->isEntreprise() && $offre->entreprise_id !== $user->entreprise_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre');
        }

        $filieres = Filiere::orderBy('nom')->get(['id', 'nom', 'code']);

        return view('offres.edit', compact('offre', 'filieres'));
    }

    public function update(Request $request, Offre $offre)
    {
        $user = auth()->user();

        // Vérifier que l'entreprise peut modifier cette offre
        if ($user->isEntreprise() && $offre->entreprise_id !== $user->entreprise_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre');
        }

        // Si l'utilisateur est une entreprise, forcer l'entreprise_id à son entreprise
        if ($user->isEntreprise() && $user->entreprise_id) {
            $request->merge(['entreprise_id' => $user->entreprise_id]);
        }

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'missions' => 'required|string',
            'competences_requises' => 'nullable|string',
            'duree' => 'required|integer|min:1|max:12',
            'type_stage' => ['required', Rule::in($this->typesStage())],
            'niveau_etude' => 'required|in:L1,L2,L3,M1,M2',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'nombre_places' => 'nullable|integer|min:1|max:10',
            'lieu' => 'nullable|string|max:255',
            'date_limite_candidature' => 'nullable|date',
            'statut' => 'nullable|in:active,fermee,suspendue',
            'filiere_id' => 'required|integer|exists:filieres,id',
        ]);

        $data['statut'] = ($request->input('statut') === 'active') ? 'active' : 'suspendue';

        $wasPublished = $offre->statut === 'active';

        $offre->update($data);

        if (! $wasPublished && $offre->statut === 'active') {
            $this->notifierPublication($offre);
        }

        return redirect()->route('offres.mes')
            ->with('success', 'Offre mise à jour avec succès');
    }

    public function destroy(Offre $offre)
    {
        $user = auth()->user();

        // Vérifier que l'entreprise peut supprimer cette offre
        if ($user->isEntreprise() && $offre->entreprise_id !== $user->entreprise_id) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette offre');
        }

        $offre->delete();

        return redirect()->route('offres.mes')
            ->with('success', 'Offre supprimée avec succès');
    }

    public function mesOffres()
    {
        $user = auth()->user();
        $offres = Offre::where('entreprise_id', $user->entreprise_id)
            ->with('candidatures')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('offres.mes', compact('offres'));
    }

    public function offresDisponibles()
    {
        $offres = Offre::with('entreprise')->where('statut', 'active')->paginate(10);

        return view('offres.disponibles', compact('offres'));
    }

    public function export()
    {
        // Logique pour l'export des offres
        return response()->download('offres.csv');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $offres = Offre::where('titre', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->paginate(10);

        return view('offres.index', compact('offres', 'query'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q');
        $offres = Offre::where('titre', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'titre']);

        return response()->json($offres);
    }

    private function typesStage(): array
    {
        return ['Perfectionnement', 'Professionnel', 'Académique', 'Mémoire'];
    }

    private function notifierPublication(Offre $offre): void
    {
        NotifierEtudiantsNouvelleOffre::dispatch($offre);
    }
}
