<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameSlot | Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Open Sans', sans-serif;
        }

        .register-card {
            background-color: #1e1e1e;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .register-card .form-control {
            background-color: #2d2d2d;
            border: 1px solid #444;
            color: #fff;
        }

        .register-card .form-control:focus {
            background-color: #2d2d2d;
            color: #fff;
            border-color: #63b3ed;
            box-shadow: none;
        }

        .register-card .btn-primary {
            background-color: #63b3ed;
            border: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .register-card .btn-primary:hover {
            background-color: #4299e1;
        }

        .register-card .btn-secondary {
            background-color: #333;
            border: none;
            color: #fff;
        }

        .register-card a {
            color: #63b3ed;
            text-decoration: none;
        }

        .register-card a:hover {
            text-decoration: underline;
        }

        .navbar-brand img {
            width: 75px;
            height: 75px;
        }

        .form-check-label {
            margin-left: 5px;
        }

        @media (max-width: 576px) {
            .register-card {
                padding: 30px 20px;
            }
        }
        .btn-back {
    font-weight: bold;               /* Bold text */
    color: #fff !important;          /* White text */
    border-color: #fff !important;   /* White border */
    text-decoration: none !important;
}

.btn-back:hover {
    color: #000 !important;          /* Black text on hover */
    background-color: #fff !important; /* White background on hover */
    border-color: #fff !important;
    text-decoration: none !important;  /* Remove underline on hover */
}

    </style>
</head>

<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 register-card">

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

            <form action="{{ route('register_logic') }}" method="POST">
                @csrf
                <div class="text-center mb-4">
                    <a class="navbar-brand" href="/">
                        <img src="{{ asset('storage/images/gameslot_logo.png') }}" alt="GameSlot">
                    </a>
                    <h1 class="h4 mt-2">Register a New Account</h1>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        placeholder="Full Name" value="{{ old('name') }}">
                    <label>Full Name</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        placeholder="Email" value="{{ old('email') }}">
                    <label>Email Address</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                        placeholder="Password">
                    <label>Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation" placeholder="Confirm Password">
                    <label>Confirm Password</label>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <label class="mt-2">Gender</label>
                <div class="d-flex gap-3 mb-3">
                    <div class="form-check">
                        <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender"
                            value="Male" id="genderMale" {{ old('gender') == 'Male' ? 'checked' : '' }}>
                        <label class="form-check-label" for="genderMale">Male</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender"
                            value="Female" id="genderFemale" {{ old('gender') == 'Female' ? 'checked' : '' }}>
                        <label class="form-check-label" for="genderFemale">Female</label>
                    </div>
                    @error('gender')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Date of Birth</label>
                    <input type="date" class="form-control @error(' ') is-invalid @enderror"
                        name="date_of_birth" value="{{ old('date_of_birth') }}">
                    @error('date_of_birth')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-outline-primary w-100 mb-3" type="submit">Register Account</button>
                <a href="{{ route('landing_page') }}" class="btn btn-outline-secondary w-100 mb-3 btn-back">Back to Landing Page</a>


                <p class="text-center mb-1">Already have an account?</p>
                <div class="text-center">
                    <a href="{{ route('login_page') }}">Login Here</a>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
