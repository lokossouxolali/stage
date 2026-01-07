<?php

namespace App\Policies;

use App\Models\PropositionTheme;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropositionThemePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isEtudiant() || $user->isEnseignant() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PropositionTheme $propositionTheme): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEtudiant()) {
            return $propositionTheme->etudiant_id === $user->id;
        }

        if ($user->isEnseignant()) {
            return $propositionTheme->envoye_au_directeur && $propositionTheme->directeur_memoire_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isEtudiant();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PropositionTheme $propositionTheme): bool
    {
        return $user->isEtudiant() && $propositionTheme->etudiant_id === $user->id && $propositionTheme->statut === 'en_attente';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PropositionTheme $propositionTheme): bool
    {
        return $user->isEtudiant() && $propositionTheme->etudiant_id === $user->id && $propositionTheme->statut === 'en_attente';
    }

    /**
     * Determine whether the user can validate the proposition.
     */
    public function valider(User $user, PropositionTheme $propositionTheme): bool
    {
        if ($user->isAdmin()) {
            return $propositionTheme->envoye_a_l_admin;
        }

        if ($user->isEnseignant()) {
            return $propositionTheme->envoye_au_directeur && $propositionTheme->directeur_memoire_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can reject the proposition.
     */
    public function refuser(User $user, PropositionTheme $propositionTheme): bool
    {
        return $this->valider($user, $propositionTheme);
    }

    /**
     * Determine whether the user can download files.
     */
    public function downloadFile(User $user, PropositionTheme $propositionTheme): bool
    {
        return $this->view($user, $propositionTheme);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PropositionTheme $propositionTheme): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PropositionTheme $propositionTheme): bool
    {
        return false;
    }
}
