@extends('Components.admin')

@section('title', 'Edit Membership Plan')

@section('content')
    <!-- Back Button -->
    <form action="{{ route('admin.membership.index') }}">
        <button type="submit" class="btn btn-primary mb-3"><i class="bi bi-arrow-left"></i> Back</button>
    </form>

    <h3>Edit Membership Plan</h3>

    <form action="{{ route('admin.membership.update', $plan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Membership Name <span style="color:red">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $plan->name) }}" 
                class="form-control @error('name') is-invalid @enderror" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label for="price" class="form-label">Price <span style="color:red">*</span></label>
            <input type="number" id="price" name="price" value="{{ old('price', $plan->price) }}" 
                class="form-control @error('price') is-invalid @enderror" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" 
                rows="4">{{ old('description', $plan->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Duration Type -->
        <div class="mb-3">
            <label for="duration_type" class="form-label">Duration Type <span style="color:red">*</span></label>
            <select id="duration_type" name="duration_type" 
                class="form-select @error('duration_type') is-invalid @enderror" required>
                <option value="days" {{ old('duration_type', $plan->duration_type) == 'days' ? 'selected' : '' }}>Days</option>
                <option value="week" {{ old('duration_type', $plan->duration_type) == 'week' ? 'selected' : '' }}>Weeks</option>
                <option value="month" {{ old('duration_type', $plan->duration_type) == 'month' ? 'selected' : '' }}>Months</option>
            </select>
            @error('duration_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Duration Days -->
        <div class="mb-3">
            <label for="duration_days" class="form-label">Duration (in days) <span style="color:red">*</span></label>
            <input type="number" id="duration_days" name="duration_days" 
                value="{{ old('duration_days', $plan->duration_days) }}" 
                class="form-control @error('duration_days') is-invalid @enderror" required>
            @error('duration_days')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-warning mt-3">Update Membership</button>
    </form>
@endsection
