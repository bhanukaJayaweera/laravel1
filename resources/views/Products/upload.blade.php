@include('Products.frame')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; }
        .back {padding: 20px;}
        form{
            padding: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="row col-md-6" style="margin-left:25%">
    <h2>Upload Excel File</h2>
    
    @if(session('success'))
        <!-- <p style="color: green;">{{ session('success') }}</p> -->
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{session('success')}}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>         
        </div>
    @endif

    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit" class="btn btn-success">Upload</button>
    </form>
    <div class="back">      
            <a type="button" href="{{route('product.index')}}" class="btn btn-danger">Back</a>
    </div>
    <div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
