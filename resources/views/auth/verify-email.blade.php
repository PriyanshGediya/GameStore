@extends('Components.guest')

@section('content')
<div class="container mt-5">
    <h2>Verify Your Email Address</h2>
    <p>We have sent a verification link to your email. Please check your inbox and click the link to verify your account.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
    </form>
</div>
@endsection
