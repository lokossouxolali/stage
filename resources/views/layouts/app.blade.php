<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="@guest auth-page @endguest">
    @auth
        <div class="app-shell">
            @include('layouts.partials.sidebar')

            <div class="app-main">
                @include('layouts.partials.topbar')

                <main class="content-wrapper">
                    @include('layouts.partials.toasts')
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <main class="auth-shell">
            @include('layouts.partials.toasts')
            @yield('content')
        </main>
    @endauth

    @stack('scripts')
</body>
</html>
