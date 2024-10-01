<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews and Ratings</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 bg-dark text-white p-3">
            <h5>Admin Dashboard</h5>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('adminDashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('groups.index') }}">Groups</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.reviews') }}">Reviews and Ratings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.challenges') }}">Challenges Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.challengesParticipants') }}">Challenges Participants</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('adminLogout') }}">Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <h2>Reviews and Ratings</h2>
            <hr>

            <!-- Analytics Section -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Total Reviews</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $totalReviews }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Average Rating</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ number_format($averageRating, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Table -->
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Book Title</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Created At</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($reviews as $review)
                    <tr>
                        <td>{{ $review->user->name }}</td>
                        <td>{{ $review->book_name }}</td>
                        <td>{{ $review->rating }}</td>
                        <td>{{ $review->review_text }}</td>
                        <td>{{ $review->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Chart for Reviews -->
            <h3>Reviews Chart</h3>
            <canvas id="reviewsChart"></canvas>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                var ctx = document.getElementById('reviewsChart').getContext('2d');
                var reviewsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                        datasets: [{
                            label: 'Number of Reviews',
                            data: [
                                {!! json_encode($reviews->where('rating', 1)->count()) !!},
                                {!! json_encode($reviews->where('rating', 2)->count()) !!},
                                {!! json_encode($reviews->where('rating', 3)->count()) !!},
                                {!! json_encode($reviews->where('rating', 4)->count()) !!},
                                {!! json_encode($reviews->where('rating', 5)->count()) !!}
                            ],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 205, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 205, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(54, 162, 235, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>
</body>
</html>
