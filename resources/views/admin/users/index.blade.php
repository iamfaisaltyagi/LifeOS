<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Users</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by name or email" class="border rounded-md px-3 py-2 md:col-span-2">
                    <select name="role" class="border rounded-md px-3 py-2">
                        <option value="">All Roles</option>
                        <option value="user" @selected(($filters['role'] ?? '') === 'user')>User</option>
                        <option value="admin" @selected(($filters['role'] ?? '') === 'admin')>Admin</option>
                    </select>
                    <select name="status" class="border rounded-md px-3 py-2">
                        <option value="">All Status</option>
                        <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                        <option value="suspended" @selected(($filters['status'] ?? '') === 'suspended')>Suspended</option>
                    </select>
                    <button class="bg-gray-900 text-white rounded-md px-4 py-2">Apply</button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">User</th>
                        <th class="py-2">Role</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr class="border-b align-top">
                            <td class="py-3">
                                <p class="font-medium">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </td>
                            <td class="py-3">{{ ucfirst($user->role) }}</td>
                            <td class="py-3">{{ $user->is_suspended ? 'Suspended' : 'Active' }}</td>
                            <td class="py-3">
                                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex flex-wrap gap-2 items-center">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="border rounded-md px-2 py-1 text-xs">
                                        <option value="user" @selected($user->role === 'user')>User</option>
                                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                    </select>
                                    <label class="inline-flex items-center gap-1 text-xs">
                                        <input type="checkbox" name="is_suspended" value="1" @checked($user->is_suspended)>
                                        Suspended
                                    </label>
                                    <button class="px-2 py-1 rounded-md text-xs bg-blue-100 text-blue-700">Save</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
