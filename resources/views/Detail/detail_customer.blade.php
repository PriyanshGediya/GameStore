@extends('Components.customer')

@section('title', 'Game Detail | GameSlot')

@section('content')
<style>
    /* Custom styles for the game detail card */
    .game-detail-card {
        background-color: #fff; /* White background */
        color: #000; /* Black text */
        line-height: 1.5em;
        border-radius: 15px;
        border: 1px solid #ccc;
    }

    /* Style for the main game title */
    .game-detail-card .card-title {
        color: #000;
        font-weight: bold;
    }

    /* Style for the game image to ensure it fits well */
    .game-detail-card img {
        border-radius: 10px;
        max-width: 100%;
        height: auto;
    }

    /* A vibrant color for the price */
    .game-detail-card .price-highlight {
        color: #63b3ed;
        font-weight: bold;
    }
</style>

<div class="card mt-5 mb-5 px-5 py-5 game-detail-card">
    <div class="container-fluid">
        <div class="wrapper row">
            <!-- Game Image -->
            <div class="preview col-md-6">
                <div class="preview-pic tab-content">
                    <div class="tab-pane active" id="pic-1">
                        <img src="{{ asset($gameDetail->game_image) }}" class="card-img-top">
                    </div>
                </div>
            </div>

            <!-- Game Details -->
            <div class="details col-md-6">
                <h3 class="card-title">{{ $gameDetail->game_name }}</h3>
                <p class="card-text" style="font-family: 'Open Sans'; font-size: 18px;">
                    {{ $gameDetail->game_detail }}
                </p>

                <h4 class="price">
                    Price: <span class="price-highlight">INR {{ $gameDetail->game_price }}</span>
                </h4>

                {{-- Purchase Button Logic --}}
                @if (Auth::check())
                    @if ($alreadyPurchased)
                        <a href="{{ asset('storage/' . $gameDetail->installer) }}"   
                           class="w-100 btn btn-success mt-4" 
                           download>
                           Install Game
                        </a>
                    @else
                        <form action="{{ route('customer.cart.add') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="qty" value="1">
                            <input type="hidden" name="game_id" value="{{ $gameDetail->id }}">
                            <button class="w-100 btn btn-lg btn-dark" type="submit">
                                Add to Cart
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="w-100 btn btn-lg btn-secondary mt-4">
                        Login to Purchase
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
