@extends('Components.customer')

@section('title', 'Home | Epic Games')

@section('content')
<style>
    /* Base styles, adjusted for better cohesion */
    body {
        background-color: #121212;
        color: #e0e0e0;
        font-family: 'Segoe UI', sans-serif;
    }

    /* Hero and Carousel section */
    .carousel-item img {
        border-radius: 12px;
        object-fit: cover;
        height: 600px;
    }

    /* Right Stack styling */
    .side-panel {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .side-game {
        border-radius: 12px;
        transition: transform 0.3s ease;
        flex-grow: 1;
        position: relative;
        overflow: hidden;
        display: block;
        height: 180px; /* Fixed height for each item in the side panel */
    }
    
    .side-game a {
        position: relative;
        display: block;
        height: 100%;
    }

    .side-game:hover {
        transform: scale(1.03);
    }

    .side-game img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .side-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 8px 15px;
        font-weight: 600;
        font-size: 1rem;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
    }
    
    /* Game Card styling */
    .game-card {
        background-color: #1e1e1e;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .game-card:hover {
        transform: translateY(-5px);
    }

    .game-card img {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .card-body {
        padding: 12px;
    }

    .card-body h6 {
        font-weight: 600;
        font-size: 0.95rem;
        color: #fff !important;
    }

    .section-title {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 20px;
        color: #fff !important;
    }
    
    .text-muted {
        color: #a0a0a0 !important;
    }

    .popular-games .card-title {
        color: #fff !important;
    }

    .carousel-caption {
        backdrop-filter: blur(8px);
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 12px;
        padding: 15px 20px;
        position: absolute;
        bottom: 20px;
        left: 20px;
        right: auto;
        width: 45%;
        text-align: left;
    }

    .carousel-caption h2 {
        font-weight: 700;
        font-size: 1.8rem;
    }

    .btn-custom {
        padding: 8px 16px;
        border-radius: 8px;
        transition: 0.3s ease;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
    }
    
    /* Game Pass section styling */
    .game-pass-card {
        background-color: #2a2a2a;
        border-radius: 12px;
        border: 2px solid #5a5a5a;
        transition: all 0.3s ease;
    }

    .game-pass-card:hover {
        border-color: #007bff;
        transform: translateY(-5px);
    }

    .game-pass-header {
        background-color: #1e1e1e;
        border-bottom: 2px solid #5a5a5a;
        padding: 20px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .join-btn {
        background-color: #4CAF50;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 50px;
        transition: background-color 0.3s ease;
    }

    .join-btn:hover {
        background-color: #000000ff;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .feature-list li {
        padding: 8px 0;
        border-bottom: 1px solid #3a3a3a;
        font-size: 0.9rem;
    }

    .feature-list li:last-child {
        border-bottom: none;
    }

    .feature-list i {
        color: #4CAF50;
        margin-right: 10px;
    }

    .text-green {
        color: #4CAF50 !important;
    }
</style>

<div class="container-fluid py-4">

    {{-- Hero Section --}}
    <div class="row g-3 align-items-stretch">
        {{-- Main Carousel (col-lg-9) --}}
        <div class="col-lg-9">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($all_games->take(3) as $key => $a)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <a href="{{ route('customer.detail', ['id' => $a->id]) }}">
                                <img src="{{ asset($a->game_image) }}" class="d-block w-100">
                            </a>
                            <div class="carousel-caption d-none d-md-block text-start">
                                <h2>{{ $a->game_name }}</h2>
                                <p class="mb-3">INR {{ $a->game_price }} | {{ $a->genre->genre_name }}</p>
                                <a href="{{ route('customer.detail', ['id' => $a->id]) }}" class="btn btn-light btn-custom">Play Now</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        {{-- Right Side Panel (col-lg-3) --}}
        <div class="col-lg-3 side-panel">
            @foreach ($all_games->skip(2)->take(2) as $a)
                <div class="side-game">
                    <a href="{{ route('customer.detail', ['id' => $a->id]) }}">
                        <img src="{{ asset($a->game_image) }}">
                        <div class="side-caption" style="color: white;">
                            {{ $a->game_name }}
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    

    {{-- Discover Section --}}
    <div class="mt-5">
        <h4 class="section-title">Discover Something New</h4>
        <div class="row g-3">
            @foreach ($last_five_games->chunk(6) as $chunk)
                @foreach ($chunk as $a)
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="game-card shadow-sm">
                            <a href="{{ route('customer.detail', ['id' => $a->id]) }}">
                                <img src="{{ asset($a->game_image) }}">
                            </a>
                            <div class="card-body">
                                <h6>{{ Str::limit($a->game_name, 20) }}</h6>
                                <small class="text-muted">INR {{ $a->game_price }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- Popular Games Section --}}
    <h3 class="mt-5 mb-4 section-title">Popular Games</h3>
    <div class="row g-3 popular-games">
        @foreach ($popular_games as $game)
            <div class="col-6 col-md-3 col-lg-2">
                <div class="game-card shadow-sm">
                    <a href="{{ route('customer.detail', ['id' => $game->id]) }}">
                        <img src="{{ asset($game->game_image) }}">
                    </a>
                    <div class="card-body text-center p-2">
                        <h6>{{ Str::limit($game->game_name, 18) }}</h6>
                        <small class="text-muted">INR {{ $game->game_price }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

   
    {{-- Game Pass Section (Enhanced) --}}
{{-- Membership Section --}}
{{-- Membership / Game Pass Section --}}
<div class="mt-5 mb-4">
    <h4 class="section-title">Exclusive Membership Plans</h4>

    @if($all_membership_plans->count() > 2)
        {{-- Carousel for more than 2 plans --}}
        <div id="membershipCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($all_membership_plans->chunk(2) as $chunkIndex => $chunk)
                    <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                        <div class="row justify-content-center g-4">
                            @foreach($chunk as $plan)
                                <div class="col-md-5 col-lg-4">
                                    <div class="game-pass-card p-4 position-relative shadow-lg">
                                        @if($plan->discount)
                                            <div class="ribbon">{{ $plan->discount }}% OFF</div>
                                        @endif
                                        <div class="text-center mb-4">
                                            <h4 class="text-white">{{ $plan->name }}</h4>
                                            <p class="text-green fw-bold">₹{{ $plan->price }} / {{ $plan->duration_type }}</p>
                                            <p class="text-muted small">{{ $plan->description }}</p>
                                            <p class="text-green small fst-italic">Save More. Play More.</p>
                                        </div>
                                        <ul class="feature-list">
                                            <li><i class="fas fa-check-circle"></i> {{ $plan->benefit ?? 'Special discount on games' }}</li>
                                        </ul>
                                        <div class="text-center mt-4">
                                            <a href="{{ route('customer.membership.checkout', ['plan_id' => $plan->id]) }}" class="btn join-btn w-100">JOIN NOW</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#membershipCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#membershipCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    @else
        {{-- Just display cards normally if 2 or less --}}
        <div class="row justify-content-center g-4">
            @foreach($all_membership_plans as $plan)
                <div class="col-md-5 col-lg-4">
                    <div class="game-pass-card p-4 position-relative shadow-lg">
                        @if($plan->discount)
                            <div class="ribbon">{{ $plan->discount }}% OFF</div>
                        @endif
                        <div class="text-center mb-4">
                            <h4 class="text-white">{{ $plan->name }}</h4>
                            <p class="text-green fw-bold">₹{{ $plan->price }} / {{ $plan->duration_type }}</p>
                            <p class="text-muted small">{{ $plan->description }}</p>
                            <p class="text-green small fst-italic">Save More. Play More.</p>
                        </div>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> {{ $plan->benefit ?? 'Special discount on games' }}</li>
                        </ul>
                        <div class="text-center mt-4">
                            <a href="{{ route('customer.membership.checkout', ['plan_id' => $plan->id]) }}" class="btn join-btn w-100">JOIN NOW</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>



<style>
    .text-green {
        color: #ffffffff !important;
    }
    .game-card img {
        border-radius: 12px;
        height: 120px;
        object-fit: cover;
    }
</style>

<style>
/* Ribbon Badge */
.ribbon {
    width: 90px;
    height: 25px;
    background: #ff4c4c;
    color: #fff;
    font-weight: bold;
    font-size: 0.85rem;
    text-align: center;
    line-height: 25px;
    position: absolute;
    top: 10px;
    right: -25px;
    transform: rotate(45deg);
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

/* Card Hover Glow */
.game-pass-card:hover {
    transform: translateY(-5px) scale(1.02);
    border-color: #ffffffff;
    box-shadow: 0 10px 20px rgba(0, 170, 255, 0.3);
    transition: all 0.3s ease;
}

/* Gradient CTA Button */
.join-btn {
    background: #000000ff;
    color: white;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 50px;
    
}


/* Additional micro-copy style */
.game-pass-card .fst-italic {
    font-style: italic;
    font-size: 0.85rem;
    margin-top: 5px;
    color: #4CAF50;
}
</style>

</div>
@endsection