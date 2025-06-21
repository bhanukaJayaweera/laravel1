<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .correct-answer {
            background-color: #d4edda !important;
            border-left: 4px solid #28a745;
        }
        .search-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Questions List</h1>
            <a href="{{ route('fetch.question') }}" class="btn btn-primary">
                Fetch New Questions
            </a>
        </div>

        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="search-container mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search questions...">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select" id="filterSelect">
                        <option value="">All Questions</option>
                        <option value="a">Questions with Answer A</option>
                        <option value="b">Questions with Answer B</option>
                        <option value="c">Questions with Answer C</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row" id="questionsContainer">
            @foreach($questions as $question)
                <div class="col-md-6 question-item">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Question #{{ $loop->iteration }}</h5>
                            <div class="action-buttons">
                                <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-muted">Category: {{ $question->category ?? 'General' }}</h6>
                            <p class="card-text fw-bold">{{ $question->question }}</p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item {{ $question->correct_answer == 'a' ? 'correct-answer' : '' }}">
                                    <strong>A)</strong> {{ $question->answer_a }}
                                </li>
                                <li class="list-group-item {{ $question->correct_answer == 'b' ? 'correct-answer' : '' }}">
                                    <strong>B)</strong> {{ $question->answer_b }}
                                </li>
                                <li class="list-group-item {{ $question->correct_answer == 'c' ? 'correct-answer' : '' }}">
                                    <strong>C)</strong> {{ $question->answer_c }}
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer text-muted">
                            Last updated: {{ $question->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($questions->isEmpty())
            <div class="alert alert-info">
                No questions found in the database. 
                <a href="{{ route('fetch.question') }}" class="alert-link">Fetch some questions</a>.
            </div>
        @endif
<!-- 
        <div class="d-flex justify-content-center mt-4">
            {{ $questions->links() }}
        </div> -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchButton').click(function() {
                const searchText = $('#searchInput').val().toLowerCase();
                $('.question-item').each(function() {
                    const questionText = $(this).text().toLowerCase();
                    $(this).toggle(questionText.includes(searchText));
                });
            });

            // Filter functionality
            $('#filterSelect').change(function() {
                const filterValue = $(this).val();
                if (filterValue === '') {
                    $('.question-item').show();
                } else {
                    $('.question-item').each(function() {
                        const hasAnswer = $(this).find(`.list-group-item:nth-child(${filterValue.charCodeAt(0) - 96})`)
                                            .text().trim().length > 3;
                        $(this).toggle(hasAnswer);
                    });
                }
            });

            // Initialize DataTable if you want table view
            // $('#questionsTable').DataTable();
        });
    </script>
</body>
</html>