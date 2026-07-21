<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome back, {{ auth()->user()->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="text-sm text-gray-500">Today's overall progress</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $overallProgress }}%</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="rounded-lg border p-4">
                        <p class="text-sm text-gray-500">Tasks</p>
                        <p class="text-xl font-semibold">{{ $taskProgress }}%</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $totals['todayTasks'][0] }}/{{ $totals['todayTasks'][1] }}</p>
                    </div>
                    <div class="rounded-lg border p-4">
                        <p class="text-sm text-gray-500">Diet</p>
                        <p class="text-xl font-semibold">{{ $dietProgress }}%</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $totals['diet'][0] }}/{{ $totals['diet'][1] }}</p>
                    </div>
                    <div class="rounded-lg border p-4">
                        <p class="text-sm text-gray-500">Workout</p>
                        <p class="text-xl font-semibold">{{ $workoutProgress }}%</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $totals['workout'][0] }}/{{ $totals['workout'][1] }}</p>
                    </div>
                    <div class="rounded-lg border p-4">
                        <p class="text-sm text-gray-500">Habits</p>
                        <p class="text-xl font-semibold">{{ $habitProgress }}%</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $totals['habit'][0] }}/{{ $totals['habit'][1] }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900">Pending Tasks</h3>
                    <div class="mt-4 space-y-3">
                        @forelse($pendingTasks as $task)
                            <div class="border rounded-md p-3 flex items-start justify-between">
                                <div>
                                    <p class="font-medium">{{ $task->title }}</p>
                                    <p class="text-xs text-gray-500">Priority: {{ ucfirst($task->priority) }} @if($task->due_date) | Due: {{ $task->due_date->format('M d, Y') }} @endif</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No pending tasks yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900">Upcoming Tasks</h3>
                    <div class="mt-4 space-y-3">
                        @forelse($upcomingTasks as $task)
                            <div class="border rounded-md p-3">
                                <p class="font-medium">{{ $task->title }}</p>
                                <p class="text-xs text-gray-500">{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No upcoming tasks.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900">Today's Checklist</h3>
                <p class="text-sm text-gray-500 mt-1">Check items off as you finish them.</p>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-5">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Tasks</h4>
                        <div class="space-y-2">
                            @forelse($todayTasks as $task)
                                <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="border rounded-md px-3 py-2 flex items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="h-5 w-5 rounded border border-gray-400 flex items-center justify-center {{ $task->status === 'completed' ? 'bg-green-600 border-green-600 text-white' : 'bg-white text-transparent' }}" aria-label="Toggle task completion">{{ $task->status === 'completed' ? '✓' : '.' }}</button>
                                    <div class="min-w-0">
                                        <p class="text-sm {{ $task->status === 'completed' ? 'line-through text-gray-400' : 'text-gray-800' }}">{{ $task->title }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst($task->priority) }} priority</p>
                                    </div>
                                </form>
                            @empty
                                <p class="text-sm text-gray-500">No tasks scheduled for today.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Diet</h4>
                        <div class="space-y-2">
                            @forelse($todayDietItems as $item)
                                <form method="POST" action="{{ route('diet-items.toggle', $item) }}" class="border rounded-md px-3 py-2 flex items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="h-5 w-5 rounded border border-gray-400 flex items-center justify-center {{ $item->is_completed ? 'bg-green-600 border-green-600 text-white' : 'bg-white text-transparent' }}" aria-label="Toggle diet item completion">{{ $item->is_completed ? '✓' : '.' }}</button>
                                    <div class="min-w-0">
                                        <p class="text-sm {{ $item->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">{{ $item->meal_name }} - {{ $item->food_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->scheduled_time ?: 'Any time' }}</p>
                                    </div>
                                </form>
                            @empty
                                <p class="text-sm text-gray-500">No diet items for today.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Workouts</h4>
                        <div class="space-y-2">
                            @forelse($todayWorkouts as $workout)
                                <form method="POST" action="{{ route('workouts.toggle', $workout) }}" class="border rounded-md px-3 py-2 flex items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="h-5 w-5 rounded border border-gray-400 flex items-center justify-center {{ $workout->is_completed ? 'bg-green-600 border-green-600 text-white' : 'bg-white text-transparent' }}" aria-label="Toggle workout completion">{{ $workout->is_completed ? '✓' : '.' }}</button>
                                    <div class="min-w-0">
                                        <p class="text-sm {{ $workout->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">{{ $workout->exercise_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $workout->workout_type ?: 'General' }}</p>
                                    </div>
                                </form>
                            @empty
                                <p class="text-sm text-gray-500">No workouts planned for today.</p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Habits</h4>
                        <div class="space-y-2">
                            @forelse($todayHabits as $habit)
                                @php($isCompleted = (bool) ($todayHabitCompletionMap[$habit->id] ?? false))
                                <form method="POST" action="{{ route('habits.toggle-completion', $habit) }}" class="border rounded-md px-3 py-2 flex items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="date" value="{{ $todayDate }}">
                                    <button type="submit" class="h-5 w-5 rounded border border-gray-400 flex items-center justify-center {{ $isCompleted ? 'bg-green-600 border-green-600 text-white' : 'bg-white text-transparent' }}" aria-label="Toggle habit completion">{{ $isCompleted ? '✓' : '.' }}</button>
                                    <div class="min-w-0">
                                        <p class="text-sm {{ $isCompleted ? 'line-through text-gray-400' : 'text-gray-800' }}">{{ $habit->name }}</p>
                                        <p class="text-xs text-gray-500">Daily habit</p>
                                    </div>
                                </form>
                            @empty
                                <p class="text-sm text-gray-500">No active habits yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-900">Quick Actions</h3>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm">Manage Tasks</a>
                    <a href="{{ route('diet-items.index') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm">Manage Diet</a>
                    <a href="{{ route('workouts.index') }}" class="px-4 py-2 bg-orange-600 text-white rounded-md text-sm">Manage Workouts</a>
                    <a href="{{ route('habits.index') }}" class="px-4 py-2 bg-sky-600 text-white rounded-md text-sm">Manage Habits</a>
                    <a href="{{ route('calendar.index') }}" class="px-4 py-2 bg-gray-700 text-white rounded-md text-sm">Open Calendar</a>
                </div>
                <p class="text-sm text-gray-500 mt-4">AI assistant architecture is intentionally deferred for later phase.</p>
            </div>
        </div>
    </div>
</x-app-layout>
