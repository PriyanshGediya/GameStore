@extends('Components.admin')

@section('title', 'Home | Epics Game Store')

@section('content')

<div class="row pt-1">
    @foreach ($all_games as $a)
       <div class="col-md-3 col-sm-6">
    <a href="{{ route('admin.games.manage', $a->id) }}" class="text-decoration-none text-white">
        <div class="card mx-2 mt-2 mb-4 bg-dark text-white" style="width: 15rem;">
            <img src="{{ asset($a->game_image) }}" class="card-img-top"
                 style="height: 250px; object-fit: cover;"
                 alt="{{ $a->game_name }}">
            
            <div class="card-body">
                <h5 class="card-title">{{ Str::limit($a->game_name, 30) }}</h5>
                <p class="card-text">${{ $a->game_price }}</p>
                <p class="card-text">{{ $a->genre?->genre_name ?? 'No Genre' }}</p>
            </div>
        </div>
    </a>
</div>
    
    @endforeach
</div>

{{-- Pagination --}}
<div class="d-flex justify-content-center mt-4">
    {{ $all_games->links() }}
</div>

@endsection
