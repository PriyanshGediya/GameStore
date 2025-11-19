<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | GameSlot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Open Sans', sans-serif;
        }

        .forgot-card {
            background-color: #1e1e1e;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .forgot-card .form-control {
            background-color: #2d2d2d;
            border: 1px solid #444;
            color: #fff;
        }

        .forgot-card .form-control:focus {
            background-color: #2d2d2d;
            border-color: #63b3ed;
            box-shadow: none;
            color: #fff;
        }

        .forgot-card .btn-primary {
            background-color: #63b3ed;
            border: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .forgot-card .btn-primary:hover {
            background-color: #4299e1;
        }

        .forgot-card a {
            color: #63b3ed;
            text-decoration: none;
        }

        .forgot-card a:hover {
            text-decoration: underline;
        }

        .text-center h3 {
            margin-bottom: 20px;
        }
        .btn-otp {
    border: 2px solid #fff;    /* White border */
    color: #fff;               /* White text */
    background-color: transparent;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-otp:hover {
    background-color: #fff;    /* White background */
    color: #000;               /* Black text */
}

    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 forgot-card">

            <div class="text-center mb-4">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('storage/images/gameslot_logo.png') }}" width="75" height="75" alt="GameSlot">
                </a>
                <h3>Forgot Password</h3>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('forgot_password.send_otp') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Enter your registered Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-otp w-100">Send OTP</button>

            </form>

            <div class="mt-3 text-center">
                <a href="{{ route('login_page') }}" style="color: white;">Back to Login</a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
