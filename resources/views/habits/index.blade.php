<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Habit Tracker</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('habits.index') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Date</label>
                        <input type="date" name="date" value="{{ $selectedDate }}" class="border rounded-md px-3 py-2">
                    </div>
                    <button class="bg-gray-900 text-white rounded-md px-4 py-2">Load</button>
                </form>
                <div class="mt-4 border rounded-md p-3 inline-block">
                    <p class="text-sm text-gray-500">Daily Completion</p>
                    <p class="text-2xl font-semibold">{{ $dailyCompletion }}%</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Create Habit</h3>
                <form method="POST" action="{{ route('habits.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    @csrf
                    <input name="name" placeholder="Habit name" class="border rounded-md px-3 py-2" required>
                    <input name="description" placeholder="Description" class="border rounded-md px-3 py-2 md:col-span-2">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_active" value="1" checked>
                        Active
                    </label>
                    <button class="bg-sky-600 text-white rounded-md px-4 py-2">Add Habit</button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Habit</th>
                        <th class="py-2">Today</th>
                        <th class="py-2">Weekly Progress</th>
                        <th class="py-2">Streak</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($habitData as $row)
                        @php($habit = $row['habit'])
                        <tr class="border-b align-top">
                            <td class="py-3">
                                <p class="font-medium">{{ $habit->name }}</p>
                                <p class="text-xs text-gray-500">{{ $habit->description }}</p>
                            </td>
                            <td class="py-3">{{ $row['today_completed'] ? 'Completed' : 'Pending' }}</td>
                            <td class="py-3">{{ $row['weekly_completed_days'] }}/7 days</td>
                            <td class="py-3">{{ $row['streak'] }} days</td>
                            <td class="py-3">{{ $habit->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="py-3">
                                <div class="flex gap-2 flex-wrap">
                                    <form method="POST" action="{{ route('habits.toggle-completion', $habit) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="date" value="{{ $selectedDate }}">
                                        <button class="px-2 py-1 rounded-md text-xs {{ $row['today_completed'] ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">{{ $row['today_completed'] ? 'Undo' : 'Complete' }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('habits.destroy', $habit) }}" onsubmit="return confirm('Delete habit?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-1 rounded-md text-xs bg-red-100 text-red-700">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-gray-500">No habits yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
