<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Epics Game Store  | Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/gameslot_logo.png') }}">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Open Sans', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .landing-card {
            background-color: #1e1e1e;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .landing-card .logo {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            object-fit: contain;
        }

        .landing-card h1 {
            margin-bottom: 10px;
        }

        .landing-card p {
            margin-bottom: 20px;
        }

        .btn-custom {
            width: 150px;
            margin: 5px;
            font-weight: bold;
            color: #fff;
            border: 2px solid #fff;
            background-color: transparent;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #fff;
            color: #000;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center; 
            padding: 10px 0;
            border-top: 1px solid #333;
            background-color: #121212;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="landing-card">
        <img src="{{ asset('storage/images/gameslot_logo.png') }}" alt="Logo" class="logo">
        <h1 class="display-5">Epics Game Store </h1>
        <p class="lead">Welcome to Epics Game Store  Web App</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('login_page') }}" class="btn btn-custom">Login</a>
            <a href="{{ route('register_page') }}" class="btn btn-custom">Register</a>
        </div>
    </div>

    <footer>
        © 2025
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
