<x-app-layout>
    <x-slot name="header">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome CDN (Add to <head> section) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <h2 class="text-4xl font-bold text-orange-600 text-center leading-snug" style="color: #f97316;">
                {{ __('Main Menu') }}
        </h2>
    </x-slot>
    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1> -->
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- User Management Section -->
                    @can('handle users')
                    <div class="bg-blue-50 p-5 rounded-lg border border-blue-100">
                        <h2 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-users mr-2"></i> User Management
                        </h2>
                        <div class="space-y-3">
                            <a class="btn btn-primary w-full text-left py-3 flex items-center" href="{{route('users.index')}}">
                                <i class="fa fa-user mr-2"></i> Manage Users
                            </a><br><br>
                            <a class="btn btn-primary w-full text-left py-3 flex items-center" href="{{route('user-role.index')}}">
                                <i class="fas fa-user-tag mr-2"></i> Assign Roles
                            </a><br><br>
                            <a class="btn btn-success w-full text-left py-3 flex items-center" href="{{route('roles.permissions.index')}}">
                                <i class="fas fa-key mr-2"></i> Manage Permissions
                            </a>
                        </div>
                    </div>
                    @endcan

                    <!-- Product Management Section -->
                    @can('handle products')
                    <div class="bg-green-50 p-5 rounded-lg border border-green-100">
                        <h2 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                            <i class="fas fa-boxes mr-2"></i> Product Management
                        </h2>
                        <a class="btn btn-success w-full text-left py-3 flex items-center" href="{{route('product.index')}}">
                            <i class="fa fa-box mr-2"></i> Manage Products
                        </a><br><br>
                          <a class="btn btn-success w-full text-left py-3 flex items-center" href="{{route('fruit.prices')}}">
                            <i class="fa fa-box mr-2"></i> View Market Prices
                        </a><br><br>
                         <a class="btn btn-success w-full text-left py-3 flex items-center" href="{{route('fetchpricesapi')}}">
                            <i class="fa fa-box mr-2"></i> Get Market Prices - API
                        </a><br><br>
                        <a class="btn btn-success w-full text-left py-3 flex items-center" href="{{route('market.upload')}}">
                            <i class="fa fa-box mr-2"></i> Get Market Prices - Excel
                        </a><br><br>
                        
                        <a class="btn btn-success w-full text-left py-3 flex items-center" href="{{route('fruit.form')}}">
                            <i class="fa fa-box mr-2"></i> Fruit Image Recognizer
                        </a><br><br>
                        <!-- <div class="container">
                            <h1>Fruit Classifier</h1>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe 
                                    src="http://172.16.44.212:8501" 
                                    class="embed-responsive-item"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div> -->
                    </div>
                    @endcan

                    <!-- Customer Management Section -->
                    @can('handle customers')
                    <div class="bg-red-50 p-5 rounded-lg border border-red-100">
                        <h2 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                            <i class="fas fa-user-friends mr-2"></i> Customer Management
                        </h2>
                        <a class="btn btn-danger w-full text-left py-3 flex items-center" href="{{route('customer.index')}}">
                            <i class="fa fa-user mr-2"></i> Manage Customers
                        </a><br><br>
                        <a class="btn btn-danger w-full text-left py-3 flex items-center" href="{{route('fetch.question')}}">
                            <i class="fa fa-user mr-2"></i> Fetch Q and A 
                        </a><br><br>
                            <a class="btn btn-danger w-full text-left py-3 flex items-center" href="{{route('show.question')}}">
                            <i class="fa fa-user mr-2"></i> View Q and A 
                        </a><br><br>
                    </div>
                    @endcan

                    <!-- Order Management Section -->
                  @can('handle orders')
                    <div class="bg-purple-50 p-5 rounded-lg border border-purple-100">
                        <h2 class="text-lg font-semibold text-purple-800 mb-4 flex items-center">
                            <i class="fas fa-shopping-cart mr-2"></i> Order Management
                        </h2>
                        <div class="space-y-3">
                            
                            <a class="btn btn-success w-full text-left py-3 flex items-center" href="{{route('order.index')}}">
                                <i class="fas fa-clipboard-list mr-2"></i> Manage Orders
                            </a> <br><br>
                            @can('approve orders')
                            <a class="btn btn-primary w-full text-left py-3 flex items-center" href="{{route('order.approvals')}}">
                                <i class="fas fa-check-circle mr-2"></i> Approve Orders
                            </a>
                            @endcan
                        </div>
                    </div>
                   @endcan

                    <!-- Promotion Management Section -->
                    @can('handle promotions')
                    <div class="bg-yellow-50 p-5 rounded-lg border border-yellow-100">
                        <h2 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                            <i class="fas fa-percentage mr-2"></i> Promotion Management
                        </h2>
                        <div class="space-y-3">
                            
                            <a class="btn btn-success w-full text-left py-3 flex items-center" href="{{route('Promotion.index')}}">
                                <i class="fas fa-money-bill-wave mr-2"></i> Manage Promotions
                            </a> 
                            <br><br>
                            @can('approve promotions')
                            <a class="btn btn-primary w-full text-left py-3 flex items-center" href="{{route('Promotion.approvals')}}">
                                <i class="fas fa-check-double mr-2"></i> Approve Promotions
                            </a><br><br>
                            @endcan
                        </div>
                    </div>
                   @endcan
                </div>

                <!-- Quick Stats Section (Optional) -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Quick Stats</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- You can add dynamic stats here -->
                        <div class="bg-blue-100 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['new_orders'] ?? 0 }}</div>
                            <div class="text-sm text-blue-800">New Orders </div>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['pending_approvals'] ?? 0 }}</div>
                            <div class="text-sm text-green-800">Pending Approvals</div>
                        </div>
                        <div class="bg-purple-100 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $stats['new_customers'] ?? 0 }}</div>
                            <div class="text-sm text-purple-800">New Customers</div>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ $stats['active_promotions'] ?? 0 }}</div>
                            <div class="text-sm text-yellow-800">Active Promotions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- <h2 class="font-bold">You're logged in!</h2>
                    <br><br> -->
                    <!-- @can('handle users')
           -->
                    
                    <!-- <a class="btn btn-primary" href="{{route('users.index')}}" class="text-red-600"><i class="fa fa-user"></i> Manage User</a>            
                    <br><br>  
                    <a class="btn btn-primary" href="{{route('user-role.index')}}" class="text-red-600"><i class="fa fa-shopping-cart"></i>  Assign Roles to Users</a>            
                    <br><br>             
                    <a class="btn btn-success" href="{{route( 'roles.permissions.index')}}" class="text-red-600"><i class="fa fa-shopping-cart"></i>  Assign Permissions to Roles</a>   
                    @endcan
                    <br><br>
                    @can('handle products')
                    <a class="btn btn-success" href="{{route('product.index')}}" class="text-red-600"><i class="fa fa-box"></i>  Manage Products</a>  
                    @endcan                
                    <br><br>
                    @can('handle customers')
                    <a class="btn btn-danger" href="{{route('customer.index')}}"><i class="fa fa-user"></i>  Manage Customers</a>
                    @endcan
                    <br><br>
                    @can('handle orders')
                    <a class="btn btn-success" href="{{route('order.index')}}" class="text-red-600"><i class="fa fa-shopping-cart"></i>  Manage Orders</a>
                    @endcan
                    <br><br> -->
                    <!-- @can('approve orders')
                    <a class="btn btn-primary" href="{{route('order.approvals')}}" class="text-red-600"> <i class="fas fa-check"></i>  Approve Orders</a>
                    @endcan
                    <br><br>
                    @can('handle promotions')
                    <a class="btn btn-success" href="{{route('Promotion.index')}}" class="text-red-600"> <i class="fas fa-money-bill-wave"></i>  Manage Promotions</a>
                    @endcan
                    <br><br>
                    @can('approve promotions')
                    <a class="btn btn-primary" href="{{route('Promotion.approvals')}}" class="text-red-600"> <i class="fas fa-check"></i>  Approve Promotions</a>
                    @endcan
                </div>
            </div>
        </div>
    </div> --> -->
</x-app-layout>