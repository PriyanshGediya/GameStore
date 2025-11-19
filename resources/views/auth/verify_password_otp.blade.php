@extends('Components.customer')

@section('title', 'Verify OTP')

@section('content')

<div class="col-md-3 container d-flex flex-wrap justify-content-center mt-3">

<style>
    .btn-otp {
    border: 2px solid #fff;  /* White border */
    color: #fff;             /* White text */
    background-color: transparent;
    font-weight: bold;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-otp:hover {
    background-color: #fff;  /* White background */
    color: #000;             /* Black text */
    text-decoration: none;
}

</style>
<main class="form-signin w-100 m-auto">
        <form action="{{ route('forgot_password.verify') }}" method="POST" class="flex-wrap">
            @csrf

            {{-- Logo --}}
            <a class="navbar-brand px-2 d-flex justify-content-center text-center" href="/">
                <img src="{{ asset('storage/images/gameslot_logo.png') }}" width="75" height="75" alt="GameSlot">
            </a>

            {{-- Heading --}}
            <h1 class="h3 mb-3 fw-normal d-flex justify-content-center text-center mt-3">Verify OTP</h1>

            {{-- Alerts --}}
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

            {{-- OTP Input --}}
            <div class="form-floating mt-3">
                <input type="text" name="otp" class="form-control" id="otp" placeholder="Enter OTP" required>
                <label for="otp" style="color: black;" >Enter OTP</label>
            </div>

            {{-- Submit Button --}}
            {{-- Submit Button --}}
<button class="w-100 btn btn-otp btn-lg mt-3" type="submit">Verify</button>

{{-- Back Button --}}
<a href="{{ route('forgot_password.page') }}" class="btn btn-otp btn-lg w-100 mt-3">Back</a>


        </form>
    </main>
</div>
@endsection
