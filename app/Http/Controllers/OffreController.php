<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\Entreprise;
use App\Models\TypeStage;

class OffreController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Les entreprises voient toutes les offres (lecture seule)
        // Les admins voient toutes les offres
        $offres = Offre::with('entreprise')->paginate(10);
        
        return view('offres.index', compact('offres'));
    }

    public function create()
    {
        $type_stage = ['Obligatoire', 'Perfectionnement', 'Projet_fin_etudes'];
        return view('offres.create', compact('type_stage'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Si l'utilisateur est une entreprise, forcer l'entreprise_id à son entreprise
        if ($user->isEntreprise()) {
            if (!$user->entreprise_id) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'Votre compte entreprise n\'est pas associé à une entreprise. Veuillez contacter l\'administrateur.']);
            }
            $request->merge(['entreprise_id' => $user->entreprise_id]);
        } else {
            // Si l'utilisateur n'est pas une entreprise, on ne peut pas créer d'offre
            abort(403, 'Seules les entreprises peuvent créer des offres');
        }
        
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'missions' => 'required|string',
            'competences_requises' => 'nullable|string',
            'duree' => 'required|integer|min:1|max:12',
            'type_stage' => 'required|string',
            'niveau_etude' => 'required|in:L1,L2,L3,M1,M2',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'nombre_places' => 'nullable|integer|min:1|max:10',
            'lieu' => 'nullable|string|max:255',
            'date_limite_candidature' => 'nullable|date',
            'statut' => 'nullable|in:active,fermee,suspendue',
        ]);

        // S'assurer que l'entreprise_id est bien défini avant la création
        $data = $request->all();
        if (!isset($data['entreprise_id']) || !$data['entreprise_id']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erreur : l\'entreprise n\'a pas pu être identifiée.']);
        }

        // Gérer le statut : si la checkbox n'est pas cochée, le champ n'est pas envoyé
        // Si non coché, on utilise 'suspendue' pour indiquer que ce n'est pas encore publié
        // Sinon on utilise 'active' (la valeur par défaut de la base de données est 'active')
        if (!isset($data['statut']) || $data['statut'] !== 'active') {
            $data['statut'] = 'suspendue'; // Brouillon/non publié
        } else {
            $data['statut'] = 'active';
        }

        Offre::create($data);

        return redirect()->route('offres.mes')
            ->with('success', 'Offre créée avec succès');
    }

    public function show(Offre $offre)
    {
        $offre->load('entreprise', 'candidatures');
        return view('offres.show', compact('offre'));
    }

    public function edit(Offre $offre)
    {
        $user = auth()->user();
        
        // Vérifier que l'entreprise peut modifier cette offre
        if ($user->isEntreprise() && $offre->entreprise_id !== $user->entreprise_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette offre');
        }
        
        return view('offres.edit', compact('offre'));
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
        
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'missions' => 'required|string',
            'competences_requises' => 'nullable|string',
            'duree' => 'required|integer|min:1|max:12',
            'type_stage' => 'required|string',
            'niveau_etude' => 'required|in:L1,L2,L3,M1,M2',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'nombre_places' => 'nullable|integer|min:1|max:10',
            'lieu' => 'nullable|string|max:255',
            'date_limite_candidature' => 'nullable|date',
            'statut' => 'nullable|in:active,inactive',
        ]);

        $offre->update($request->all());

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
        $offres = Offre::where('statut', 'active')->paginate(10);
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
}
