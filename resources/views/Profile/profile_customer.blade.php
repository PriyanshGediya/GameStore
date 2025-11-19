@extends('Components.customer')

@section('title', 'My Profile | GameSlot')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ $profile->profile_picture ? asset('storage/'. $profile->profile_picture) : 'https://i.ibb.co/685Y0C8/default-avatar.png' }}"
                         alt="Profile Picture" class="rounded-circle mb-3" width="150" height="150" style="object-fit: cover;">

                    <h4 class="mb-1" style="color: black;">{{ $profile->name }}</h4>
                    <p class="text-muted mb-4">{{ $profile->email }}</p>

                    <form action="{{ route('customer.profile.picture.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label small" style="color: black;">Change Picture</label>
                            <input class="form-control form-control-sm @error('profile_picture') is-invalid @enderror" type="file" name="profile_picture" id="profile_picture" required>
                            @error('profile_picture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-outline-secondary">Upload Picture</button>
                    </form>
                </div>
            </div> 
        </div>

        <div class="col-lg-8">
            {{-- Success/Error Alerts --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('status'))
                <div class="alert alert-info">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 " style="color: black;">Edit Profile Information</h5>
                </div>
                <div class="card-body">
                    {{-- Form for Name, DOB, Gender --}}
                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-3"style="color: black;"><label for="name" class="form-label mb-0">Name</label></div>
                            <div class="col-sm-9"><input type="text" class="form-control" id="name" name="name" value="{{ old('name', $profile->name) }}"></div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-3"style="color: black;"><label for="date_of_birth" class="form-label mb-0">Date of Birth</label></div>
                            <div class="col-sm-9"><input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $profile->date_of_birth) }}"></div>
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-3"style="color: black;"><label for="gender" class="form-label mb-0">Gender</label></div>
                            <div class="col-sm-9">
                                <select class="form-select" id="gender" name="gender">
                                    <option value="Male" @if($profile->gender == 'Male') selected @endif>Male</option>
                                    <option value="Female" @if($profile->gender == 'Female') selected @endif>Female</option>
                                    <option value="Other" @if($profile->gender == 'Other') selected @endif>Other</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark">Save Info</button>
                    </form>

                    <hr>

                    {{-- Static Email display with Update button --}}
                    <div class="row align-items-center">
                        <div class="col-sm-3" style="color: black;"><p class="mb-0">Email</p></div>
                        <div class="col-sm-6"><p class="text-muted mb-0">{{ $profile->email }}</p></div>
                        <div class="col-sm-3"><button type="button" id="updateEmailBtn" class="btn btn-outline-secondary">Update</button></div>
                    </div>

                    {{-- Hidden Email Update Form --}}
                    <div id="emailUpdateForm" class="mt-3 d-none">
                        <p class="text-muted small">An OTP will be sent to your current email address to verify the change.</p>
                        <form action="{{ route('customer.profile.email.request') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Enter new email address" name="email" required>
                                <button class="btn btn-secondary" type="submit">Send OTP</button>
                            </div>
                        </form>
                        {{-- This part only appears after an OTP has been requested --}}
                        @if (session('otp_for') === 'email_change')
                            <p class="fw-bold text-info mt-3">An OTP has been sent. Please enter it below:</p>
                            <form action="{{ route('customer.profile.email.verify') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Enter 6-digit OTP" name="otp" required autofocus>
                                    <button class="btn btn-success" type="submit">Verify & Change Email</button>
                                </div>
                            </form>
                        @endif
                    </div>

                    <hr>

                    {{-- Static Password display with Update button --}}
                     <div class="row align-items-center">
                        <div class="col-sm-3" style="color: black;"><p class="mb-0">Password</p></div>
                        <div class="col-sm-6"><p class="text-muted mb-0">********</p></div>
                        <div class="col-sm-3"><button type="button" id="updatePasswordBtn" class="btn btn-outline-secondary">Update</button></div>
                    </div>

                     {{-- Hidden Password Update Form with 3 Steps --}}
                    <div id="passwordUpdateForm" class="mt-3 d-none">
                        @if (session('password_otp_verified'))
                            {{-- STEP 3: OTP is verified, now show the new password fields --}}
                            <p class="fw-bold text-success">OTP Verified. Please enter your new password.</p>
                            <form action="{{ route('customer.profile.password.update') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="password" class="form-label" style="color: black;">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label" style="color: black;">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-warning">Update Password</button>
                            </form>

                        @elseif (session('otp_for') === 'password_change')
                             {{-- STEP 2: OTP has been sent, now show the OTP entry form --}}
                            <p class="fw-bold text-info">An OTP has been sent to your email. Please enter it below to proceed.</p>
                             <form action="{{ route('customer.profile.password.otp.verify') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Enter 6-digit OTP" name="otp" required autofocus>
                                    <button class="btn btn-success" type="submit">Verify OTP</button>
                                </div>
                            </form>
                        @else
                            {{-- STEP 1: The initial state, show the button to send an OTP --}}
                            <p class="text-muted small">To change your password, we'll first send a verification code to your email.</p>
                            <form action="{{ route('customer.profile.password.otp.request') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Send OTP to my Email</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const updateEmailBtn = document.getElementById('updateEmailBtn');
        const emailUpdateForm = document.getElementById('emailUpdateForm');
        const updatePasswordBtn = document.getElementById('updatePasswordBtn');
        const passwordUpdateForm = document.getElementById('passwordUpdateForm');

        if (updateEmailBtn) {
            updateEmailBtn.addEventListener('click', function () {
                emailUpdateForm.classList.toggle('d-none');
            });
        }

        if (updatePasswordBtn) {
            updatePasswordBtn.addEventListener('click', function () {
                passwordUpdateForm.classList.toggle('d-none');
            });
        }

        // Show the correct form if there was a validation error on reload
        @if (session('otp_for') === 'email_change' || $errors->has('email') || $errors->has('otp'))
            if(emailUpdateForm) {
                emailUpdateForm.classList.remove('d-none');
            }
        @endif

        @if (session('otp_for') === 'password_change' || session('password_otp_verified') || $errors->has('otp') || $errors->has('password'))
             if(passwordUpdateForm) {
                passwordUpdateForm.classList.remove('d-none');
            }
        @endif
    });
</script>
@endsection