@can('handle users')
<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        .user-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 0.35em 0.65em;
        }
        .action-btn {
            min-width: 80px;
        }
        .card-header {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(249, 115, 22, 0.05);
        }
        .search-box {
            transition: all 0.3s ease;
        }
        .search-box:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25);
        }
    </style>
</head>
<body>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-orange-600">
                <i class="fas fa-users-cog mr-2"></i>{{ __('User Management') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Dashboard
            </a>
        </div>
    </x-slot>

    <div class="container py-6">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Create New User
                    </a>
                    
                    <div class="w-50">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="userSearch" class="form-control search-box" placeholder="Search users...">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-users me-2"></i>User List</span>
                        <span class="badge bg-orange-500">{{ $users->count() }} users</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Access</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr data-search="{{ strtolower($user->name.' '.$user->email) }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($user->profile_photo_path)
                                                        <img src="{{ asset('storage/'.$user->profile_photo_path) }}" 
                                                             alt="{{ $user->name }}" class="user-avatar me-3">
                                                    @else
                                                        <div class="user-avatar bg-light text-secondary d-flex align-items-center justify-content-center me-3">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold">{{ $user->name }}</div>
                                                        <small class="text-muted">ID: {{ $user->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge status-badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                                    <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }} me-1"></i>
                                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge status-badge bg-{{ $user->is_enabled ? 'success' : 'danger' }}">
                                                    <i class="fas fa-{{ $user->is_enabled ? 'lock-open' : 'lock' }} me-1"></i>
                                                    {{ $user->is_enabled ? 'Enabled' : 'Disabled' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('users.edit', $user) }}" 
                                                       class="btn btn-sm btn-primary action-btn"
                                                       data-bs-toggle="tooltip" title="Edit User">
                                                        <i class="fas fa-edit me-1"></i>Edit
                                                    </a>
                                                    <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" 
                                                                class="btn btn-sm action-btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                                                data-bs-toggle="tooltip" 
                                                                title="{{ $user->is_active ? 'Disable User' : 'Enable User' }}">
                                                            <i class="fas fa-{{ $user->is_active ? 'toggle-off' : 'toggle-on' }} me-1"></i>
                                                            {{ $user->is_active ? 'Disable' : 'Enable' }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($users->hasPages())
                        <div class="card-footer bg-white">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable({
                paging: false,
                searching: false,
                info: false,
                responsive: true
            });

            // Custom search functionality
            $('#userSearch').on('keyup', function() {
                const searchText = $(this).val().toLowerCase();
                $('tbody tr').each(function() {
                    const rowText = $(this).data('search');
                    $(this).toggle(rowText.includes(searchText));
                });
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</x-app-layout>
</html>
@endcan