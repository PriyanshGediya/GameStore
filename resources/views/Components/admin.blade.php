<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <title>@yield('title')</title>
</head>
{{-- Body: Added 'text-bg-dark' for the dark theme --}}
<body class="text-bg-dark">

    {{-- Header/Navbar --}}
    <header class="p-3 mb-3 border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

                <a class="navbar-brand me-lg-4" href="{{route('home_admin')}}">
                    <img src="{{asset('storage/images/gameslot_logo.png')}}" width="75" height="75" alt="GameSlot">
                </a>

                {{-- Navbar: Combined all navigation links into one list for a cleaner look --}}
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="{{route('admin.games.index')}}" class="nav-link px-2 text-white">Manage Games</a></li>
                    <li><a href="{{route('admin.genres.index')}}" class="nav-link px-2 text-white">Manage Genres</a></li>
                    <li><a href="{{ route('admin.membership.index') }}" class="nav-link px-2 text-white">Manage Membership</a></li>
                    <li><a href="{{ route('admin.users.index') }}" class="nav-link px-2 text-white">Manage Users</a></li>
                </ul>

                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="{{route('admin.search')}}" role="search">
                    <input type="search" class="form-control form-control-dark text-bg-dark" name="search_game_name" placeholder="Search..." aria-label="Search">
                </form>

                {{-- Dropdown: Added 'dropdown-menu-dark' and changed link color to white --}}
                <div class="dropdown text-end">
                    <a href="#" class="d-block text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        {{-- Added a user icon next to the name for better UI --}}
                        <i class="bi bi-person-circle me-1"></i>
                        {{Auth::user()->name}}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small">
                        <li><a class="dropdown-item" href="{{route('admin.profile.view')}}"><i class="bi bi-person me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{route('logout')}}"><i class="bi bi-box-arrow-left me-2"></i> Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    {{-- Web Content --}}
    <div class="container mt-4 mb-5">
        @yield('content')
    </div>


    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <div class="col-md-4 d-flex align-items-center">
                {{-- Footer: Changed text color to be visible on the dark background --}}
                <span class="mb-3 mb-md-0 text-light">© 2025 Epics Game Store Admin</span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    {{-- Note: You only need one set of Bootstrap/Popper/jQuery JS files. The Bootstrap 5 bundle includes Popper.js. --}}
    {{-- Removed redundant older JS links for cleaner code --}}
</body>
</html>