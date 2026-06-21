@extends('layouts.app')

@section('title', 'Tokens inscription')
@section('page-title', 'Tokens inscription')

@section('content')
@php
    $roleLabels = [
        'etudiant' => 'Etudiant',
        'entreprise' => 'Entreprise',
        'enseignant' => 'Enseignant',
    ];
@endphp

@if(session('generated_tokens'))
    <div class="card mb-4 border-success">
        <div class="card-header bg-white text-success">
            <i class="fas fa-key me-2"></i>Tokens generes
        </div>
        <div class="card-body">
            <div class="alert alert-info small mb-3">Ces tokens sont aussi disponibles dans l'export admin.</div>
            <div class="token-list">
                @foreach(session('generated_tokens') as $token)
                    <code>{{ $token }}</code>
                @endforeach
            </div>
        </div>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus-circle me-2 text-primary"></i>Generer des tokens</div>
            <div class="card-body">
                <form method="POST" action="{{ route('registration-tokens.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="role" class="form-label">Profil</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Selectionner</option>
                            @foreach($roleLabels as $value => $label)
                                <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantite</label>
                        <input type="number" min="1" max="100" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-ticket-alt me-2"></i>Generer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <span><i class="fas fa-list me-2 text-primary"></i>Liste des tokens</span>
                <form method="GET" action="{{ route('registration-tokens.index') }}" class="d-flex gap-2">
                    <select class="form-select form-select-sm" name="role">
                        <option value="">Tous</option>
                        @foreach($roleLabels as $value => $label)
                            <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-filter"></i></button>
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('registration-tokens.export', ['role' => request('role')]) }}" title="Exporter">
                        <i class="fas fa-download"></i>
                    </a>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Token</th>
                            <th>Profil</th>
                            <th>Statut</th>
                            <th>Cree par</th>
                            <th>Utilise par</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tokens as $token)
                            <tr>
                                <td>#{{ $token->id }}</td>
                                <td><code>{{ $token->plain_token ?: 'Non disponible' }}</code></td>
                                <td>{{ $roleLabels[$token->role] ?? ucfirst($token->role) }}</td>
                                <td>
                                    @if($token->used_at)
                                        <span class="badge-soft badge-soft-secondary">Utilise</span>
                                    @else
                                        <span class="badge-soft badge-soft-success">Disponible</span>
                                    @endif
                                </td>
                                <td>{{ optional($token->creator)->email ?: '-' }}</td>
                                <td>{{ optional($token->usedBy)->email ?: '-' }}</td>
                                <td>{{ $token->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Aucun token trouve.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tokens->hasPages())
                <div class="card-footer d-flex justify-content-end">
                    {{ $tokens->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
