@extends('Components.customer')

@section('title', 'Games by Genre | GameSlot')

@section('content')
    <header class="d-flex mt-2 justify-content-between">
        <h2>Games in {{ $genre->genre_name }}</h2>
    </header>

    <div class="d-flex flex-row mb-3">
        @if ($games->isEmpty())
            <h2 class="mt-3">Sorry, no games found in this genre</h2>
        @else
            <div class="row pt-1">
                @foreach ($games as $game)
                    <div class="card mx-2 mt-2 mb-4 px-0" style="width: 15rem;">
                        <a href="{{ route('customer.detail', ['id' => $game->id]) }}">
                            <img src="{{ asset($game->game_image) }}" class="card-img-top" width="100px" height="250px">
                        </a>
                        <div class="card-body">
                            <a href="{{ route('customer.detail', ['id' => $game->id]) }}" class="text-decoration-none text-dark">
                                <h5 class="card-title">{{ Str::limit($game->game_name, 30) }}</h5>
                            </a>
                            <p class="card-text">INR {{ $game->game_price }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div style="margin: 2rem">
        {{ $games->links() }}
    </div>
@endsection
