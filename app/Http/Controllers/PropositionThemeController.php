<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropositionTheme;
use App\Models\User;
use App\Models\Notification;

class PropositionThemeController extends Controller
{
    /**
     * Afficher la liste des propositions de thèmes (Admin)
     */
    public function index()
    {
        $propositions = PropositionTheme::with(['etudiant', 'directeurMemoire'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('propositions.index', compact('propositions'));
    }

    /**
     * Afficher le formulaire de création (Étudiant)
     */
    public function create()
    {
        $enseignants = User::where('role', 'enseignant')->get();
        return view('propositions.create', compact('enseignants'));
    }

    /**
     * Enregistrer une nouvelle proposition (Étudiant)
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'objectifs' => 'nullable|string',
            'methodologie' => 'nullable|string',
            'directeur_memoire_id' => 'nullable|exists:users,id',
        ]);

        $proposition = PropositionTheme::create([
            'etudiant_id' => auth()->id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'objectifs' => $request->objectifs,
            'methodologie' => $request->methodologie,
            'directeur_memoire_id' => $request->directeur_memoire_id ?? auth()->user()->directeur_memoire_id,
            'statut' => 'en_attente',
            'date_soumission' => now(),
        ]);

        // Créer une notification pour l'admin
        Notification::create([
            'user_id' => User::where('role', 'admin')->first()->id ?? 1,
            'type' => 'nouvelle_proposition',
            'titre' => 'Nouvelle proposition de thème',
            'message' => auth()->user()->name . ' a soumis une nouvelle proposition de thème : ' . $proposition->titre,
            'lien' => route('propositions.show', $proposition->id),
        ]);

        return redirect()->route('propositions.mes')
            ->with('success', 'Proposition de thème soumise avec succès');
    }

    /**
     * Afficher une proposition spécifique
     */
    public function show(PropositionTheme $proposition)
    {
        $proposition->load(['etudiant', 'directeurMemoire']);
        return view('propositions.show', compact('proposition'));
    }

    /**
     * Afficher le formulaire d'édition (Étudiant - seulement si en attente)
     */
    public function edit(PropositionTheme $proposition)
    {
        // Seul l'étudiant propriétaire peut modifier sa proposition
        if (auth()->id() !== $proposition->etudiant_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette proposition');
        }

        // Ne peut modifier que si en attente
        if ($proposition->statut !== 'en_attente') {
            return back()->with('error', 'Vous ne pouvez modifier que les propositions en attente');
        }

        $enseignants = User::where('role', 'enseignant')->get();
        return view('propositions.edit', compact('proposition', 'enseignants'));
    }

    /**
     * Mettre à jour une proposition (Étudiant)
     */
    public function update(Request $request, PropositionTheme $proposition)
    {
        // Seul l'étudiant propriétaire peut modifier sa proposition
        if (auth()->id() !== $proposition->etudiant_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette proposition');
        }

        // Ne peut modifier que si en attente
        if ($proposition->statut !== 'en_attente') {
            return back()->with('error', 'Vous ne pouvez modifier que les propositions en attente');
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'objectifs' => 'nullable|string',
            'methodologie' => 'nullable|string',
            'directeur_memoire_id' => 'nullable|exists:users,id',
        ]);

        $proposition->update($request->all());

        return redirect()->route('propositions.show', $proposition->id)
            ->with('success', 'Proposition mise à jour avec succès');
    }

    /**
     * Supprimer une proposition (Étudiant - seulement si en attente)
     */
    public function destroy(PropositionTheme $proposition)
    {
        // Seul l'étudiant propriétaire peut supprimer sa proposition
        if (auth()->id() !== $proposition->etudiant_id) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette proposition');
        }

        // Ne peut supprimer que si en attente
        if ($proposition->statut !== 'en_attente') {
            return back()->with('error', 'Vous ne pouvez supprimer que les propositions en attente');
        }

        $proposition->delete();

        return redirect()->route('propositions.mes')
            ->with('success', 'Proposition supprimée avec succès');
    }

