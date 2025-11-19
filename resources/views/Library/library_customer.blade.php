@extends('Components.customer')

@section('title', 'Library | Epic Games')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">My Library</h2>

    @if($ownedGames->isEmpty())
        <div class="text-center text-muted">
            <i class="bi bi-emoji-frown" style="font-size: 2rem;"></i>
            <p class="mt-2">You haven’t purchased any games yet.</p>
            <a href="{{ route('games.index') }}" class="btn btn-primary mt-3">Browse Games</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($ownedGames as $game)
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ asset('storage/mages/' . basename($game->game_image)) }}" 
     class="card-img-top" 
     alt="{{ $game->game_name }}" 
     style="height: 200px; object-fit: cover;">

<div class="card-body text-center">
    <h5 class="card-title" style="text-decoration: none; color: black">{{ $game->game_name }}</h5>
    <p class="text-muted small mb-3">{{ Str::limit($game->game_detail, 60) }}</p>

    @if($game->installer)
        <a href="{{ asset('storage/installers/' . basename($game->installer)) }}" 
           class="btn btn-success w-100">
           <i class="bi bi-download"></i> Install
        </a>
    @else
        <button class="btn btn-secondary w-100" disabled>
            <i class="bi bi-hourglass-split"></i> Coming Soon
        </button>
    @endif
</div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection