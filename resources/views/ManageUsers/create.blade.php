@extends('Components.admin')

@section('title', 'Add User | GameSlot')

@section('content')
    {{-- Back Button --}}
    <form action="{{ route('admin.users.index') }}">
        <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> Back</button>
    </form>

    <h3>Add New User</h3>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label class="form-label">Name<span style="color: red">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" placeholder="Enter full name" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Date of Birth --}}
        <div class="mb-3">
            <label class="form-label">Date of Birth<span style="color: red">*</span></label>
            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" 
                   value="{{ old('date_of_birth') }}" required>
            @error('date_of_birth')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email<span style="color: red">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" placeholder="Enter email address" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Gender --}}
        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                <option value="Male" @if(old('gender')=='Male') selected @endif>Male</option>
                <option value="Female" @if(old('gender')=='Female') selected @endif>Female</option>
                <option value="Other" @if(old('gender')=='Other') selected @endif>Other</option>
            </select>
            @error('gender')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password<span style="color: red">*</span></label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label class="form-label">Confirm Password<span style="color: red">*</span></label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        {{-- Role --}}
        <div class="mb-3">
            <label class="form-label">Role<span style="color: red">*</span></label>
            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="admin" @if(old('role')=='admin') selected @endif>Admin</option>
                <option value="customer" @if(old('role')=='customer') selected @endif>Customer</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-warning mt-3">Add User</button>
    </form>
@endsection
