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
        $user = auth()->user();
        
        // Si l'utilisateur est une entreprise, ne montrer que son entreprise
        if ($user->isEntreprise() && $user->entreprise_id) {
            $entreprises = Entreprise::where('id', $user->entreprise_id)->get();
        } else {
            // Admin peut voir toutes les entreprises
            $entreprises = Entreprise::all();
        }
        
        $type_stage = ['Obligatoire', 'Perfectionnement', 'Projet_fin_etudes'];
        return view('offres.create', compact('entreprises', 'type_stage'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Si l'utilisateur est une entreprise, forcer l'entreprise_id à son entreprise
        if ($user->isEntreprise() && $user->entreprise_id) {
            $request->merge(['entreprise_id' => $user->entreprise_id]);
        }
        
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'competences_requises' => 'required|string',
            'duree' => 'required|integer|min:1',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'entreprise_id' => 'required|exists:entreprises,id',
            'statut' => 'required|in:active,inactive',
        ]);

        Offre::create($request->all());

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
        
        // Si l'utilisateur est une entreprise, ne montrer que son entreprise
        if ($user->isEntreprise() && $user->entreprise_id) {
            $entreprises = Entreprise::where('id', $user->entreprise_id)->get();
        } else {
            // Admin peut voir toutes les entreprises
            $entreprises = Entreprise::all();
        }
        
        return view('offres.edit', compact('offre', 'entreprises'));
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
            'competences_requises' => 'required|string',
            'duree' => 'required|integer|min:1',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'entreprise_id' => 'required|exists:entreprises,id',
            'statut' => 'required|in:active,inactive',
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
        $offres = Offre::where('entreprise_id', $user->entreprise_id)->paginate(10);
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
