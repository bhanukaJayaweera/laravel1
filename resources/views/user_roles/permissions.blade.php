@can('handle users')
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-user-shield mr-2"></i>{{ __('Role Permissions Management') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </x-slot>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <style>
            .permission-card {
                transition: all 0.2s ease;
                border-left: 4px solid transparent;
            }
            .permission-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                border-left-color: #3b82f6;
            }
            .permission-checkbox:checked + .permission-label {
                font-weight: 600;
                color: #1d4ed8;
            }
            .role-section {
                transition: all 0.3s ease;
            }
            .role-section.collapsed {
                background-color: #f9fafb;
            }
            .search-highlight {
                background-color: #fffb8f;
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" 
                 class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-green-500">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button @click="show = false" class="text-green-500 hover:text-green-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 text-red-500">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-700">There were {{ $errors->count() }} errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-600">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <!-- Search and Filter Section -->
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="w-full md:w-auto">
                    <div class="relative">
                        <input type="text" id="permissionSearch" placeholder="Search permissions..." 
                               class="pl-10 pr-4 py-2 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Filter by module:</span>
                    <select id="moduleFilter" class="border rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Modules</option>
                        @php
                            $modules = $permissions->pluck('name')->map(function($name) {
                                return explode('.', $name)[0] ?? 'other';
                            })->unique()->sort();
                        @endphp
                        @foreach($modules as $module)
                            <option value="{{ $module }}">{{ ucfirst($module) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Roles Accordion -->
            <div class="space-y-4">
                @foreach ($roles as $role)
                    <div x-data="{ expanded: false }" class="role-section border rounded-lg overflow-hidden">
                        <button @click="expanded = !expanded" 
                                class="w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 focus:outline-none"
                                :class="{ 'collapsed': !expanded }">
                            <div class="flex items-center">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $role->name }}</h3>
                                <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    {{ $role->permissions->count() }} permissions
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span x-show="!expanded" class="text-sm text-gray-500 mr-2">Click to expand</span>
                                <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'transform rotate-180': expanded }"></i>
                            </div>
                        </button>

                        <div x-show="expanded" x-collapse class="p-4">
                            <form method="POST" action="{{ route('roles.permissions.update', $role) }}" 
                                  class="permission-form" data-role="{{ $role->name }}">
                                @csrf
                                
                                <!-- Permission Groups -->
                                @php
                                    $groupedPermissions = $permissions->groupBy(function($permission) {
                                        return explode('.', $permission->name)[0] ?? 'other';
                                    });
                                @endphp
                                
                                <div class="space-y-6">
                                    @foreach($groupedPermissions as $module => $modulePermissions)
                                        <div class="permission-module" data-module="{{ $module }}">
                                            <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-folder-open text-blue-500 mr-2"></i>
                                                {{ ucfirst($module) }} Permissions
                                                <span class="ml-2 text-xs text-gray-500">({{ $modulePermissions->count() }})</span>
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach($modulePermissions as $permission)
                                                    <label class="permission-card flex items-start p-3 border rounded-lg cursor-pointer">
                                                        <input type="checkbox" name="permissions[]" 
                                                               value="{{ $permission->name }}"
                                                               class="permission-checkbox h-4 w-4 mt-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                               {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                        <div class="ml-2">
                                                            <span class="permission-label block text-sm font-medium text-gray-700">
                                                                {{ $permission->name }}
                                                            </span>
                                                            <span class="block text-xs text-gray-500 mt-1">
                                                                Last updated: {{ $permission->updated_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" @click="document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false)" 
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-times mr-2"></i> Clear All
                                    </button>
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-black bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-save mr-2"></i> Update Permissions
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Search functionality
                const permissionSearch = document.getElementById('permissionSearch');
                permissionSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const permissionLabels = document.querySelectorAll('.permission-label');
                    
                    permissionLabels.forEach(label => {
                        const text = label.textContent.toLowerCase();
                        const card = label.closest('.permission-card');
                        const module = label.closest('.permission-module');
                        
                        if (text.includes(searchTerm)) {
                            card.style.display = 'flex';
                            module.style.display = 'block';
                            // Highlight matching text
                            const regex = new RegExp(searchTerm, 'gi');
                            label.innerHTML = label.textContent.replace(regex, 
                                match => `<span class="search-highlight">${match}</span>`);
                        } else {
                            card.style.display = 'none';
                            // Check if any permissions in module are visible
                            const visibleInModule = module.querySelector('.permission-card[style="display: flex;"]');
                            if (!visibleInModule) {
                                module.style.display = 'none';
                            }
                        }
                    });
                });

                // Module filter
                const moduleFilter = document.getElementById('moduleFilter');
                moduleFilter.addEventListener('change', function() {
                    const selectedModule = this.value;
                    const modules = document.querySelectorAll('.permission-module');
                    
                    modules.forEach(module => {
                        if (!selectedModule || module.dataset.module === selectedModule) {
                            module.style.display = 'block';
                            module.querySelectorAll('.permission-card').forEach(card => {
                                card.style.display = 'flex';
                            });
                        } else {
                            module.style.display = 'none';
                        }
                    });
                });

                // Form submission handling
                document.querySelectorAll('.permission-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const submitButton = this.querySelector('button[type="submit"]');
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
@endcan