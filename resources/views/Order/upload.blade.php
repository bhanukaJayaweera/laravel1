<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Excel File</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .upload-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: white;
        }
        .upload-header {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .file-upload {
            border: 2px dashed #3498db;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
        }
        .file-upload:hover {
            border-color: #2980b9;
            background: #f8f9fa;
        }
        .file-input {
            display: none;
        }
        .file-label {
            cursor: pointer;
            display: block;
        }
        .file-icon {
            font-size: 3rem;
            color: #3498db;
            margin-bottom: 1rem;
        }
        .file-name {
            margin-top: 1rem;
            font-weight: bold;
            color: #2c3e50;
        }
        .btn-upload {
            background: #3498db;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        .btn-upload:hover {
            background: #2980b9;
        }
        .requirements {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1.5rem;
        }
    </style>
</head>

<body>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="upload-container">
            <h3 class="upload-header">
                <i class="fas fa-file-excel me-2"></i> Upload Your Orders Excel File
            </h3>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>{{ session('success') }}</strong>
                    @if(session('imported_rows'))
                        <div class="mt-2">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ session('imported_rows') }} records imported from 
                            <strong>{{ session('file_name') }}</strong>
                        </div>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>There were issues with your upload:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Upload Form -->
            <form action="{{ route('importorders') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                
                <div class="file-upload">
                    <label for="file-upload-input" class="file-label">
                        <div class="file-icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <div class="mb-2">Click to browse or drag & drop your file</div>
                        <span class="btn btn-primary btn-upload">
                            <i class="fas fa-folder-open me-2"></i> Select File
                        </span>
                        <div id="file-name-display" class="file-name"></div>
                    </label>
                    <input id="file-upload-input" type="file" name="file" class="file-input" required>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('order.index') }}" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i> Upload File
                    </button>
                </div>
            </form>

            <!-- Requirements -->
            <div class="requirements">
                <h5><i class="fas fa-info-circle me-2"></i> File Requirements:</h5>
                <ul class="mb-0">
                    <li>Only Excel files (.xlsx, .xls) are accepted</li>
                    <li>Maximum file size: 5MB</li>
                    <li>Ensure your file follows the required format</li>
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#formatModal">View sample format</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sample Format Modal -->
    <div class="modal fade" id="formatModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Excel File Format Requirements</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Customer Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>101</td>
                                    <td>Sample Product</td>
                                    <td>2</td>
                                    <td>19.99</td>
                                    <td>customer@example.com</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1"><strong>Notes:</strong></p>
                        <ul>
                            <li>First row should contain headers exactly as shown</li>
                            <li>All columns are required</li>
                            <li>Do not modify column order</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="/sample-import-template.xlsx" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i> Download Template
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Display selected file name
        document.getElementById('file-upload-input').addEventListener('change', function(e) {
            const fileNameDisplay = document.getElementById('file-name-display');
            if (this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
            } else {
                fileNameDisplay.textContent = '';
            }
        });

        // Drag and drop functionality
        const fileUpload = document.querySelector('.file-upload');
        const fileInput = document.getElementById('file-upload-input');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUpload.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUpload.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUpload.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            fileUpload.classList.add('bg-light');
        }

        function unhighlight() {
            fileUpload.classList.remove('bg-light');
        }

        fileUpload.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            
            const fileNameDisplay = document.getElementById('file-name-display');
            if (files.length > 0) {
                fileNameDisplay.textContent = files[0].name;
            }
        }
    </script>
</body>
</x-app-layout>
</html>