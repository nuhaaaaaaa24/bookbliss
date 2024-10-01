<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups Management</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* You can add custom styles here if needed */
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
            <h2>Groups Management</h2>
            <hr>

            <!-- Groups Table -->
            <h3>Groups List</h3>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Users Count</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>{{ $group->id }}</td>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->description }}</td>
                        <td>{{ $group->users_count }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Chart for User Registrations -->
            <h3>User Registrations Chart</h3>
            <canvas id="userChart"></canvas>

            <!-- Scripts for Bootstrap and Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                var ctx = document.getElementById('userChart').getContext('2d');
                var userChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($groups->pluck('name')) !!},
                        datasets: [{
                            label: 'Users in Each Group',
                            data: {!! json_encode($groups->pluck('users_count')) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
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
