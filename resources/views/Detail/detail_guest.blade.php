@extends('Components.customer')

@section('title', 'Game Detail | GameSlot')

@section('content')
<style>
    /* Game detail card */
    .game-detail-card {
        background-color: #1e1e1e; /* dark card background */
        color: #fff;
        line-height: 1.5em;
        border-radius: 15px;
        border: 1px solid #4a5568;
        padding: 30px;
    }

    /* Game title */
    .game-detail-card .card-title {
        color: #ffffff;
        font-weight: bold;
        font-size: 28px;
    }

    /* Game image */
    .game-detail-card img {
        border-radius: 10px;
        width: 100%;
        height: auto;
    }

    /* Price styling */
    .game-detail-card .price-highlight {
        color: #63b3ed;
        font-weight: bold;
        font-size: 22px;
    }

    /* Buttons */
    .game-detail-card .btn-dark,
    .game-detail-card .btn-primary {
        font-size: 18px;
        font-weight: bold;
        transition: 0.3s;
    }

    .game-detail-card .btn-dark:hover,
    .game-detail-card .btn-primary:hover {
        color: #63b3ed;
    }

    @media (max-width: 768px) {
        .game-detail-card {
            padding: 20px;
        }
        .game-detail-card .card-title {
            font-size: 24px;
        }
    }
</style>

<div class="card mt-5 mb-5 game-detail-card">
    <div class="row g-4">
        <div class="col-md-6">
            <img src="{{ asset($gameDetail->game_image) }}" alt="{{ $gameDetail->game_name }}">
        </div>
        <div class="col-md-6 d-flex flex-column justify-content-center">
            <h3 class="card-title">{{ $gameDetail->game_name }}</h3>
            <p style="font-size: 16px; margin-top: 10px;">{{ $gameDetail->game_detail }}</p>
            <h4 class="mt-3">Price: <span class="price-highlight">INR {{ $gameDetail->game_price }}</span></h4>

            @auth
                <!-- Logged-in user: Add to Cart -->
                <form action="{{ route('customer.cart.add') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="qty" value="1">
                    <input type="hidden" name="game_id" value="{{ $gameDetail->id }}">
                    <button class="btn btn-dark w-100 btn-lg" type="submit">Add to Cart</button>
                </form>
            @else
                <!-- Guest user: Login to Buy -->
                <a href="{{ route('login_page') }}" class="btn btn-primary w-100 btn-lg mt-4">Login to Buy Game</a>
            @endauth

        </div>
    </div>
</div>
@endsection
