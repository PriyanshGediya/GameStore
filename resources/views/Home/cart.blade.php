@extends('Components.customer')

@section('title', 'Cart | Epics Game Store')

@section('content')
@php
$isMember = Auth::user() && Auth::user()->membership_expiry && now()->lt(Auth::user()->membership_expiry);
@endphp

<style>
    /* --- Section Titles --- */
    .section-title {
        font-weight: 700;
        font-size: 1.6rem;
        margin-bottom: 25px;
        color: #f0f0f0 !important;
    }

    .text-muted-dark { color: #b0b0b0 !important; }
    .text-green { color: #ffffffff !important; } /* brighter green */

    /* --- Cart Container --- */
    .cart-container {
        padding: 25px;
        background: linear-gradient(145deg, #1f1f1f, #181818);
        border-radius: 14px;
        color: #f0f0f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.5);
    }

    /* --- Table Styles --- */
    .table-responsive { overflow-x: auto; }
    .table {
        color: #f0f0f0;
        border-collapse: separate;
        border-spacing: 0 12px;
        width: 100%;
    }

    .table th {
        color: #fff;
        border-bottom: 2px solid #2c2c2c !important;
        text-align: center;
        font-size: 1rem;
    }

    .table td {
        background-color: #222;
        padding: 18px;
        vertical-align: middle;
        transition: 0.3s;
    }

    .table tbody tr:hover { background-color: #333; }

    /* Rounded table row corners */
    .table tbody tr td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
    .table tbody tr td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }

    /* --- Buttons --- */
    .btn-primary-custom {
        background-color: #00bfa5;
        border-color: #00bfa5;
        color: #fff;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-primary-custom:hover { background-color: #00e676; border-color: #00e676; }

    .btn-danger-custom {
        background-color: #ff3d00;
        border-color: #ff3d00;
        color: #fff;
        font-weight: 600;
    }
    .btn-danger-custom:hover { background-color: #ff5722; border-color: #ff5722; }

    /* Buttons spacing */
    .cart-actions form { margin-left: 10px; margin-top: 5px; }

    /* --- Total Section --- */
    .cart-total {
        background-color: #1a1a1a;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.4);
    }

    /* --- Responsive --- */
    @media (max-width: 768px) {
        .cart-actions form { margin-left: 0; width: 100%; }
        .cart-total { flex-direction: column; gap: 15px; }
    }

</style>

<div class="mt-4 cart-container">

    {{-- Error/Success Messages --}}
    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <h2 class="section-title">Shopping Cart</h2>

    @if($carts->isEmpty())
        <div class="text-center py-5">
            <h2 class="text-white">Your cart is empty.</h2>
            <form action="{{ route('home_customer') }}" class="mt-4">
                <button type="submit" class="btn btn btn-light">See Game(s) to Purchase</button>
            </form>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="text-align:left; padding-left: 20px;">Game</th>
                        <th>Quantity</th>
                        <th>Sub-Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carts as $c)
                        @php
                            $fullPrice = $c->qty * $c->game->game_price;
                            $discountedPrice = $fullPrice * 0.70;
                        @endphp
                        <tr>
                            <td style="display:flex; align-items:center;">
                                <img src="{{ asset($c->game->game_image) }}" alt="{{ $c->game->game_name }}" style="width:60px; height:60px; object-fit:cover; border-radius:6px; margin-right:15px;">
                                <a href="{{ route('customer.detail', ['id'=>$c->game_id]) }}" class="text-white text-decoration-none fw-bold">{{ $c->game->game_name }}</a>
                            </td>
                            <td style="text-align:center;">{{ $c->qty }}</td>
                            <td style="text-align:center;">
                                @if($isMember)
                                    <span class="text-muted-dark" style="text-decoration: line-through;">INR {{ number_format($fullPrice,2) }}</span>
                                    <br>
                                    <span class="text-green fw-bold">INR {{ number_format($discountedPrice,2) }}</span>
                                @else
                                    <span class="fw-bold">INR {{ number_format($fullPrice,2) }}</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <form action="{{ route('customer.cart.game.delete', ['id' => $c->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn btn-light">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="cart-total mt-4">
            <div>
                @if($isMember)
                    <p class="text-green mb-1 fw-bold">30% Membership Discount Applied</p>
                    <h4 class="text-white mb-0 fw-bold">Total: <span class="text-green">INR {{ number_format($totalPrice,2) }}</span></h4>
                @else
                    <h4 class="text-white mb-0 fw-bold">Total: INR {{ number_format($totalPrice,2) }}</h4>
                @endif
            </div>

            <div class="d-flex flex-wrap cart-actions mt-3 mt-md-0">
                <form action="{{ route('customer.cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline text-white btn-lg me-2">Checkout</button>
                </form>

                <form action="{{ route('customer.cart.delete_all') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline text-white btn-lg">Clear Cart</button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
