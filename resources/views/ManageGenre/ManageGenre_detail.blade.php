@extends('Components.admin')

@section('title', 'Genre Detail | GameSlot')

@section('content')

<form action="{{ route('admin.genres.update', ['id' => $genre->id]) }}" method="POST" enctype="multipart/form-data">
    @method('patch')
    @csrf

    <div class="mb-3">
        <label for="genre_name" class="form-label">Game Genre <span style="color: red">*</span></label>
        <input 
            type="text" 
            id="genre_name" 
            name="genre_name" 
            value="{{ $genre->genre_name }}" 
            class="form-control @error('genre_name') is-invalid @enderror">

        @error('genre_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <button type="submit" class="btn btn-warning mt-4">Update Genre</button>
</form>

@endsection
