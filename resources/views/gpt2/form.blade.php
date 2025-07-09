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

         .card-header {
            background-color: #f8f9fa;
        }
        .copy-prompt-btn {
            padding: 0.15rem 0.35rem;
            font-size: 0.75rem;
        }
        #batch-results .card {
            border-left: 4px solid #4e73df;
        }
        #batch-prompts {
            min-height: 200px;
            resize: none;
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
        <div class="col-md-10">
            
             <!-- <div class="form-group text-center mt-4">
                            <button type="button" class="btn btn-primary btn-lg px-5" id="batch_process">
                                <i class="fas fa-magic mr-2"></i> Batch Process
                            </button>
            </div> -->
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
   
 <!-- Batch Response Container -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-batch-processing mr-2"></i>GPT-2 Batch Generator</h4>
                </div>

                <div class="card-body">
                    <!-- Batch Response Container -->
                    <div id="batch-response-container" class="mb-4" style="display: none;">
                        <div class="alert alert-success">
                            <h5 class="alert-heading">Batch Results:</h5>
                            <div id="batch-results" class="mb-3"></div>
                            <div class="text-right">
                                <button id="batch-copy-btn" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-copy mr-1"></i>Copy All
                                </button>
                                <button id="batch-download-btn" class="btn btn-sm btn-outline-primary ml-2">
                                    <i class="fas fa-download mr-1"></i>Download JSON
                                </button>
                            </div>
                        </div>
                    </div>

                    <form id="batch-gpt2-form">
                        @csrf
                        
                        <div class="form-group">
                            <label for="batch-prompts">Enter prompts (one per line):</label>
                            <textarea class="form-control" 
                                      id="batch-prompts" 
                                      name="prompts" 
                                      rows="8" 
                                      placeholder="Enter each prompt on a new line"
                                      required></textarea>
                            <small class="form-text text-muted">Maximum 10 prompts per batch</small>
                            <div class="invalid-feedback" id="batch-prompts-error"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch-max-length">Max Length (20-500)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="batch-max-length" 
                                           name="max_length" 
                                           value="100"
                                           min="20" 
                                           max="500">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch-temperature">Temperature (0.1-1.0)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="batch-temperature" 
                                           name="temperature" 
                                           value="0.7"
                                           min="0.1" 
                                           max="1.0" 
                                           step="0.1">
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="batch-submit-btn">
                                <i class="fas fa-cogs mr-2"></i> Process Batch
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>Processing time depends on number of prompts
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
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

        // Batch form submission
    $('#batch-gpt2-form').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors and results
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#batch-response-container').hide();
        
        // Get prompts from textarea
        const promptText = $('#batch-prompts').val().trim();
        const prompts = promptText.split('\n').filter(p => p.trim() !== '');
        
        // Validate prompts
        if (prompts.length === 0) {
            $('#batch-prompts').addClass('is-invalid');
            $('#batch-prompts-error').text('At least one prompt is required');
            return;
        }
        
        if (prompts.length > 10) {
            $('#batch-prompts').addClass('is-invalid');
            $('#batch-prompts-error').text('Maximum 10 prompts allowed per batch');
            return;
        }
        
        // Show loading state
        const submitBtn = $('#batch-submit-btn');
        const originalBtnText = submitBtn.html();
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i> Processing...');
        
        // Prepare data
        const formData = {
            prompts: prompts,
            max_length: $('#batch-max-length').val(),
            temperature: $('#batch-temperature').val(),
            _token: $('input[name="_token"]').val()
        };
        
        // Make AJAX request
        $.ajax({
            url: "{{ route('gpt2.batch-generate') }}",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            dataType: "json",
            success: function(response) {
                if (response.error) {
                    showBatchError(response.error);
                    return;
                }
                
                displayBatchResults(response.results || []);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        $(`#batch-${field}`).addClass('is-invalid');
                        $(`#batch-${field}-error`).text(errors[field][0]);
                    }
                } else {
                    showBatchError(xhr.responseJSON.error || 'Something went wrong');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false);
                submitBtn.html(originalBtnText);
            }
        });
    });
    
    // Display batch results
    function displayBatchResults(results) {
        const container = $('#batch-results');
        container.empty();
        
        if (results.length === 0) {
            container.html('<div class="text-muted">No results generated</div>');
            $('#batch-response-container').show();
            return;
        }
        
        results.forEach((result, index) => {
            const card = $(`
                <div class="card mb-2">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold">Prompt ${index + 1}</span>
                        <button class="btn btn-sm btn-outline-secondary copy-prompt-btn" data-index="${index}">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-2"><strong>Input:</strong> ${result.prompt}</div>
                        <div><strong>Output:</strong> ${result.generated_text.replace(/\n/g, '<br>')}</div>
                    </div>
                </div>
            `);
            
            container.append(card);
        });
        
        // Initialize copy buttons for individual prompts
        $('.copy-prompt-btn').on('click', function() {
            const index = $(this).data('index');
            const text = results[index].generated_text;
            copyToClipboard(text, $(this));
        });
        
        $('#batch-response-container').fadeIn();
    }
    
    // Show batch error
    function showBatchError(message) {
        $('#batch-results').html(`<div class="text-danger">${message}</div>`);
        $('#batch-response-container').fadeIn();
    }
    
    // Copy all results to clipboard
    $('#batch-copy-btn').on('click', function() {
        const results = [];
        $('.card', '#batch-results').each(function() {
            const prompt = $(this).find('.card-body div:first').text().replace('Input: ', '');
            const output = $(this).find('.card-body div:last').text().replace('Output: ', '');
            results.push(`Prompt: ${prompt}\nOutput: ${output}\n`);
        });
        
        copyToClipboard(results.join('\n'), $(this));
    });
    
    // Download as JSON
    $('#batch-download-btn').on('click', function() {
        const results = [];
        $('.card', '#batch-results').each(function() {
            results.push({
                prompt: $(this).find('.card-body div:first').text().replace('Input: ', ''),
                generated_text: $(this).find('.card-body div:last').text().replace('Output: ', '')
            });
        });
        
        if (results.length > 0) {
            downloadAsJson(results, 'gpt2-batch-results.json');
        }
    });
    
    // Helper function to copy text
    function copyToClipboard(text, button) {
        navigator.clipboard.writeText(text).then(function() {
            const originalHtml = button.html();
            button.html('<i class="fas fa-check"></i> Copied!');
            setTimeout(() => {
                button.html(originalHtml);
            }, 2000);
        });
    }
    
    // Helper function to download JSON
    function downloadAsJson(data, filename) {
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
    
    // Auto-resize textarea
    $('#batch-prompts').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
    </script>
</body>
</x-app-layout>
</html>