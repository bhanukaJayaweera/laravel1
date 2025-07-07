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
       /* GPT-2 Form Styling */
        .gpt2-card {
            border-radius: 15px;
            overflow: hidden;
        }

        .gpt2-card .card-header {
            border-radius: 15px 15px 0 0 !important;
        }

        .gpt2-form textarea {
            min-height: 120px;
            resize: none;
            transition: height 0.2s ease;
        }

        .gpt2-form .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
    </style>
</head>

<body>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('GPT Engine') }}
        </h2>
    </x-slot>

    <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">GPT-2 Text Generator</h4>
                </div>

                <div class="card-body">
                    <!-- Response Div -->
                    <div id="response-container" class="mb-3" style="display: none;">
                        <div class="alert alert-success">
                            <h5>Generated Text:</h5>
                            <div id="generated-text"></div>
                            <div class="text-right mt-2">
                                <button id="copy-btn" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>

                    <form id="gpt2-form">
                        @csrf

                        <div class="form-group">
                            <label for="prompt">Enter your prompt:</label>
                            <textarea class="form-control" 
                                      id="prompt" 
                                      name="prompt" 
                                      rows="3" 
                                      required></textarea>
                            <div class="invalid-feedback" id="prompt-error"></div>
                        </div>

                        <!-- Advanced Options (collapsible) -->
                        <a class="btn btn-link p-0 mb-3" data-toggle="collapse" href="#advanced-options">
                            <i class="fas fa-cog"></i> Advanced Options
                        </a>
                        <div class="collapse" id="advanced-options">
                            <div class="card card-body mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="max_length">Max Length (20-500)</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="max_length" 
                                                   name="max_length" 
                                                   value="100"
                                                   min="20" 
                                                   max="500">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="temperature">Temperature (0.1-1.0)</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="temperature" 
                                                   name="temperature" 
                                                   value="0.7"
                                                   min="0.1" 
                                                   max="1.0" 
                                                   step="0.1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submit-btn">
                                <i class="fas fa-magic mr-2"></i> Generate Text
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
   <!-- <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">GPT-2 Text Generator</h4>
                </div>

                <div class="card-body">
                    @if(session('response'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <h5>Generated Text:</h5>
                        <p>{{ session('response') }}</p>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('gpt2.generate') }}">
                        @csrf

                        <div class="form-group">
                            <label for="prompt">Enter your prompt:</label>
                            <textarea class="form-control @error('prompt') is-invalid @enderror" 
                                      id="prompt" 
                                      name="prompt" 
                                      rows="3" 
                                      required>{{ old('prompt', session('original_prompt')) }}</textarea>
                            @error('prompt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="max_length">Max Length (20-500)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="max_length" 
                                           name="max_length" 
                                           value="{{ old('max_length', 100) }}"
                                           min="20" 
                                           max="500">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="temperature">Temperature (0.1-1.0)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="temperature" 
                                           name="temperature" 
                                           value="{{ old('temperature', 0.7) }}"
                                           min="0.1" 
                                           max="1.0" 
                                           step="0.1">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="top_k">Top K (1-100)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="top_k" 
                                           name="top_k" 
                                           value="{{ old('top_k', 50) }}"
                                           min="1" 
                                           max="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="top_p">Top P (0.1-1.0)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="top_p" 
                                           name="top_p" 
                                           value="{{ old('top_p', 0.9) }}"
                                           min="0.1" 
                                           max="1.0" 
                                           step="0.1">
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-magic mr-2"></i> Generate Text
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light">
                    <small class="text-muted">
                        Powered by GPT-2 API | Response times may vary
                    </small>
                </div>
            </div>
        </div>
    </div>
</div> -->
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       $(document).ready(function() {
    $('#gpt2-form').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Show loading state
        const submitBtn = $('#submit-btn');
        const originalBtnText = submitBtn.html();
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Generating...');
        
        // Hide previous response
        $('#response-container').hide();
        
        // Make AJAX request
        $.ajax({
            url: "{{ route('gpt2.generate') }}",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                // Show response
                $('#generated-text').html(response.response.replace(/\n/g, '<br>'));
                $('#response-container').fadeIn();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}-error`).text(errors[field][0]);
                    }
                } else {
                    // Other errors
                    $('#generated-text').html('Error: ' + (xhr.responseJSON.error || 'Something went wrong'));
                    $('#response-container').fadeIn();
                }
            },
            complete: function() {
                // Restore button state
                submitBtn.prop('disabled', false);
                submitBtn.html(originalBtnText);
            }
        });
    });

    // Copy to clipboard functionality
    $('#copy-btn').on('click', function() {
        const text = $('#generated-text').text();
        navigator.clipboard.writeText(text).then(function() {
            $(this).html('<i class="fas fa-check"></i> Copied!');
            setTimeout(() => {
                $(this).html('<i class="fas fa-copy"></i> Copy');
            }, 2000);
        }.bind(this));
    });

    // Auto-resize textarea
    $('#prompt').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
    </script>
</body>
</x-app-layout>
</html>