    /**
     * Valider une proposition (Admin)
     */
    public function valider(PropositionTheme $proposition)
    {
        $proposition->update([
            'statut' => 'valide',
            'date_validation' => now(),
        ]);

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $proposition->etudiant_id,
            'type' => 'proposition_validee',
            'titre' => 'Proposition de thème validée',
            'message' => 'Votre proposition de thème "' . $proposition->titre . '" a été validée.',
            'lien' => route('propositions.show', $proposition->id),
        ]);

        return back()->with('success', 'Proposition validée avec succès');
    }

    /**
     * Refuser une proposition (Admin)
     */
    public function refuser(Request $request, PropositionTheme $proposition)
    {
        $request->validate([
            'commentaires_admin' => 'required|string',
        ]);

        $proposition->update([
            'statut' => 'refuse',
            'commentaires_admin' => $request->commentaires_admin,
        ]);

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $proposition->etudiant_id,
            'type' => 'proposition_refusee',
            'titre' => 'Proposition de thème refusée',
            'message' => 'Votre proposition de thème "' . $proposition->titre . '" a été refusée. Commentaires : ' . $request->commentaires_admin,
            'lien' => route('propositions.show', $proposition->id),
        ]);

        return back()->with('success', 'Proposition refusée avec succès');
    }

    /**
     * Consulter mes propositions (Étudiant)
     */
    public function mesPropositions()
    {
        $propositions = PropositionTheme::where('etudiant_id', auth()->id())
            ->with('directeurMemoire')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('propositions.mes', compact('propositions'));
    }

    /**
     * Consulter les propositions des étudiants encadrés (Enseignant)
     */
    public function propositionsEncadrees()
    {
        $user = auth()->user();
        $propositions = PropositionTheme::where('directeur_memoire_id', $user->id)
            ->orWhereHas('etudiant', function($query) use ($user) {
                $query->where('directeur_memoire_id', $user->id);
            })
            ->with('etudiant')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('propositions.encadrees', compact('propositions'));
    }

    /**
     * Ajouter un commentaire sur une proposition (Enseignant)
     */
    public function ajouterCommentaire(Request $request, PropositionTheme $proposition)
    {
        $request->validate([
            'commentaires_enseignant' => 'required|string',
        ]);

        $proposition->update([
            'commentaires_enseignant' => $request->commentaires_enseignant,
        ]);

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $proposition->etudiant_id,
            'type' => 'commentaire_proposition',
            'titre' => 'Nouveau commentaire sur votre proposition',
            'message' => auth()->user()->name . ' a ajouté un commentaire sur votre proposition de thème.',
            'lien' => route('propositions.show', $proposition->id),
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès');
    }

    /**
     * Valider une proposition (Enseignant)
     */
    public function validerParEnseignant(PropositionTheme $proposition)
    {
        // Vérifier que l'enseignant est le directeur de mémoire
        if (auth()->id() !== $proposition->directeur_memoire_id) {
            abort(403, 'Vous n\'êtes pas autorisé à valider cette proposition');
        }

        $proposition->update([
            'statut' => 'valide',
            'date_validation' => now(),
        ]);

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $proposition->etudiant_id,
            'type' => 'proposition_validee',
            'titre' => 'Proposition de thème validée',
            'message' => 'Votre proposition de thème "' . $proposition->titre . '" a été validée par votre directeur de mémoire.',
            'lien' => route('propositions.show', $proposition->id),
        ]);

        return back()->with('success', 'Proposition validée avec succès');
    }

    /**
     * Rejeter une proposition (Enseignant)
     */
    public function rejeterParEnseignant(Request $request, PropositionTheme $proposition)
    {
        // Vérifier que l'enseignant est le directeur de mémoire
        if (auth()->id() !== $proposition->directeur_memoire_id) {
            abort(403, 'Vous n\'êtes pas autorisé à rejeter cette proposition');
        }

        $request->validate([
            'commentaires_enseignant' => 'required|string',
        ]);

        $proposition->update([
            'statut' => 'refuse',
            'commentaires_enseignant' => $request->commentaires_enseignant,
        ]);

        // Créer une notification pour l'étudiant
        Notification::create([
            'user_id' => $proposition->etudiant_id,
            'type' => 'proposition_refusee',
            'titre' => 'Proposition de thème refusée',
            'message' => 'Votre proposition de thème "' . $proposition->titre . '" a été refusée par votre directeur de mémoire.',
            'lien' => route('propositions.show', $proposition->id),
        ]);

        return back()->with('success', 'Proposition refusée avec succès');
    }
}
