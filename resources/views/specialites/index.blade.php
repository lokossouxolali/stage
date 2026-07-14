@extends('layouts.app')

@section('title', 'Spécialités / Départements')
@section('page-title', 'Gestion des spécialités / départements')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-sitemap me-2 text-primary"></i>Liste des spécialités / départements</span>
        <a href="{{ route('specialites.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-2"></i>Ajouter</a>
    </div>
    <div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead><tr><th>Nom</th><th>Code</th><th>Enseignants</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        @forelse($specialites as $specialite)
            <tr>
                <td><div class="fw-semibold">{{ $specialite->nom }}</div>@if($specialite->description)<small class="text-muted">{{ $specialite->description }}</small>@endif</td>
                <td>{{ $specialite->code ?: '—' }}</td>
                <td><span class="badge-soft badge-soft-info">{{ $specialite->enseignants_count }}</span></td>
                <td class="text-end">
                    <a href="{{ route('specialites.edit', $specialite) }}" class="btn btn-sm btn-outline-warning" title="Modifier"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('specialites.destroy', $specialite) }}" class="d-inline" onsubmit="return confirm('Supprimer cette spécialité / ce département ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer" @disabled($specialite->enseignants_count > 0)><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center py-5 text-muted">Aucune spécialité enregistrée.</td></tr>
        @endforelse
        </tbody>
    </table></div>
    @if($specialites->hasPages())<div class="card-footer d-flex justify-content-end">{{ $specialites->links('pagination::bootstrap-5') }}</div>@endif
</div>
@endsection
