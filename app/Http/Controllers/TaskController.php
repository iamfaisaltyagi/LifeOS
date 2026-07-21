<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TaskController extends Controller
{
    private const REPEAT_TYPES = ['none', 'daily', 'weekly', 'monthly', 'custom'];

    public function index(Request $request): View
    {
        $this->rollOverRecurringTasks($request);

        $query = Task::where('user_id', $request->user()->id);

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['pending', 'completed'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority') && in_array($request->priority, ['low', 'medium', 'high'], true)) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tab = $request->get('tab', 'today');
        if (! in_array($tab, ['today', 'upcoming', 'overdue', 'completed', 'all'], true)) {
            $tab = 'today';
        }

        $today = now()->toDateString();
        if ($tab === 'today') {
            $query->where('status', 'pending')->whereDate('due_date', $today);
        } elseif ($tab === 'upcoming') {
            $query->where('status', 'pending')->whereDate('due_date', '>', $today);
        } elseif ($tab === 'overdue') {
            $query->where('status', 'pending')->whereDate('due_date', '<', $today);
        } elseif ($tab === 'completed') {
            $query->where('status', 'completed');
        }

        $sort = $request->get('sort', 'due_date');
        $direction = $request->get('direction', 'asc') === 'desc' ? 'desc' : 'asc';

        if ($sort === 'priority') {
            $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END {$direction}");
        } elseif (in_array($sort, ['title', 'created_at', 'due_date', 'due_time'], true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderByRaw('due_date is null');
            $query->orderBy('due_date');
            $query->orderBy('due_time');
        }

        $tasks = $query->paginate(10)->withQueryString();

        return view('tasks.index', [
            'tasks' => $tasks,
            'filters' => $request->only(['search', 'status', 'priority', 'category', 'sort', 'direction', 'tab']),
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('tasks.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
            'due_time' => ['nullable', 'date_format:H:i'],
            'repeat_type' => ['nullable', 'in:none,daily,weekly,monthly,custom'],
            'repeat_every' => ['nullable', 'integer', 'min:1', 'max:365', 'required_if:repeat_type,custom'],
            'repeat_until' => ['nullable', 'date', 'after_or_equal:due_date'],
        ]);

        $validated['repeat_type'] = $validated['repeat_type'] ?? 'none';
        if ($validated['repeat_type'] === 'none') {
            $validated['repeat_every'] = null;
            $validated['repeat_until'] = null;
        } else {
            $validated['recurrence_group'] = (string) Str::uuid();
        }

        $request->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('status', 'Task created successfully.');
    }

    public function show(string $id): RedirectResponse
    {
        return redirect()->route('tasks.index');
    }

    public function edit(string $id): RedirectResponse
    {
        return redirect()->route('tasks.index');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
            'due_time' => ['nullable', 'date_format:H:i'],
            'status' => ['nullable', 'in:pending,completed'],
            'repeat_type' => ['nullable', 'in:none,daily,weekly,monthly,custom'],
            'repeat_every' => ['nullable', 'integer', 'min:1', 'max:365', 'required_if:repeat_type,custom'],
            'repeat_until' => ['nullable', 'date', 'after_or_equal:due_date'],
        ]);

        $validated['repeat_type'] = $validated['repeat_type'] ?? 'none';
        if ($validated['repeat_type'] === 'none') {
            $validated['repeat_every'] = null;
            $validated['repeat_until'] = null;
            $validated['recurrence_group'] = null;
        } elseif (! $task->recurrence_group) {
            $validated['recurrence_group'] = (string) Str::uuid();
        }

        if (($validated['status'] ?? null) === 'completed') {
            $validated['completed_at'] = now();
        }

        if (($validated['status'] ?? null) === 'pending') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return redirect()->route('tasks.index')->with('status', 'Task updated successfully.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'delete_scope' => ['nullable', 'in:single,future,all'],
        ]);
        $deleteScope = $validated['delete_scope'] ?? 'single';

        if ($deleteScope === 'all' && $task->recurrence_group) {
            $request->user()->tasks()->where('recurrence_group', $task->recurrence_group)->delete();

            return redirect()->route('tasks.index')->with('status', 'Task series deleted successfully.');
        }

        if ($deleteScope === 'future' && $task->recurrence_group && $task->due_date) {
            $request->user()->tasks()
                ->where('recurrence_group', $task->recurrence_group)
                ->whereDate('due_date', '>=', $task->due_date)
                ->delete();

            return redirect()->route('tasks.index')->with('status', 'This and future tasks deleted successfully.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('status', 'Task deleted successfully.');
    }

    public function toggle(Request $request, string $id): RedirectResponse
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $isCompleted = $task->status === 'completed';
        $task->update([
            'status' => $isCompleted ? 'pending' : 'completed',
            'completed_at' => $isCompleted ? null : now(),
        ]);

        if (! $isCompleted && $task->repeat_type !== 'none') {
            $this->createNextRecurringTask($request, $task);
        }

        return redirect()->route('tasks.index')->with('status', 'Task status updated.');
    }

    public function duplicate(Request $request, string $id): RedirectResponse
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $copy = $task->replicate([
            'status',
            'completed_at',
            'created_at',
            'updated_at',
        ]);
        $copy->title = $task->title.' (Copy)';
        $copy->status = 'pending';
        $copy->completed_at = null;
        $copy->save();

        return redirect()->route('tasks.index')->with('status', 'Task duplicated successfully.');
    }

    private function rollOverRecurringTasks(Request $request): void
    {
        $tasks = $request->user()->tasks()
            ->where('status', 'pending')
            ->whereNotNull('due_date')
            ->whereIn('repeat_type', ['daily', 'weekly', 'monthly', 'custom'])
            ->whereDate('due_date', '<', now()->toDateString())
            ->get();

        foreach ($tasks as $task) {
            $nextDate = Carbon::parse($task->due_date);
            $guard = 0;

            while ($nextDate->lt(now()->startOfDay()) && $guard < 400) {
                $nextDate = $this->calculateNextDueDate($nextDate, $task);
                $guard++;

                if ($task->repeat_until && $nextDate->gt(Carbon::parse($task->repeat_until))) {
                    break;
                }
            }

            if ($task->repeat_until && $nextDate->gt(Carbon::parse($task->repeat_until))) {
                continue;
            }

            if ($nextDate->toDateString() !== Carbon::parse($task->due_date)->toDateString()) {
                $task->update(['due_date' => $nextDate->toDateString()]);
            }
        }
    }

    private function createNextRecurringTask(Request $request, Task $task): void
    {
        if (! $task->due_date) {
            return;
        }

        $nextDate = $this->calculateNextDueDate(Carbon::parse($task->due_date), $task);
        if ($task->repeat_until && $nextDate->gt(Carbon::parse($task->repeat_until))) {
            return;
        }

        $request->user()->tasks()->create([
            'title' => $task->title,
            'description' => $task->description,
            'category' => $task->category,
            'priority' => $task->priority,
            'status' => 'pending',
            'due_date' => $nextDate->toDateString(),
            'due_time' => $task->due_time,
            'repeat_type' => $task->repeat_type,
            'repeat_every' => $task->repeat_every,
            'repeat_until' => $task->repeat_until,
            'recurrence_group' => $task->recurrence_group ?: (string) Str::uuid(),
        ]);
    }

    private function calculateNextDueDate(Carbon $baseDate, Task $task): Carbon
    {
        return match ($task->repeat_type) {
            'daily' => $baseDate->copy()->addDay(),
            'weekly' => $baseDate->copy()->addWeek(),
            'monthly' => $baseDate->copy()->addMonth(),
            'custom' => $baseDate->copy()->addDays((int) ($task->repeat_every ?: 1)),
            default => $baseDate->copy(),
        };
    }
}
