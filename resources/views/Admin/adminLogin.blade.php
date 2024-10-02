<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 1500px;
            height: 500px;
        }
        .illustration {
            text-align: center;
            margin-right: 30px;
        }
        .illustration img {
            max-width: 320px;
            height: auto;
        }
        .login-container {
            max-width: 350px;
            width: 100%;
            padding: 0;
        }
        .login-container h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        .login-container form {
            margin-top: 10px;
        }
        .form-group label {
            font-weight: bold;
            font-size: 1rem;
        }
        .btn-primary {
            background-color: #FFBA00;
            border: none;
            width: 100%;
            padding: 10px;
        }
        .btn-primary:hover {
            background-color: #e0a500;
        }
        .form-check-label {
            font-size: 0.9rem;
        }
        .text-center a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            font-size: 0.85rem;
        }
        .login-container .forgot-password {
            font-size: 0.9rem;
            text-align: right;
            margin-top: 10px;
        }
        .login-container .forgot-password a {
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <!-- Illustration Section -->
    <div class="illustration">
        <img src="{{ asset('images/illustration.avif') }}" alt="Illustration">
    </div>

    <!-- Login Form Section -->
    <div class="login-container">
        <h2 class="text-center">Login</h2>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('adminLogin') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary">Log In</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
