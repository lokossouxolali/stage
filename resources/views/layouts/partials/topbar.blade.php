@php
    $notificationsNonLues = \App\Models\Notification::where('user_id', auth()->id())->where('lu', false)->count();
@endphp

<header class="topbar">
    <div>
        <h1>@yield('page-title', 'Tableau de bord')</h1>
    </div>

    <div class="topbar-actions">
        <a href="{{ route('notifications.index') }}" class="icon-button position-relative" title="Notifications">
            <i class="fas fa-bell"></i>
            <span id="notification-count" class="notification-dot" style="{{ $notificationsNonLues > 0 ? '' : 'display: none;' }}">
                {{ $notificationsNonLues > 99 ? '99+' : $notificationsNonLues }}
            </span>
        </a>

        <div class="dropdown">
            <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                @if(auth()->user()->photo_path && auth()->user()->photo_url)
                    <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name }}" class="avatar avatar-sm" onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
                    <span class="avatar avatar-sm avatar-fallback" style="display: none;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                @else
                    <span class="avatar avatar-sm avatar-fallback">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
                <span class="profile-button-name">{{ auth()->user()->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-edit me-2"></i>Profil
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt me-2"></i>Deconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

@push('scripts')
<script>
let unreadNotifications = {{ $notificationsNonLues }};

setInterval(async () => {
    try {
        const response = await fetch('{{ route('notifications.nombre-non-lues') }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            return;
        }

        const data = await response.json();
        const count = Number(data.nombre || 0);
        const badge = document.getElementById('notification-count');

        if (!badge) {
            return;
        }

        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = count > 0 ? '' : 'none';

        if (count > unreadNotifications && window.bootstrap) {
            const latestResponse = await fetch('{{ route('notifications.derniere-non-lue') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const latestData = latestResponse.ok ? await latestResponse.json() : {};
            const notification = latestData.notification || {};
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-bg-primary border-0 position-fixed bottom-0 end-0 m-3';
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${escapeNotificationText(notification.message || 'Nouvelle notification reçue')}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
                </div>
            `;
            document.body.appendChild(toast);
            new bootstrap.Toast(toast, { delay: 4000 }).show();
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        }

        unreadNotifications = count;
    } catch (error) {
        // Le badge se rafraîchira au prochain intervalle.
    }
}, 10000);

function escapeNotificationText(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
</script>
@endpush
