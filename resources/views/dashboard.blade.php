<x-app-layout>
    <x-slot name="header">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome CDN (Add to <head> section) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-bold">You're logged in!</h2>
                    <br><br>
                    <a class="btn btn-success" href="{{route('product.index')}}" class="text-red-600"><i class="fa fa-box"></i> Product page</a>
                    <br><br>
                    <a class="btn btn-danger" href="{{route('customer.index')}}"><i class="fa fa-user"></i> Customer page</a>
                    <br><br>
                    @role('admin')
                    <a class="btn btn-primary" href="{{route('order.index')}}" class="text-red-600"><i class="fa fa-shopping-cart"></i> Order page</a>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>