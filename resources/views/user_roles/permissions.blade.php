@can('handle users')
<x-app-layout>
<head>

<!-- <x-slot name="header">
</x-slot> -->
<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
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
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Permissions to Roles') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded shadow">
            @foreach ($roles as $role)
                <form method="POST" action="{{ route('roles.permissions.update', $role) }}" class="mb-6 border p-4 rounded">
                    @csrf
                    <h3 class="text-lg font-semibold mb-2">{{ $role->name }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success" class="mt-4 bg-blue-600 text-black px-4 py-2 rounded">Update</button>
                </form>
            @endforeach
            <a class="btn btn-danger" href="{{route('dashboard')}}"><i class="fa fa-home"></i> Back</a>
        </div>
    </div>
</x-app-layout>
@endcan
