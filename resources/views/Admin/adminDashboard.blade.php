<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            <h2>Welcome, {{ Auth::guard('admins')->user()->name }}</h2>
            <hr>

            <!-- User Data Section -->
            <h3>User Data</h3>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Bio</th>
                    <th>Genre</th>
                    <th>Groups</th> <!-- New Groups Column -->
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->bio }}</td>
                        <td>{{ implode(', ', $user->genre) }}</td>
                        <td>{{ $user->groups->pluck('name')->implode(', ') }}</td> <!-- Displaying groups -->
                    </tr>
                @endforeach
                </tbody>
            </table>


            <h4>Overview of users</h4>

            <hr>
            <h5>Users Counts</h5>
            <!-- User Counts Display -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-header">Total Users</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $totalUsers }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-header">Users Registered Last 7 Days</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $recentUsers }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <h5>Genres</h5>
            <canvas id="genreChart"></canvas>

            <script>
                var ctx = document.getElementById('genreChart').getContext('2d');
                var genreChart = new Chart(ctx, {
                    type: 'bar', // Choose chart type
                    data: {
                        labels: {!! json_encode(array_keys($genreCounts)) !!}, // Genre names as labels
                        datasets: [{
                            label: 'Users Count by Genre',
                            data: {!! json_encode(array_values($genreCounts)) !!}, // Counts for each genre
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            borderColor: 'rgba(255, 206, 86, 1)',
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

<!-- Scripts for Bootstrap and Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
