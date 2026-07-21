<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkoutController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        $workouts = Workout::where('user_id', $request->user()->id)
            ->whereDate('date', $date)
            ->orderBy('exercise_name')
            ->paginate(12)
            ->withQueryString();

        $summary = Workout::where('user_id', $request->user()->id)
            ->whereDate('date', $date)
            ->selectRaw('COUNT(*) as total_items')
            ->selectRaw('SUM(CASE WHEN is_completed = 1 THEN 1 ELSE 0 END) as completed_items')
            ->first();

        $totalItems = (int) ($summary->total_items ?? 0);
        $completedItems = (int) ($summary->completed_items ?? 0);
        $completionPercentage = $totalItems > 0 ? (int) round(($completedItems / $totalItems) * 100) : 0;

        return view('workouts.index', [
            'workouts' => $workouts,
            'date' => $date,
            'completionPercentage' => $completionPercentage,
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('workouts.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'exercise_name' => ['required', 'string', 'max:255'],
            'sets' => ['nullable', 'integer', 'min:1'],
            'repetitions' => ['nullable', 'integer', 'min:1'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'workout_type' => ['nullable', 'string', 'max:100'],
            'date' => ['required', 'date'],
        ]);

        $request->user()->workouts()->create($validated);

        return redirect()->route('workouts.index', ['date' => $validated['date']])->with('status', 'Workout created.');
    }

    public function show(string $id): RedirectResponse
    {
        return redirect()->route('workouts.index');
    }

    public function edit(string $id): RedirectResponse
    {
        return redirect()->route('workouts.index');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $workout = $request->user()->workouts()->findOrFail($id);

        $validated = $request->validate([
            'exercise_name' => ['required', 'string', 'max:255'],
            'sets' => ['nullable', 'integer', 'min:1'],
            'repetitions' => ['nullable', 'integer', 'min:1'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'workout_type' => ['nullable', 'string', 'max:100'],
            'date' => ['required', 'date'],
        ]);

        $workout->update($validated);

        return redirect()->route('workouts.index', ['date' => $validated['date']])->with('status', 'Workout updated.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $workout = $request->user()->workouts()->findOrFail($id);
        $date = optional($workout->date)->toDateString();
        $workout->delete();

        return redirect()->route('workouts.index', ['date' => $date])->with('status', 'Workout deleted.');
    }

    public function toggle(Request $request, string $id): RedirectResponse
    {
        $workout = $request->user()->workouts()->findOrFail($id);

        $workout->update([
            'is_completed' => !$workout->is_completed,
            'completed_at' => $workout->is_completed ? null : now(),
        ]);

        return redirect()->route('workouts.index', ['date' => optional($workout->date)->toDateString()])->with('status', 'Workout status updated.');
    }
}
