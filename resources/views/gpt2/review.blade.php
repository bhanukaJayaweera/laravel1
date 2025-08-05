<!DOCTYPE html>
<html lang="en">
<x-app-layout>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload Excel File</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .spinner-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .spinner {
            width: 3rem;
            height: 3rem;
            color: white;
        }
        
        #submitBtn {
            position: relative;
        }
        
        .btn-spinner {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: none;
        }
    </style>
</head>

<body>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pizza Restaurant Review Q&A
        </h2>
    </x-slot>

    <!-- Full-page Spinner -->
    <div class="spinner-container" id="fullPageSpinner">
        <div class="spinner-border spinner" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container">
        <div class="card mb-4">
            <div class="card-body">
                <form id="questionForm">
                    <div class="mb-3">
                        <label for="question" class="form-label">Ask about customer reviews:</label>
                        <textarea class="form-control" id="question" rows="3" required></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="includeReviews">
                        <label class="form-check-label" for="includeReviews">Include review details</label>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        Submit
                        <span class="btn-spinner spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </form>
            </div>
        </div>

        <div id="responseContainer" class="card" style="display: none;">
            <div class="card-header">Answer</div>
            <div class="card-body">
                <p id="answerText"></p>
            </div>
        </div>

        <div id="reviewsContainer" class="card mt-3" style="display: none;">
            <div class="card-header">Relevant Reviews</div>
            <div class="card-body" id="reviewsList"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('questionForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const question = document.getElementById('question').value;
        const includeReviews = document.getElementById('includeReviews').checked;
        const submitBtn = document.getElementById('submitBtn');
        const btnSpinner = submitBtn.querySelector('.btn-spinner');
        const fullPageSpinner = document.getElementById('fullPageSpinner');
        
        // Show loading indicators
        submitBtn.disabled = true;
        btnSpinner.style.display = 'block';
        fullPageSpinner.style.display = 'flex';
        
        try {
            const response = await fetch('/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    question: question,
                    include_reviews: includeReviews
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Network response was not ok');
            }

            const data = await response.json();
            console.log('Response data:', data); // Debug log

            if (data.error) {
                throw new Error(data.error);
            }

            document.getElementById('answerText').textContent = data.answer;
            document.getElementById('responseContainer').style.display = 'block';

            if (includeReviews && data.reviews) {
                const reviewsList = document.getElementById('reviewsList');
                reviewsList.innerHTML = '';
                
                data.reviews.forEach(review => {
                    const reviewElement = document.createElement('div');
                    reviewElement.className = 'mb-3 p-3 border-bottom';
                    reviewElement.innerHTML = `
                        <p>${review.content}</p>
                        <small class="text-muted">Rating: ${'★'.repeat(review.rating)}</small>
                        <br>
                        <small class="text-muted">Date: ${review.date}</small>
                    `;
                    reviewsList.appendChild(reviewElement);
                });
                
                document.getElementById('reviewsContainer').style.display = 'block';
            } else {
                document.getElementById('reviewsContainer').style.display = 'none';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error: ' + error.message);
        } finally {
            // Hide loading indicators
            submitBtn.disabled = false;
            btnSpinner.style.display = 'none';
            fullPageSpinner.style.display = 'none';
        }
    });
    </script>
</body>
</x-app-layout>
</html>