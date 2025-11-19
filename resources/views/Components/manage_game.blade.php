@extends('Components.manage_game')

@section('title', 'Manage Game | Epic Games')

@section('content')
    <h3>Manage Game</h3>

    <div class="mt-4">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($message = Session::get('success2'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.games.create') }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Game
            </button>
        </form>

        <div class="d-flex flex-row mb-3 mt-3 w-100">
            @if (count($games) < 1)
                <h2 class="mt-3">Sorry, Game(s) in this category is unavailable</h2>
            @else
                <div class="w-100">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" style="text-align: center">Game Image</th>
                                <th scope="col" style="text-align: center">Game Pegi Rating</th>
                                <th scope="col" style="text-align: center">Game Name</th>
                                <th scope="col" style="text-align: center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($games as $p)
                                <tr>
                                    <td>
                                        <img src="{{ asset($p->game_image) }}" width="250px" height="auto">
                                    </td>
                                    <td style="text-align: center">{{ $p->game_pegi_rating }}</td>
                                    <td>
                                        <h5>{{ $p->game_name }}</h5>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.games.edit', ['id' => $p->id]) }}">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="bi bi-pencil" style="font-size: 18px"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.games.delete', ['id' => $p->id]) }}" method="POST">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash3" style="font-size: 18px"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div style="margin: 2rem">
            {{ $games->links() }}
        </div>
    </div>
@endsection
