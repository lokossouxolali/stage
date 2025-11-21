@extends('layouts.app')

@section('title', 'Mes Notifications')
@section('page-title', 'Mes Notifications')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h6 class="mb-0 text-muted fw-normal">
            <i class="fas fa-bell me-2"></i>
            Mes Notifications
        </h6>
    </div>
    <div class="col-md-6 text-end">
        @if($notifications->where('lu', false)->count() > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-check-double me-1"></i>
                    Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-3">
        @if($notifications->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($notifications as $notification)
                    <div class="list-group-item border-0 px-0 py-3 {{ !$notification->lu ? 'bg-light' : '' }}">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                @if(!$notification->lu)
                                    <span class="badge bg-primary rounded-circle" style="width: 10px; height: 10px;"></span>
                                @else
                                    <i class="fas fa-circle text-muted" style="font-size: 0.5rem;"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 fw-semibold" style="font-size: 0.85rem;">
                                        {{ $notification->titre }}
                                    </h6>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <p class="mb-2 text-muted" style="font-size: 0.8rem;">
                                    {{ $notification->message }}
                                </p>
                                <div class="d-flex gap-2">
                                    @if($notification->lien)
                                        <a href="{{ $notification->lien }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.75rem;">
                                            <i class="fas fa-eye me-1"></i>
                                            Voir
                                        </a>
                                    @endif
                                    @if(!$notification->lu)
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" style="font-size: 0.75rem;">
                                                <i class="fas fa-check me-1"></i>
                                                Marquer comme lu
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="d-inline" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size: 0.75rem;">
                                            <i class="fas fa-trash me-1"></i>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)
                        <hr class="my-0" style="opacity: 0.1;">
                    @endif
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                <div style="font-size: 0.75rem;">
                    {{ $notifications->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3" style="opacity: 0.5;"></i>
                <h6 class="text-muted mb-2" style="font-size: 0.85rem;">Aucune notification</h6>
                <p class="text-muted mb-0" style="font-size: 0.75rem;">Vous n'avez aucune notification pour le moment.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .list-group-item {
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f9fafb !important;
    }

    .card {
        border: 1px solid #e5e7eb;
    }

    .badge.bg-primary {
        background-color: #3b82f6 !important;
    }
</style>
@endpush

