<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                    <br><br>
                    <a href="{{route('product.index')}}" class="text-red-600">Product page</a>
                    <br><br>
                    <a href="{{route('customer.index')}}" class="text-red-600">Customer page</a>
                    <br><br>
                    <a href="{{route('order.index')}}" class="text-red-600">Order page</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>