<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LifeOS') }}</title>
        @include('partials.lifeos-theme')

        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="lo-base lo-auth-layout">
        <div class="lo-auth-shell">
            <aside class="lo-auth-hero">
                <a href="{{ url('/') }}" class="lo-brand" aria-label="LifeOS Home">
                    <span class="lo-brand-mark">LO</span>
                    <span class="lo-brand-name">LifeOS</span>
                </a>

                <h1 class="lo-auth-headline">Plan your day. Build better habits. Improve your life.</h1>
                <p class="lo-auth-copy">
                    Access your secure personal workspace for tasks, wellness checklists, and progress insights.
                </p>

                <div class="lo-badge-row" aria-label="Feature Tags">
                    <span class="lo-badge">Tasks</span>
                    <span class="lo-badge">Diet</span>
                    <span class="lo-badge">Workouts</span>
                    <span class="lo-badge">Habits</span>
                    <span class="lo-badge">Calendar</span>
                </div>
            </aside>

            <section class="lo-auth-panel">
                {{ $slot }}
            </section>
        </div>

        <script>
            document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var inputId = button.getAttribute('data-target');
                    var input = document.getElementById(inputId);

                    if (!input) {
                        return;
                    }

                    var isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    button.textContent = isPassword ? 'Hide' : 'Show';
                });
            });
        </script>
    </body>
</html>
