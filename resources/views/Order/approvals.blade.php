@can('approve orders')
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Order Deletion Approvals
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
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            <table class="table-auto w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">Order ID</th>
                            <th class="px-4 py-2 border">Requested By</th>
                            <th class="px-4 py-2 border"></th>
                            <th class="px-4 py-2 border"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $req)
                    <tr class="border">
                        <td class="px-4 py-2 border"> {{ $req->order->id }}</td>
                        <td class="px-4 py-2 border">{{ $req->user->name }}</td>
                        <td class="px-4 py-2 border">
                            <form method="POST" action="{{ route('order.approve', $req->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-success">Approve</button>
                            </form>
                        </td>
                        <td class="px-4 py-2 border">
                            <form method="POST" action="{{ route('order.reject', $req->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-danger">Reject</button>
                            </form>
                        </td>
</tr>
                    @endforeach
                    </tbody>

            </div>
        </div>
    </div>
</x-app-layout>
@endcan
