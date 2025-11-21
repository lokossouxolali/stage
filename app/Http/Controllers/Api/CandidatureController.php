<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CandidatureController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Candidature::with(['etudiant', 'offre.entreprise']);

        // Filtres selon le rôle
        if ($user->isEtudiant()) {
            $query->where('etudiant_id', $user->id);
        } elseif ($user->isEntreprise()) {
            $query->whereHas('offre', function($q) use ($user) {
                $q->where('entreprise_id', $user->entreprise_id);
            });
        }

        // Filtres supplémentaires
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('offre_id')) {
            $query->where('offre_id', $request->offre_id);
        }

        $candidatures = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $candidatures
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offre_id' => 'required|exists:offres,id',
            'lettre_motivation' => 'nullable|string',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'lettre_recommandation' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'commentaires_etudiant' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Vérifier que l'utilisateur est un étudiant
        if (!$user->isEtudiant()) {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les étudiants peuvent postuler'
            ], 403);
        }

        $offre = Offre::find($request->offre_id);

        // Vérifier que l'offre est active
        if ($offre->statut !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Cette offre n\'est plus disponible'
            ], 400);
        }

        // Vérifier la date limite
        if ($offre->date_limite_candidature && $offre->date_limite_candidature < now()) {
            return response()->json([
                'success' => false,
                'message' => 'La date limite de candidature est dépassée'
            ], 400);
        }

        // Vérifier qu'il n'y a pas déjà une candidature
        $existingCandidature = Candidature::where('etudiant_id', $user->id)
            ->where('offre_id', $request->offre_id)
            ->first();

        if ($existingCandidature) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà postulé à cette offre'
            ], 400);
        }

        // Gestion des fichiers
        $cvPath = null;
        $lettreRecommandationPath = null;

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('candidatures/cv', 'public');
        }

        if ($request->hasFile('lettre_recommandation')) {
            $lettreRecommandationPath = $request->file('lettre_recommandation')->store('candidatures/lettres', 'public');
        }

        $candidature = Candidature::create([
            'etudiant_id' => $user->id,
            'offre_id' => $request->offre_id,
            'lettre_motivation' => $request->lettre_motivation,
            'cv_path' => $cvPath,
            'lettre_recommandation_path' => $lettreRecommandationPath,
            'commentaires_etudiant' => $request->commentaires_etudiant,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidature envoyée avec succès',
            'data' => $candidature->load(['etudiant', 'offre.entreprise'])
        ], 201);
    }

    public function show(string $id)
    {
        $candidature = Candidature::with(['etudiant', 'offre.entreprise', 'stage'])->find($id);

        if (!$candidature) {
            return response()->json([
                'success' => false,
                'message' => 'Candidature non trouvée'
            ], 404);
        }

        $user = request()->user();

        // Vérifier les permissions
        if (!$user->isAdmin() && 
            $candidature->etudiant_id !== $user->id && 
            $candidature->offre->entreprise_id !== $user->entreprise_id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $candidature
        ]);
    }

    public function update(Request $request, string $id)
    {
        $candidature = Candidature::find($id);

        if (!$candidature) {
            return response()->json([
                'success' => false,
                'message' => 'Candidature non trouvée'
            ], 404);
        }

        $user = $request->user();

        // Vérifier les permissions
        if (!$user->isAdmin() && $candidature->etudiant_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'lettre_motivation' => 'sometimes|string',
            'commentaires_etudiant' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $candidature->update($request->only([
            'lettre_motivation', 'commentaires_etudiant'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Candidature mise à jour avec succès',
            'data' => $candidature->load(['etudiant', 'offre.entreprise'])
        ]);
    }

    public function destroy(string $id)
    {
        $candidature = Candidature::find($id);

        if (!$candidature) {
            return response()->json([
                'success' => false,
                'message' => 'Candidature non trouvée'
            ], 404);
        }

        $user = request()->user();

        // Vérifier les permissions
        if (!$user->isAdmin() && $candidature->etudiant_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        // Supprimer les fichiers associés
        if ($candidature->cv_path) {
            Storage::disk('public')->delete($candidature->cv_path);
        }

        if ($candidature->lettre_recommandation_path) {
            Storage::disk('public')->delete($candidature->lettre_recommandation_path);
        }

        $candidature->delete();

        return response()->json([
            'success' => true,
            'message' => 'Candidature supprimée avec succès'
        ]);
    }

    public function accepter(string $id)
    {
        $candidature = Candidature::find($id);

        if (!$candidature) {
            return response()->json([
                'success' => false,
                'message' => 'Candidature non trouvée'
            ], 404);
        }

        $user = request()->user();

        // Vérifier les permissions (entreprise ou admin)
        if (!$user->isAdmin() && $candidature->offre->entreprise_id !== $user->entreprise_id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $candidature->update([
            'statut' => 'acceptee',
            'date_reponse' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidature acceptée',
            'data' => $candidature->load(['etudiant', 'offre.entreprise'])
        ]);
    }

    public function refuser(string $id)
    {
        $candidature = Candidature::find($id);

        if (!$candidature) {
            return response()->json([
                'success' => false,
                'message' => 'Candidature non trouvée'
            ], 404);
        }

        $user = request()->user();

        // Vérifier les permissions (entreprise ou admin)
        if (!$user->isAdmin() && $candidature->offre->entreprise_id !== $user->entreprise_id) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $candidature->update([
            'statut' => 'refusee',
            'date_reponse' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Candidature refusée',
            'data' => $candidature->load(['etudiant', 'offre.entreprise'])
        ]);
    }
}
