<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #121212; /* dark background */
            color: #fff;
        }

        .navbar-brand img {
            height: 60px;
            width: auto;
        }

        .search-bar {
            max-width: 500px;
            width: 100%;
        }

        .dropdown-menu a:hover {
            background-color: #343a40;
            color: #fff;
        }

        .cart-icon {
            font-size: 1.5rem;
            color: #fff;
        }

        footer {
            color: #ccc;
        }

        @media (max-width: 768px) {
            .search-bar {
                max-width: 100%;
                margin-top: 10px;
            }

            .d-flex.nav-items {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <!-- Header / Navbar -->
    <nav class="container d-flex flex-wrap align-items-center justify-content-between py-3">
        <a class="navbar-brand" href="{{route('home_guest')}}">
            <img src="{{asset('storage/images/gameslot_logo.png')}}" alt="Game Store">
        </a>

        <form action="{{route('customer.search')}}" class="mx-auto flex-grow-1 d-flex justify-content-center" role="search">
            @csrf
            <input type="search" class="form-control search-bar" name="search_game_name" placeholder="Search games...">
        </form>

        <div class="d-flex align-items-center gap-3 nav-items">
            @auth
                <div class="dropdown">
                    <button class="btn btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Filter by Genre
                    </button>
                    <ul class="dropdown-menu">
                        @foreach(\App\Models\Genre::all() as $genre)
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.cart.filter.genre', $genre->id) }}">
                                    {{ $genre->genre_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endauth

            <a href="{{route('customer.cart.view')}}" class="cart-icon">
                <i class="bi bi-cart2"></i>
            </a>

            <div class="dropdown text-end">
                @auth
                    <a href="#" class="text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{route('customer.history')}}"><i class="bi bi-clock-history"></i> History</a></li>
                        <li><a class="dropdown-item" href="{{route('customer.profile.edit')}}"><i class="bi bi-person"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('customer.customer.library') }}"><i class="bi bi-collection"></i> Library</a></li>

                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{route('logout')}}"><i class="bi bi-box-arrow-left"></i> Sign out</a></li>
                    </ul>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-white text-decoration-none">Login</a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 mb-5">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="container py-3 border-top text-center">
        <span>© 2025 Game Store</span>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
