<aside>
    <div class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden" x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"></div>

    <div class="fixed z-50 inset-y-0 left-0 w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transform transition-transform duration-200 lg:translate-x-0"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         @keydown.escape.window="sidebarOpen = false">
        <div class="h-16 px-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3">
                <span class="h-9 w-9 rounded-xl bg-gradient-to-br from-teal-500 to-blue-500 text-white grid place-items-center text-sm font-bold">LO</span>
                <span class="text-base font-semibold text-slate-900 dark:text-slate-100">LifeOS</span>
            </a>
            <button type="button" class="lg:hidden h-9 w-9 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300" @click="sidebarOpen = false">
                <svg class="h-4 w-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-4 overflow-y-auto h-[calc(100vh-64px)]">
            <p class="px-2 text-xs font-semibold tracking-wide uppercase text-slate-400 dark:text-slate-500">Workspace</p>
            <nav class="mt-3 space-y-1.5">
                <a href="{{ route('dashboard') }}" class="lifeos-nav-link {{ request()->routeIs('dashboard') ? 'lifeos-nav-link-active' : '' }}">
                    <span class="lifeos-nav-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-10h8V3h-8v8Z"/></svg></span>
                    Dashboard
                </a>
                <a href="{{ route('tasks.index') }}" class="lifeos-nav-link {{ request()->routeIs('tasks.*') ? 'lifeos-nav-link-active' : '' }}">
                    <span class="lifeos-nav-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9M9 5V3h6v2M9 5h6m-7 6h8m-8 4h5"/></svg></span>
                    Tasks
                </a>
                <a href="{{ route('diet-items.index') }}" class="lifeos-nav-link {{ request()->routeIs('diet-items.*') ? 'lifeos-nav-link-active' : '' }}">
                    <span class="lifeos-nav-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3v10a4 4 0 0 0 8 0V3M8 7h8"/></svg></span>
                    Diet
                </a>
                <a href="{{ route('workouts.index') }}" class="lifeos-nav-link {{ request()->routeIs('workouts.*') ? 'lifeos-nav-link-active' : '' }}">
                    <span class="lifeos-nav-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 8v8m12-8v8M4 10h4v4H4m12-4h4v4h-4M8 12h8"/></svg></span>
                    Workouts
                </a>
                <a href="{{ route('habits.index') }}" class="lifeos-nav-link {{ request()->routeIs('habits.*') ? 'lifeos-nav-link-active' : '' }}">
                    <span class="lifeos-nav-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21a9 9 0 1 0-9-9 9 9 0 0 0 9 9Zm0-14v5l3 3"/></svg></span>
                    Habits
                </a>
                <a href="{{ route('calendar.index') }}" class="lifeos-nav-link {{ request()->routeIs('calendar.*') ? 'lifeos-nav-link-active' : '' }}">
                    <span class="lifeos-nav-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 2v4m8-4v4M3 10h18M5 6h14a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2Z"/></svg></span>
                    Calendar
                </a>
                <a href="{{ route('profile.edit') }}" class="lifeos-nav-link {{ request()->routeIs('profile.*') ? 'lifeos-nav-link-active' : '' }}">
                    <span class="lifeos-nav-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2m13-13a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/></svg></span>
                    Profile
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="lifeos-nav-link {{ request()->routeIs('admin.*') ? 'lifeos-nav-link-active' : '' }}">
                        <span class="lifeos-nav-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 4 7v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-4Z"/></svg></span>
                        Admin
                    </a>
                @endif
            </nav>
        </div>
    </div>

    <nav class="fixed bottom-0 left-0 right-0 z-30 lg:hidden border-t border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur px-2 py-2">
        <div class="grid grid-cols-5 gap-1">
            <a href="{{ route('dashboard') }}" class="lifeos-mobile-link {{ request()->routeIs('dashboard') ? 'lifeos-mobile-link-active' : '' }}">Home</a>
            <a href="{{ route('tasks.index') }}" class="lifeos-mobile-link {{ request()->routeIs('tasks.*') ? 'lifeos-mobile-link-active' : '' }}">Tasks</a>
            <a href="{{ route('diet-items.index') }}" class="lifeos-mobile-link {{ request()->routeIs('diet-items.*') ? 'lifeos-mobile-link-active' : '' }}">Diet</a>
            <a href="{{ route('workouts.index') }}" class="lifeos-mobile-link {{ request()->routeIs('workouts.*') ? 'lifeos-mobile-link-active' : '' }}">Workout</a>
            <a href="{{ route('habits.index') }}" class="lifeos-mobile-link {{ request()->routeIs('habits.*') ? 'lifeos-mobile-link-active' : '' }}">Habits</a>
        </div>
    </nav>
</aside>
