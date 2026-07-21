<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-sm text-gray-500">Total Users</p><p class="text-2xl font-semibold">{{ $totalUsers }}</p></div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-sm text-gray-500">Active Users</p><p class="text-2xl font-semibold">{{ $activeUsers }}</p></div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-sm text-gray-500">Suspended Users</p><p class="text-2xl font-semibold">{{ $suspendedUsers }}</p></div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-sm text-gray-500">Total Tasks</p><p class="text-2xl font-semibold">{{ $totalTasks }}</p></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-sm text-gray-500">Diet Items</p><p class="text-2xl font-semibold">{{ $totalDietItems }}</p></div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-sm text-gray-500">Workouts</p><p class="text-2xl font-semibold">{{ $totalWorkouts }}</p></div>
                <div class="bg-white shadow-sm sm:rounded-lg p-4"><p class="text-sm text-gray-500">Habits</p><p class="text-2xl font-semibold">{{ $totalHabits }}</p></div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Latest Users</h3>
                <div class="space-y-2">
                    @foreach($latestUsers as $user)
                        <div class="border rounded-md p-3 flex items-center justify-between">
                            <div>
                                <p class="font-medium">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-md {{ $user->is_suspended ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ $user->is_suspended ? 'Suspended' : 'Active' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
