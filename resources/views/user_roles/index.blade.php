@can('handle users')
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage User Roles') }}
        </h2>
    </x-slot>
    <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome CDN (Add to <head> section) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>

    </style>
    </head>
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
                            <!-- <th class="px-4 py-2 border">Permissions</th> -->
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
                                    <!-- <td class="px-4 py-2 border" readonly>
                                        @foreach($permissions as $permission)
                                            <label class="block">
                                                <input type="hidden" name="permissions[]" value="{{ $permission->name }}"
                                                {{ $user->hasPermissionTo($permission->name) ? '' : 'disabled' }}>
                                                <input type="checkbox" disabled name="permissions[]" value="{{ $permission->name }}"
                                                    {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                {{ $permission->name }}
                                            </label>
                                        @endforeach
                                    </td> -->
                                    <td class="px-4 py-2 border">
                                        <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <a class="btn btn-danger" href="{{route('dashboard')}}"><i class="fa fa-home"></i> Back</a>

            </div>
        </div>
    </div>
</x-app-layout>
@endcan
