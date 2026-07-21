<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Calendar</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('calendar.index') }}" class="flex items-end gap-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Select Date</label>
                        <input type="date" name="date" value="{{ $date }}" class="border rounded-md px-3 py-2" required>
                    </div>
                    <button class="bg-gray-900 text-white rounded-md px-4 py-2">View Day</button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold">Tasks</h3>
                    <div class="mt-3 space-y-2">
                        @forelse($tasks as $task)
                            <div class="border rounded-md p-3">
                                <p class="font-medium">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($task->status) }} | {{ ucfirst($task->priority) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No tasks for this date.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold">Diet</h3>
                    <div class="mt-3 space-y-2">
                        @forelse($dietItems as $item)
                            <div class="border rounded-md p-3">
                                <p class="font-medium">{{ $item->meal_name }} - {{ $item->food_name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->is_completed ? 'Completed' : 'Pending' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No diet items for this date.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold">Workouts</h3>
                    <div class="mt-3 space-y-2">
                        @forelse($workouts as $workout)
                            <div class="border rounded-md p-3">
                                <p class="font-medium">{{ $workout->exercise_name }}</p>
                                <p class="text-xs text-gray-500">{{ $workout->is_completed ? 'Completed' : 'Pending' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No workouts for this date.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold">Habits</h3>
                    <div class="mt-3 space-y-2">
                        @forelse($habits as $habit)
                            <div class="border rounded-md p-3">
                                <p class="font-medium">{{ $habit->name }}</p>
                                <p class="text-xs text-gray-500">{{ ($completionMap[$habit->id] ?? false) ? 'Completed' : 'Pending' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No active habits found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
