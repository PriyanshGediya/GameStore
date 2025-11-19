@extends('Components.customer')

@section('title', 'Search | GameSlot')

@section('content')
    <header class="d-flex mt-2 justify-content-between">
        <h2>Search Results</h2>
    </header>

    <div class="d-flex flex-row mb-3">
        @if (count($search_games) < 1)
            <h2 class="mt-3">Sorry, the games you are looking for are not available</h2>
        @else
            <div class="row pt-1">
                @foreach ($search_games as $sp)
                    <div class="card mx-2 mt-2 mb-4 px-0" style="width: 15rem;">
                        <!-- Link to the detail page -->
                        <a href="{{ route('customer.detail', ['id' => $sp->id]) }}">
                            <img src="{{ asset($sp->game_image) }}" class="card-img-top" width="100px" height="250px">
                        </a>
                        <div class="card-body">
                            <a href="{{ route('customer.detail', ['id' => $sp->id]) }}" class="text-decoration-none text-dark">
                                <h5 class="card-title">{{ Str::limit($sp->game_name, 30) }}</h5>
                            </a>
                            <p class="card-text">IDR {{ $sp->game_price }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div style="margin: 2rem">
        {{ $search_games->links() }}
    </div>
@endsection
