@extends('Components.admin')

@section('title', 'Manage Game | GameSlot')

@section('content')

<div class="card text-dark">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="card-title mb-0">Manage Games</h3>
            <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Game
            </a>
        </div>
    
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

        @if (count($games) < 1)
            <div class="alert alert-info mt-3">
                Sorry, no games are available at the moment.
            </div>
        @else
            <div class="table-responsive">
                {{-- Added table-hover for better UI and vertical alignment --}}
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 30%;">Game Image</th>
                            <th scope="col">Game Name</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($games as $p)
                            <tr>
                                <td>
                                    <img src="{{ asset($p->game_image) }}" 
     class="img-fluid rounded" 
     style="max-width: 250px;" 
     alt="{{ $p->game_name }}">

                                </td>
                                <td>
                                    <h5>{{ $p->game_name }}</h5>
                                </td>
                                {{-- Combined actions into a single column for cleaner look --}}
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.games.edit', ['id' => $p->id]) }}" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.games.delete', ['id' => $p->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this game?');">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash3"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Added pagination links for when you have many games --}}
        <div class="mt-3">
            {{ $games->links() }}
        </div>
    </div>
</div>

@endsection