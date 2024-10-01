<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenges Participants</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* You can add custom styles here if needed */
        .chart-container {
            position: relative;
            margin: auto;
            height: 40vh;
            width: 80vw;
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
            <h2>Challenges Participants</h2>
            <hr>

            <h3>All Participants</h3>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>User Name</th>
                    <th>Challenge Title</th>
                    <th>Status</th>
                    <th>Progress</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($participants as $participant)
                    <tr>
                        <td>{{ $participant->user_name }}</td>
                        <td>{{ $participant->challenge_title }}</td>
                        <td>{{ $participant->status }}</td>
                        <td>{{ $participant->progress }}%</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Doughnut Charts for Overall Progress -->
            <h3>Overall Progress Overview</h3>
            <div class="chart-container">
                <canvas id="completedChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="ongoingChart"></canvas>
            </div>

            <script>
                var ctxCompleted = document.getElementById('completedChart').getContext('2d');
                var ctxOngoing = document.getElementById('ongoingChart').getContext('2d');

                // Prepare data for the charts
                var participants = {!! json_encode($participants) !!};

                // Count completed and ongoing participants
                var completedCount = participants.filter(p => p.status === 'completed').length;
                var ongoingCount = participants.filter(p => p.status === 'ongoing').length;

                var completedChart = new Chart(ctxCompleted, {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'Remaining'],
                        datasets: [{
                            label: 'Completed Challenges',
                            data: [completedCount, participants.length - completedCount],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(255, 99, 132, 0.2)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Overall Completed Challenges'
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
