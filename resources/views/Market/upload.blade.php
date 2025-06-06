
<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>

    <!-- <x-slot name="header">
    </x-slot> -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- DataTables CSS -->
     <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
       <!-- Font Awesome CDN (Add to <head> section) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <script src="{{ asset('js/new.js') }}"></script>
    <title>Upload Excel</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; }
        .back {
        margin-left:50%;
        width: 80%;}
        form{
            padding: 15px;
        }
    </style>
</head>
<body>
<x-slot name="header">
    <h2 class="text-4xl font-bold text-orange-600 text-center leading-snug" style="color:rgb(25, 75, 223);">
        {{ __('Upload Excel File') }}
    </h2>
</x-slot> 

<body>
    <div class="container">
    <div class="row col-md-6">
    
    <div class = "container">
            <!-- Sidebar -->
        <div class="w3-sidebar w3-light-grey w3-bar-block" style="width:15%">
        <h3 class="w3-bar-item">Menu</h3>
        <!-- <button type="button" class="btn btn-primary createOrder" data-bs-toggle="modal" data-bs-target="#orderModal">New Order <i class="fa fa-plus"></i></button><br/> <br> -->
        <!-- <button type="button" class="btn btn-primary createOrderProduct" data-bs-toggle="modal" data-bs-target="#orderproductModal"><i class="fa fa-plus"></i> Order </button>      -->
        <br><a class="btn btn-success" href="{{route('dashboard')}}"><i class="fa fa-home"></i> Home</a>
        <!-- <br><br><a class="btn btn-success" href="{{route('order.upload')}}"><i class="fa fa-plus"></i> Upload Excel</a> -->
        </div>
    </div>
    @if(session('success'))
        <!-- <p style="color: green;">{{ session('success') }}</p> -->
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="margin-left:55%">
            <strong>{{session('success')}}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>         
        </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-left:55%">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <div class="back">   
    <form action="{{ route('fetchpricesexcel') }}" method="POST" enctype="multipart/form-data" style="margin-left:40%">
        @csrf
         <input type="file" name="file" class="@error('file') is-invalid @enderror" required><br><br>
         @error('file')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
       <button type="submit" class="btn btn-success" id="uploadButton">
            <span id="uploadText">Upload</span>
            <span id="uploadSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
        </button>
    </form>
    <a type="button" href="{{route('dashboard')}}" class="btn btn-danger">Back</a>
    </div>
    <div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $('form').on('submit', function() {
    $('#uploadText').text('Uploading...');
    $('#uploadSpinner').removeClass('d-none');
    $('#uploadButton').prop('disabled', true);
});
</script>
</body>
</x-app-layout>
</html>
