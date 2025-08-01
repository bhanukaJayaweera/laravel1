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
       
    </style>
</head>

<body>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('GPT Engine') }}
        </h2>
    </x-slot>

   <div class="container">
    <h1>Pizza Restaurant Review Q&A</h1>
    
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
                <button type="submit" class="btn btn-primary">Submit</button>
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
        
        try {
            const response = await fetch('/api/reviews/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question: question,
                    include_reviews: includeReviews
                })
            });

            const data = await response.json();

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
            alert('An error occurred while processing your question');
        }
    });
    </script>
</body>
</x-app-layout>
</html>