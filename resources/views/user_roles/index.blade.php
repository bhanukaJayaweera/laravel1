@can('handle users')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage User Roles & Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                <table class="table-auto w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">User</th>
                            <th class="px-4 py-2 border">Roles</th>
                            <th class="px-4 py-2 border">Permissions</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border">
                                <form method="POST" action="{{ route('user-role.update', $user) }}">
                                    @csrf
                                    <td class="px-4 py-2 border">{{ $user->name }}</td>
                                    <td class="px-4 py-2 border">
                                        @foreach($roles as $role)
                                            <label class="block">
                                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                    {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                {{ $role->name }}
                                            </label>
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-2 border" readonly>
                                        @foreach($permissions as $permission)
                                            <label class="block">
                                                <input type="hidden" name="permissions[]" value="{{ $permission->name }}"
                                                {{ $user->hasPermissionTo($permission->name) ? '' : 'disabled' }}>
                                                <input type="checkbox" disabled name="permissions[]" value="{{ $permission->name }}"
                                                    {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                {{ $permission->name }}
                                            </label>
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
@endcan
