<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100"
          x-data="{
            sidebarOpen: false,
            profileOpen: false,
            darkMode: localStorage.getItem('lifeos-dark') === '1',
            toggleDark() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('lifeos-dark', this.darkMode ? '1' : '0');
            }
          }"
          x-bind:class="darkMode ? 'dark' : ''">
        <div class="min-h-screen">
            <div class="flex min-h-screen">
                @include('layouts.navigation')

                <div class="flex-1 min-w-0 lg:pl-72 pb-20 lg:pb-0">
                    <header class="sticky top-0 z-30 border-b border-slate-200/80 dark:border-slate-800/90 bg-white/90 dark:bg-slate-900/90 backdrop-blur">
                        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <button type="button"
                                        class="lg:hidden inline-flex items-center justify-center h-10 w-10 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300"
                                        @click="sidebarOpen = true">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                </button>

                                @if (isset($header))
                                    <div class="truncate">{{ $header }}</div>
                                @else
                                    <h1 class="text-base font-semibold text-slate-900 dark:text-slate-100">LifeOS</h1>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 sm:gap-3">
                                <form action="{{ route('tasks.index') }}" method="GET" class="hidden md:flex items-center">
                                    <div class="relative">
                                        <input type="text" name="search" placeholder="Search tasks..." class="w-56 lg:w-72 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-200 dark:focus:ring-teal-800" />
                                        <svg class="absolute right-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/></svg>
                                    </div>
                                </form>

                                <button type="button" @click="toggleDark" class="inline-flex items-center justify-center h-10 w-10 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                    <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/></svg>
                                    <svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 2v2m0 16v2m8-10h2M2 12H4m13.66 6.34 1.42 1.42M4.92 4.92 6.34 6.34m11.32 0 1.42-1.42M4.92 19.08l1.42-1.42M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10Z"/></svg>
                                </button>

                                <button type="button" class="relative inline-flex items-center justify-center h-10 w-10 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.4-1.4a2 2 0 0 1-.6-1.4V11a6 6 0 1 0-12 0v3.2c0 .53-.21 1.04-.59 1.41L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"/></svg>
                                    <span class="absolute top-2 right-2 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                </button>

                                <div class="relative" @click.away="profileOpen = false">
                                    <button type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2.5 py-2" @click="profileOpen = !profileOpen">
                                        <span class="h-7 w-7 rounded-lg bg-gradient-to-br from-teal-500 to-blue-500 text-white grid place-items-center text-xs font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                        <span class="hidden sm:block text-sm font-medium text-slate-700 dark:text-slate-200">{{ auth()->user()->name }}</span>
                                        <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                                    </button>

                                    <div x-show="profileOpen" x-transition class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg p-2">
                                        <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">Profile</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Log out</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>

                    <main class="px-4 sm:px-6 lg:px-8 py-6">
                        @if (session('status'))
                            <div class="mb-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-300/70 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300 px-4 py-3">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-300/70 dark:border-rose-700 text-rose-700 dark:text-rose-300 px-4 py-3">
                                <p class="font-semibold">Please fix the following:</p>
                                <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
