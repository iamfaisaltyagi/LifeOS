<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Workout Checklist</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('workouts.index') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Date</label>
                        <input type="date" name="date" value="{{ $date }}" class="border rounded-md px-3 py-2">
                    </div>
                    <button class="bg-gray-900 text-white rounded-md px-4 py-2">Load</button>
                </form>
                <div class="mt-4 border rounded-md p-3 inline-block">
                    <p class="text-sm text-gray-500">Completion</p>
                    <p class="text-2xl font-semibold">{{ $completionPercentage }}%</p>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold mb-3">Add Workout</h3>
                <form method="POST" action="{{ route('workouts.store') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input name="exercise_name" placeholder="Exercise name" class="border rounded-md px-3 py-2 md:col-span-2" required>
                    <input type="number" min="1" name="sets" placeholder="Sets" class="border rounded-md px-3 py-2">
                    <input type="number" min="1" name="repetitions" placeholder="Reps" class="border rounded-md px-3 py-2">
                    <input type="number" min="1" name="duration_minutes" placeholder="Duration (min)" class="border rounded-md px-3 py-2">
                    <input name="workout_type" placeholder="Type" class="border rounded-md px-3 py-2">
                    <button class="bg-orange-600 text-white rounded-md px-4 py-2 md:col-span-1">Add</button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Exercise</th>
                        <th class="py-2">Sets/Reps</th>
                        <th class="py-2">Duration</th>
                        <th class="py-2">Type</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($workouts as $workout)
                        <tr class="border-b">
                            <td class="py-3">{{ $workout->exercise_name }}</td>
                            <td class="py-3">{{ $workout->sets ?: '-' }}/{{ $workout->repetitions ?: '-' }}</td>
                            <td class="py-3">{{ $workout->duration_minutes ?: '-' }}</td>
                            <td class="py-3">{{ $workout->workout_type ?: '-' }}</td>
                            <td class="py-3">{{ $workout->is_completed ? 'Completed' : 'Pending' }}</td>
                            <td class="py-3">
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('workouts.toggle', $workout) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="px-2 py-1 rounded-md text-xs {{ $workout->is_completed ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">{{ $workout->is_completed ? 'Undo' : 'Complete' }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('workouts.destroy', $workout) }}" onsubmit="return confirm('Delete workout?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-1 rounded-md text-xs bg-red-100 text-red-700">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-gray-500">No workouts for this date.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $workouts->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
