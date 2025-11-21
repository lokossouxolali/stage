<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OffreController extends Controller
{
    public function index(Request $request)
    {
        $query = Offre::with(['entreprise', 'candidatures']);

        // Filtres
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('type_stage')) {
            $query->where('type_stage', $request->type_stage);
        }

        if ($request->has('niveau_etude')) {
            $query->where('niveau_etude', $request->niveau_etude);
        }

        if ($request->has('entreprise_id')) {
            $query->where('entreprise_id', $request->entreprise_id);
        }

        // Recherche par titre ou description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $offres = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $offres
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'missions' => 'nullable|string',
            'competences_requises' => 'nullable|string',
            'duree' => 'nullable|string|max:50',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'lieu' => 'nullable|string|max:255',
            'type_stage' => 'required|in:obligatoire,optionnel,projet_fin_etudes',
            'niveau_etude' => 'nullable|in:L1,L2,L3,M1,M2,Doctorat',
            'remuneration' => 'nullable|string|max:100',
            'date_limite_candidature' => 'nullable|date',
            'nombre_places' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Vérifier que l'utilisateur est une entreprise
        if (!$user->isEntreprise()) {
            return response()->json([
                'success' => false,
                'message' => 'Seules les entreprises peuvent créer des offres'
            ], 403);
        }

        $offre = Offre::create([
            'entreprise_id' => $user->entreprise_id,
            'titre' => $request->titre,
            'description' => $request->description,
            'missions' => $request->missions,
            'competences_requises' => $request->competences_requises,
            'duree' => $request->duree,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'lieu' => $request->lieu,
            'type_stage' => $request->type_stage,
            'niveau_etude' => $request->niveau_etude,
            'remuneration' => $request->remuneration,
            'date_limite_candidature' => $request->date_limite_candidature,
            'nombre_places' => $request->nombre_places,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Offre créée avec succès',
            'data' => $offre->load('entreprise')
        ], 201);
    }

    public function show(string $id)
    {
        $offre = Offre::with(['entreprise', 'candidatures.etudiant'])->find($id);

        if (!$offre) {
            return response()->json([
                'success' => false,
                'message' => 'Offre non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $offre
        ]);
    }

    public function update(Request $request, string $id)
    {
        $offre = Offre::find($id);

        if (!$offre) {
            return response()->json([
                'success' => false,
                'message' => 'Offre non trouvée'
            ], 404);
        }

        $user = $request->user();

        // Vérifier les permissions
        if (!$user->isAdmin() && $offre->entreprise_id !== $user->entreprise_id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'statut' => 'sometimes|in:active,fermee,suspendue',
            'date_limite_candidature' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $offre->update($request->only([
            'titre', 'description', 'missions', 'competences_requises',
            'duree', 'date_debut', 'date_fin', 'lieu', 'type_stage',
            'niveau_etude', 'remuneration', 'statut', 'date_limite_candidature',
            'nombre_places'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Offre mise à jour avec succès',
            'data' => $offre->load('entreprise')
        ]);
    }

    public function destroy(string $id)
    {
        $offre = Offre::find($id);

        if (!$offre) {
            return response()->json([
                'success' => false,
                'message' => 'Offre non trouvée'
            ], 404);
        }

        $user = request()->user();

        // Vérifier les permissions
        if (!$user->isAdmin() && $offre->entreprise_id !== $user->entreprise_id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $offre->delete();

        return response()->json([
            'success' => true,
            'message' => 'Offre supprimée avec succès'
        ]);
    }

    public function candidatures(string $id)
    {
        $offre = Offre::with(['candidatures.etudiant'])->find($id);

        if (!$offre) {
            return response()->json([
                'success' => false,
                'message' => 'Offre non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $offre->candidatures
        ]);
    }

    public function publiques()
    {
        $offres = Offre::with(['entreprise'])
            ->active()
            ->disponible()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $offres
        ]);
    }
}
