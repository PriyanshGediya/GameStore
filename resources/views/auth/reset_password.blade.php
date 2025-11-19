@extends('Components.customer')

@section('title', 'Reset Password')

@section('content')
<div class="col-md-3 container d-flex flex-wrap justify-content-center mt-3">

<style>
    .btn-otp {
    border: 2px solid #fff;    /* White border */
    color: #fff;               /* White text */
    background-color: transparent;
    font-weight: bold;
    text-decoration: none;     /* Remove underline for <a> tags */
    transition: all 0.3s ease;
}

.btn-otp:hover {
    background-color: #fff;    /* White background */
    color: #000;               /* Black text */
    text-decoration: none;     /* Ensure no underline on hover */
}
</style>
    <main class="form-signin w-100 m-auto">
        <form action="{{ route('forgot_password.reset') }}" method="POST" class="flex-wrap">
            @csrf

            {{-- Logo --}}
            <a class="navbar-brand px-2 d-flex justify-content-center text-center" href="/">
                <img src="{{ asset('storage/images/gameslot_logo.png') }}" width="75" height="75" alt="GameSlot">
            </a>

            {{-- Heading --}}
            <h1 class="h3 mb-3 fw-normal d-flex justify-content-center text-center mt-3">Reset Your Password</h1>

            {{-- Alerts --}}
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

            {{-- New Password --}}
            <div class="form-floating mt-3">
                <input type="password" class="form-control" id="password" placeholder="New Password" name="password" required>
                <label for="password" style="color: black;">New Password</label>
            </div>

            {{-- Confirm Password --}}
            <div class="form-floating mt-3">
                <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation" required>
                <label for="password_confirmation" style="color: black;">Confirm Password</label>
            </div>

            {{-- Submit Button --}}
<button class="w-100 btn btn-otp btn-lg mt-3" type="submit">Reset Password</button>

{{-- Back to Landing Page --}}
<a href="{{ route('landing_page') }}" class="btn btn-otp btn-lg mt-3 w-100">Back to Landing Page</a>


            {{-- Login Link --}}
            <p class="mt-3 mb-1 text-muted d-flex flex-wrap justify-content-center">Already have an account?</p>
            <a href="{{ route('login_page') }}" class="d-flex flex-wrap justify-content-center mb-3" style="color: white;">Login Here</a>
        </form>
    </main>
</div>
@endsection
