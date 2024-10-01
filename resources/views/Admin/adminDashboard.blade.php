<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* admin.css */

        /* General Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Main Container */
        .dashboard-container {
            padding: 2rem;
            margin-left: 250px; /* Sidebar width */
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background-color: #343a40;
            position: fixed;
            height: 100%;
            padding: 1.5rem;
        }

        .sidebar h3 {
            color: white;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            padding: 0.5rem;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        /* Page Heading */
        .dashboard-container h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 2rem;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        /* Analytics Section */
        .analytics-cards {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .analytics-card {
            flex: 1;
            background-color: #007bff;
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            font-size: 1.5rem;
        }

        .analytics-card.success {
            background-color: #28a745;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                margin-left: 0;
                padding: 1rem;
            }

            .analytics-cards {
                flex-direction: column;
            }
        }
    </style>
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
