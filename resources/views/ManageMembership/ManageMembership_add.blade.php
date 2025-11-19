@extends('Components.admin')

@section('title', 'Add Membership Plan | GameSlot')

@section('content')
    {{-- Back Button --}}
    <form action="{{ route('admin.membership.index') }}">
        <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> Back</button>
    </form>

    <h3>Create New Membership Plan</h3>

    <form action="{{ route('admin.membership.store') }}" method="POST">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label class="form-label">Name<span style="color: red">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter membership name" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Price --}}
        <div class="mb-3">
            <label class="form-label">Price ($)<span style="color: red">*</span></label>
            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Enter price" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <div class="form-floating">
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="floatingTextarea2" style="height: 150px" placeholder="Enter description">{{ old('description') }}</textarea>
            </div>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Duration Type --}}
        <div class="mb-3">
            <label for="duration_type" class="form-label">Duration Type<span style="color: red">*</span></label>
            <select id="duration_type" name="duration_type" class="form-select @error('duration_type') is-invalid @enderror" required>
                <option value="days" @if(old('duration_type')=='days') selected @endif>Days</option>
                <option value="week" @if(old('duration_type')=='week') selected @endif>Weeks</option>
                <option value="month" @if(old('duration_type')=='month') selected @endif>Months</option>
            </select>
            @error('duration_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Duration in Days --}}
        <div class="mb-3">
            <label for="duration_days" class="form-label">Duration (in days)<span style="color: red">*</span></label>
            <input type="number" id="duration_days" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror" value="{{ old('duration_days', 30) }}" required>
            @error('duration_days')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-warning mt-3">Create Membership</button>
    </form>
@endsection
