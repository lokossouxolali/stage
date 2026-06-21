@php
    $flashMessages = [
        'success' => ['icon' => 'fa-check-circle', 'class' => 'text-bg-success'],
        'error' => ['icon' => 'fa-exclamation-circle', 'class' => 'text-bg-danger'],
        'warning' => ['icon' => 'fa-exclamation-triangle', 'class' => 'text-bg-warning'],
    ];
@endphp

<div class="toast-container position-fixed top-0 end-0 p-3">
    @foreach($flashMessages as $key => $meta)
        @if(session($key))
            <div class="toast app-toast {{ $meta['class'] }}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="fas {{ $meta['icon'] }}"></i>
                    <span class="flex-grow-1">{{ session($key) }}</span>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast" aria-label="Fermer"></button>
                </div>
            </div>
        @endif
    @endforeach
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="fw-semibold mb-1"><i class="fas fa-circle-exclamation me-2"></i>Veuillez corriger les champs indiques.</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif
