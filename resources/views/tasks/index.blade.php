<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Tasks</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Plan, repeat, and complete your priorities with precision.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if ($errors->any())
                <div class="lifeos-card p-4 border-red-200 dark:border-red-900/60">
                    <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $activeTab = $filters['tab'] ?? 'today';
                $tabs = [
                    'today' => 'Today',
                    'upcoming' => 'Upcoming',
                    'overdue' => 'Overdue',
                    'completed' => 'Completed',
                    'all' => 'All',
                ];
            @endphp

            <div class="lifeos-card p-4">
                <div class="flex flex-wrap gap-2">
                    @foreach ($tabs as $key => $label)
                        <a href="{{ route('tasks.index', array_merge(request()->query(), ['tab' => $key])) }}"
                           class="rounded-lg px-3 py-1.5 text-sm font-medium transition {{ $activeTab === $key ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="lifeos-card p-6">
                <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 md:grid-cols-7 gap-3">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search title, notes, category" class="md:col-span-2 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-teal-500 focus:ring-teal-500">
                    <select name="status" class="rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-teal-500 focus:ring-teal-500">
                        <option value="">All Status</option>
                        <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                        <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Completed</option>
                    </select>
                    <select name="priority" class="rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-teal-500 focus:ring-teal-500">
                        <option value="">All Priority</option>
                        <option value="low" @selected(($filters['priority'] ?? '') === 'low')>Low</option>
                        <option value="medium" @selected(($filters['priority'] ?? '') === 'medium')>Medium</option>
                        <option value="high" @selected(($filters['priority'] ?? '') === 'high')>High</option>
                    </select>
                    <select name="sort" class="rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-teal-500 focus:ring-teal-500">
                        <option value="due_date" @selected(($filters['sort'] ?? '') === 'due_date')>Sort by Due Date</option>
                        <option value="due_time" @selected(($filters['sort'] ?? '') === 'due_time')>Sort by Due Time</option>
                        <option value="priority" @selected(($filters['sort'] ?? '') === 'priority')>Sort by Priority</option>
                        <option value="created_at" @selected(($filters['sort'] ?? '') === 'created_at')>Sort by Created</option>
                        <option value="title" @selected(($filters['sort'] ?? '') === 'title')>Sort by Title</option>
                    </select>
                    <button class="rounded-xl bg-slate-900 px-4 py-2 text-white hover:bg-slate-700 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-slate-200">Apply</button>
                    <a href="{{ route('tasks.index', ['tab' => $activeTab]) }}" class="rounded-xl bg-slate-100 px-4 py-2 text-center text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Reset</a>
                </form>
            </div>

            <div class="lifeos-card p-6">
                <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-4">Create Task</h3>
                <form method="POST" action="{{ route('tasks.store') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    @csrf
                    <input name="title" value="{{ old('title') }}" placeholder="Task title" class="md:col-span-4 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" required>
                    <input name="category" value="{{ old('category') }}" placeholder="Category" class="md:col-span-2 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <select name="priority" class="md:col-span-2 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" required>
                        <option value="medium" @selected(old('priority') === 'medium')>Medium</option>
                        <option value="low" @selected(old('priority') === 'low')>Low</option>
                        <option value="high" @selected(old('priority') === 'high')>High</option>
                    </select>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="md:col-span-2 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <input type="time" name="due_time" value="{{ old('due_time') }}" class="md:col-span-2 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">

                    <select name="repeat_type" class="md:col-span-3 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                        <option value="none" @selected(old('repeat_type', 'none') === 'none')>No Repeat</option>
                        <option value="daily" @selected(old('repeat_type') === 'daily')>Daily</option>
                        <option value="weekly" @selected(old('repeat_type') === 'weekly')>Weekly</option>
                        <option value="monthly" @selected(old('repeat_type') === 'monthly')>Monthly</option>
                        <option value="custom" @selected(old('repeat_type') === 'custom')>Custom</option>
                    </select>
                    <input type="number" min="1" max="365" name="repeat_every" value="{{ old('repeat_every') }}" placeholder="Custom every N days" class="md:col-span-3 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <input type="date" name="repeat_until" value="{{ old('repeat_until') }}" class="md:col-span-3 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                    <button class="md:col-span-3 rounded-xl bg-gradient-to-r from-teal-500 to-blue-500 px-4 py-2 text-white hover:opacity-95">Add Task</button>

                    <textarea name="description" placeholder="Task notes" class="md:col-span-12 rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">{{ old('description') }}</textarea>
                </form>
            </div>

            <div class="lifeos-card p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-200 dark:border-slate-800 text-slate-500">
                            <th class="py-2">Task</th>
                            <th class="py-2">Priority</th>
                            <th class="py-2">Due</th>
                            <th class="py-2">Repeat</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            @php
                                $isOverdue = $task->status === 'pending' && $task->due_date && $task->due_date->isPast() && ! $task->due_date->isToday();
                            @endphp
                            <tr class="border-b border-slate-100 dark:border-slate-800 align-top">
                                <td class="py-3 pr-4">
                                    <p class="font-medium {{ $task->status === 'completed' ? 'line-through text-slate-400' : 'text-slate-800 dark:text-slate-100' }}">{{ $task->title }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $task->category ?: 'No category' }}</p>
                                    @if($task->description)
                                        <p class="text-xs text-slate-500 mt-1">{{ $task->description }}</p>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="rounded-full px-2 py-1 text-xs {{ $task->priority === 'high' ? 'bg-red-100 text-red-700' : ($task->priority === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                    @if($isOverdue)
                                        <p class="mt-1 text-[11px] font-medium text-red-600">Overdue</p>
                                    @endif
                                </td>
                                <td class="py-3 text-slate-600 dark:text-slate-300">
                                    <p>{{ $task->due_date ? $task->due_date->format('M d, Y') : '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $task->due_time ? \Illuminate\Support\Carbon::createFromFormat('H:i:s', strlen($task->due_time) === 5 ? $task->due_time.':00' : $task->due_time)->format('h:i A') : 'Any time' }}</p>
                                </td>
                                <td class="py-3 text-slate-600 dark:text-slate-300">
                                    <p>{{ ucfirst($task->repeat_type ?? 'none') }}</p>
                                    @if(($task->repeat_type ?? 'none') === 'custom')
                                        <p class="text-xs text-slate-400">Every {{ $task->repeat_every }} day(s)</p>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="rounded-md px-2.5 py-1 text-xs {{ $task->status === 'completed' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $task->status === 'completed' ? 'Mark Pending' : 'Complete' }}</button>
                                        </form>

                                        <form method="POST" action="{{ route('tasks.duplicate', $task) }}">
                                            @csrf
                                            <button class="rounded-md bg-slate-100 px-2.5 py-1 text-xs text-slate-700 dark:bg-slate-800 dark:text-slate-300">Duplicate</button>
                                        </form>

                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?');" class="flex items-center gap-2">
                                            @csrf
                                            @method('DELETE')
                                            <select name="delete_scope" class="rounded-md border-slate-300 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                                <option value="single">Delete This</option>
                                                <option value="future">This + Future</option>
                                                <option value="all">Delete All Series</option>
                                            </select>
                                            <button class="rounded-md bg-red-100 px-2.5 py-1 text-xs text-red-700">Delete</button>
                                        </form>
                                    </div>

                                    <form method="POST" action="{{ route('tasks.update', $task) }}" class="mt-3 grid grid-cols-1 md:grid-cols-12 gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input name="title" value="{{ $task->title }}" class="md:col-span-3 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" required>
                                        <input name="category" value="{{ $task->category }}" class="md:col-span-2 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" placeholder="Category">
                                        <select name="priority" class="md:col-span-1 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                            <option value="low" @selected($task->priority === 'low')>Low</option>
                                            <option value="medium" @selected($task->priority === 'medium')>Medium</option>
                                            <option value="high" @selected($task->priority === 'high')>High</option>
                                        </select>
                                        <select name="status" class="md:col-span-2 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                            <option value="pending" @selected($task->status === 'pending')>Pending</option>
                                            <option value="completed" @selected($task->status === 'completed')>Completed</option>
                                        </select>
                                        <input type="date" name="due_date" value="{{ optional($task->due_date)->format('Y-m-d') }}" class="md:col-span-2 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                        <input type="time" name="due_time" value="{{ $task->due_time ? substr($task->due_time, 0, 5) : '' }}" class="md:col-span-2 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                        <select name="repeat_type" class="md:col-span-2 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                            <option value="none" @selected(($task->repeat_type ?? 'none') === 'none')>No Repeat</option>
                                            <option value="daily" @selected($task->repeat_type === 'daily')>Daily</option>
                                            <option value="weekly" @selected($task->repeat_type === 'weekly')>Weekly</option>
                                            <option value="monthly" @selected($task->repeat_type === 'monthly')>Monthly</option>
                                            <option value="custom" @selected($task->repeat_type === 'custom')>Custom</option>
                                        </select>
                                        <input type="number" min="1" max="365" name="repeat_every" value="{{ $task->repeat_every }}" class="md:col-span-2 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" placeholder="Every N">
                                        <input type="date" name="repeat_until" value="{{ optional($task->repeat_until)->format('Y-m-d') }}" class="md:col-span-2 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                        <textarea name="description" class="md:col-span-6 rounded-md border-slate-300 px-2 py-1 text-xs dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" placeholder="Notes">{{ $task->description }}</textarea>
                                        <button class="md:col-span-2 rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">Save</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-500">No tasks found for this view.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $tasks->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
