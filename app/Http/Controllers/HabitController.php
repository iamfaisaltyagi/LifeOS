<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitCompletion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HabitController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $date = Carbon::parse($request->get('date', Carbon::today()->toDateString()));
        $weekStart = $date->copy()->startOfWeek();
        $weekEnd = $date->copy()->endOfWeek();

        $habits = Habit::where('user_id', $user->id)
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        $habitIds = $habits->pluck('id');

        $todayCompletions = HabitCompletion::where('user_id', $user->id)
            ->whereIn('habit_id', $habitIds)
            ->whereDate('date', $date)
            ->pluck('is_completed', 'habit_id');

        $weeklyCompletions = HabitCompletion::where('user_id', $user->id)
            ->whereIn('habit_id', $habitIds)
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->where('is_completed', true)
            ->get()
            ->groupBy('habit_id')
            ->map(fn ($items) => $items->count());

        $habitData = $habits->map(function (Habit $habit) use ($todayCompletions, $weeklyCompletions) {
            return [
                'habit' => $habit,
                'today_completed' => (bool) ($todayCompletions[$habit->id] ?? false),
                'weekly_completed_days' => (int) ($weeklyCompletions[$habit->id] ?? 0),
                'streak' => $this->calculateStreak($habit),
            ];
        });

        $activeHabitsCount = $habits->where('is_active', true)->count();
        $todayCompletedCount = $habitData->filter(fn ($item) => $item['today_completed'])->count();
        $dailyCompletion = $activeHabitsCount > 0 ? (int) round(($todayCompletedCount / $activeHabitsCount) * 100) : 0;

        return view('habits.index', [
            'habitData' => $habitData,
            'selectedDate' => $date->toDateString(),
            'dailyCompletion' => $dailyCompletion,
        ]);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('habits.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $request->user()->habits()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->route('habits.index')->with('status', 'Habit created.');
    }

    public function show(string $id): RedirectResponse
    {
        return redirect()->route('habits.index');
    }

    public function edit(string $id): RedirectResponse
    {
        return redirect()->route('habits.index');
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $habit = $request->user()->habits()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $habit->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('habits.index')->with('status', 'Habit updated.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $habit = $request->user()->habits()->findOrFail($id);
        $habit->delete();

        return redirect()->route('habits.index')->with('status', 'Habit deleted.');
    }

    public function toggleCompletion(Request $request, string $id): RedirectResponse
    {
        $habit = $request->user()->habits()->findOrFail($id);
        $date = Carbon::parse($request->get('date', Carbon::today()->toDateString()))->toDateString();

        $completion = HabitCompletion::firstOrCreate([
            'user_id' => $request->user()->id,
            'habit_id' => $habit->id,
            'date' => $date,
        ], [
            'is_completed' => false,
        ]);

        $completion->update([
            'is_completed' => !$completion->is_completed,
        ]);

        return redirect()->route('habits.index', ['date' => $date])->with('status', 'Habit completion updated.');
    }

    private function calculateStreak(Habit $habit): int
    {
        $dates = $habit->completions()
            ->where('is_completed', true)
            ->orderByDesc('date')
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $cursor = Carbon::today();

        while ($dates->contains($cursor->toDateString())) {
            $streak++;
            $cursor->subDay();
        }

        return $streak;
    }
}
