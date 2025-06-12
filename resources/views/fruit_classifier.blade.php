<x-app-layout>
    <x-slot name="header">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <h2 class="text-4xl font-bold text-center" style="color: #f97316;">
            {{ __('Fruit Image Classifier') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <!-- Upload Card -->
            <div class="text-center mb-8">
                <div class="w-full max-w-md mx-auto">
                    <form method="POST" action="{{ route('fruit.predict') }}" enctype="multipart/form-data" class="space-y-6" id="uploadForm">
                        @csrf
                        
                        <!-- File Upload with Preview -->
                        <div class="space-y-2">
                            <label class="block text-lg font-medium text-gray-700">
                                Upload a fruit image
                            </label>
                            
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-lg">
                                <div class="space-y-1 text-center">
                                    <div class="flex text-sm text-gray-600">
                                        <label for="fruit_image" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none">
                                            <span>Click to select </span><span> ||  </span>
                                            <input id="fruit_image" name="fruit_image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                        </label>
                                        <p class="pl-1">drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG up to 5MB
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="hidden mt-4">
                                <img id="preview" class="mx-auto max-h-64 rounded-lg shadow-md" src="#" alt="Preview">
                                <button type="button" onclick="clearImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                    <i class="fas fa-times mr-1"></i> Remove image
                                </button>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                   <!-- Submit Button with Emerald Color Scheme -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150 ease-in-out btn btn-primary">
                            <i class="fas fa-search mr-2"></i> Classify Fruit
                        </button>
                    </div>
                    </form>
                </div>
            </div>
            
            <!-- Results Section -->
            @if(session('prediction'))
                <div class="mt-8 bg-orange-50 border-l-4 border-orange-400 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-orange-400 text-2xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-xl font-bold text-orange-800">Classification Results</h3>
                            <div class="mt-2 text-orange-700">
                                <p class="text-lg">
                                    <span class="font-semibold">Prediction:</span> {{ session('prediction') }}
                                </p>
                                <p class="text-lg">
                                    <span class="font-semibold">Confidence:</span> 
                                    <span class="px-2 py-1 rounded-full text-white" style="background-color: {{ session('confidence') > 0.75 ? '#10b981' : (session('confidence') > 0.5 ? '#f59e0b' : '#ef4444') }}">
                                        {{ number_format(session('confidence') * 100, 2) }}%
                                    </span>
                                </p>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Confidence Meter -->
                <div class="mt-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Confidence Level</span>
                        <span class="text-sm font-medium text-gray-700">{{ number_format(session('confidence') * 100, 2) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-orange-600 h-2.5 rounded-full" style="width: {{ session('confidence') * 100 }}%"></div>
                    </div>
                </div>
            @endif
            
            <!-- Loading Indicator (hidden by default) -->
            <div id="loadingIndicator" class="hidden mt-8 text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-600 mb-4 btn btn-danger"></div>
                <p class="text-lg text-indigo-800 font-medium">Analyzing your fruit image...</p>
            </div>
        </div>
    </div>

    <hr><br><br><a class="btn btn-danger" href="{{route('dashboard')}}"><i class="fa fa-home"></i> Back</a>

    <script>
        // Image preview functionality
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const imagePreview = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Clear image selection
        function clearImage() {
            document.getElementById('fruit_image').value = '';
            document.getElementById('preview').src = '#';
            document.getElementById('imagePreview').classList.add('hidden');
        }
        
        // Show loading indicator when form is submitted
        document.getElementById('uploadForm').addEventListener('submit', function() {
            document.getElementById('loadingIndicator').classList.remove('hidden');
        });
    </script>

    <style>
        /* Custom styles */
        .border-dashed {
            border-style: dashed !important;
        }
        #fruit_image:hover + label {
            cursor: pointer;
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</x-app-layout>