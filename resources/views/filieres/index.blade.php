@extends('layouts.app')

@section('title', 'Gestion des filières')
@section('page-title', 'Gestion des filières')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-graduation-cap me-2 text-primary"></i>Liste des filières</span>
        <a href="{{ route('filieres.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-2"></i>Ajouter une filière</a>
    </div>
    <div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead><tr><th>Nom</th><th>Code</th><th>Étudiants</th><th>Offres</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse($filieres as $filiere)
            <tr>
                <td><div class="fw-semibold">{{ $filiere->nom }}</div>@if($filiere->description)<small class="text-muted">{{ $filiere->description }}</small>@endif</td>
                <td>{{ $filiere->code ?: '—' }}</td>
                <td><span class="badge-soft badge-soft-info">{{ $filiere->etudiants_count }}</span></td>
                <td><span class="badge-soft badge-soft-info">{{ $filiere->offres_count }}</span></td>
                <td class="text-end">
                    <a href="{{ route('filieres.edit', $filiere) }}" class="btn btn-sm btn-outline-warning" title="Modifier"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('filieres.destroy', $filiere) }}" class="d-inline" onsubmit="return confirm('Supprimer cette filière ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" @disabled($filiere->etudiants_count > 0 || $filiere->offres_count > 0)><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center py-5 text-muted">Aucune filière enregistrée.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    @if($filieres->hasPages())<div class="card-footer d-flex justify-content-end">{{ $filieres->links('pagination::bootstrap-5') }}</div>@endif
</div>
@endsection
