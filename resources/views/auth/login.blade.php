<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameSlot | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Open Sans', sans-serif;
        }

        .login-card {
            background-color: #1e1e1e;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .login-card .form-control {
            background-color: #2d2d2d;
            border: 1px solid #444;
            color: #fff;
        }

        .login-card .form-control:focus {
            background-color: #2d2d2d;
            color: #fff;
            border-color: #63b3ed;
            box-shadow: none;
        }

        .login-card .btn-primary {
            background-color: #63b3ed;
            border: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .login-card .btn-primary:hover {
            background-color: #4299e1;
        }

        .login-card a {
            color: #63b3ed;
            text-decoration: none;
        }

        .login-card a:hover {
            text-decoration: underline;
        }

        .navbar-brand img {
            width: 75px;
            height: 75px;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-4 login-card">

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('login_page') }}" method="POST">
                @csrf

                <div class="text-center mb-4">
                    <a class="navbar-brand" href="/">
                        <img src="{{ asset('storage/images/gameslot_logo.png') }}" alt="GameSlot">
                    </a>
                    <h1 class="h4 mt-2">Log In to Epics Game Store</h1>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email"
                        value="{{ Cookie::get('userEmail') ?? '' }}">
                    <label for="email">Email address</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password"
                        value="{{ Cookie::get('userPassword') ?? '' }}">
                    <label for="password">Password</label>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="remember-me" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('forgot_password.page') }}">Forgot Password?</a>
                </div>

                <button class="btn btn-outline-primary w-100 mb-3" type="submit">Log In</button>
                <a href="{{ route('landing_page') }}" class="btn btn-outline-primary w-100 mb-3">Back to Landing Page</a>

                <p class="text-center mb-1">Need an account?</p>
                <div class="text-center">
                    <a href="{{ route('register_page') }}">Register Here</a>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
