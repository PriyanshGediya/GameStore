@extends('Components.admin')

@section('title', 'Manage Genre | GameSlot')

@section('content')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Manage Genres</h3>
       
    </div>

    <div class="row g-3">
        @foreach($genres as $genre)
        <div class="col-md-4 col-sm-6">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <span class="fw-bold"style="color: black;" >{{ Str::limit($genre->genre_name, 25) }}</span>
                    <div>
                        <a href="{{ route('admin.genres.show', ['id' => $genre->id]) }}" class="btn btn-sm btn-warning me-2">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <form action="{{ route('admin.genres.update', ['id' => $genre->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this genre?')">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $genres->links() }}
    </div>
</div>

@endsection
