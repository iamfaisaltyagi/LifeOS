<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && in_array($request->role, ['user', 'admin'], true)) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'suspended'], true)) {
            $query->where('is_suspended', $request->status === 'suspended');
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'status']),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:user,admin'],
            'is_suspended' => ['nullable', 'boolean'],
        ]);

        if ($request->user()->id === $user->id && ($validated['role'] ?? 'user') !== 'admin') {
            return back()->withErrors(['role' => 'You cannot remove your own admin role.']);
        }

        $user->update([
            'role' => $validated['role'],
            'is_suspended' => (bool) ($validated['is_suspended'] ?? false),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }
}
