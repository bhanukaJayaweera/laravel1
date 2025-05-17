@can('handle users')
<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- DataTables CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
       <!-- Font Awesome CDN (Add to <head> section) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script src="{{ asset('js/new.js') }}"></script>
    <title>Document</title>
    <style>

    </style>
</head>
<body>
<x-slot name="header">
    <h2 class="text-4xl font-bold text-orange-600 text-center leading-snug" style="color: #f97316;">
        {{ __('User List') }}
    </h2>
</x-slot> 
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <a href="{{ route('users.create') }}" class="btn btn-primary">Create New User</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Users') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Active</th>
                                <th>Enable</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                     <td>
                                        <span class="badge bg-{{ $user->is_enabled ? 'success' : 'danger' }}">
                                            {{ $user->is_enabled ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-{{ $user->is_active ? 'warning' : 'success' }}">
                                                {{ $user->is_active ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</x-app-layout>
</html>
@endcan