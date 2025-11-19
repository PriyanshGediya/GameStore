@extends('Components.admin')

@section('title', 'Admin Profile | Epics Game Store')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card bg-dark text-light border-0 shadow-lg rounded-4 p-4">
                <div class="text-center mb-4">
                    <!-- <img src="{{ asset('images/profile_default.png') }}" 
                         alt="Profile Picture" 
                         class="rounded-circle border border-info mb-3"
                         width="120" height="120"
                         style="object-fit: cover; box-shadow: 0 0 20px rgba(0,180,216,0.3);"> -->

                    <h3 class="fw-bold text-white mb-0">{{ $profile->name }}</h3>
                    <p class="text-secondary mb-2">Admin Panel</p>
                    <hr class="border-secondary">
                </div>

                <div class="mb-3">
                    <label class="form-label text-info fw-semibold">Full Name</label>
                    <div class="form-control bg-secondary text-white border-0">{{ $profile->name }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-info fw-semibold">Email</label>
                    <div class="form-control bg-secondary text-white border-0">{{ $profile->email }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-info fw-semibold">Gender</label>
                    <div class="form-control bg-secondary text-white border-0">{{ $profile->gender ?? 'N/A' }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-info fw-semibold">Date of Birth</label>
                    <div class="form-control bg-secondary text-white border-0">{{ $profile->date_of_birth ?? 'N/A' }}</div>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    <a href="{{ route('home_admin') }}" 
                       class="btn btn-info text-white fw-semibold px-4 py-2 rounded-3"
                       style="transition: all 0.3s ease;">
                        <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #0b0f1a;
        font-family: 'Poppins', sans-serif;
    }
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,180,216,0.2);
    }
</style>
@endsection

