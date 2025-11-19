@extends('Components.admin')

@section('title', 'Add Game | GameSlot')

@section('content')
    <form action="{{ route('admin.games.index') }}">
        <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back</button>
    </form>
    <br>

    <h3>Add New Game</h3>

    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Game Name --}}
        <div class="mb-3">
            <label for="game_name" class="form-label">Name<span style="color: red">*</span></label>
            <input type="text" 
                   class="form-control @error('game_name') is-invalid @enderror" 
                   id="game_name" 
                   name="game_name" 
                   value="{{ old('game_name') }}">
            @error('game_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Game Image --}}
        <div class="form-group mb-3">
            <div class="row">
                <label for="game_image">Photo<span style="color: red">*</span></label>
                <label style="margin-bottom: 2px">
                    <p style="font-weight: bold">File Type: .jpg / .jpeg / .png</p>
                </label>
                <input type="file" 
                       class="form-control-file @error('game_image') is-invalid @enderror" 
                       id="game_image" 
                       name="game_image">
            </div>
            @error('game_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Game Installer --}}
        <div class="form-group mb-3">
            <label for="installer" class="form-label">Game Installer File</label>
            <p style="font-weight: bold;">Allowed File Types: .zip / .rar / .exe</p>
            <input type="file" 
                   class="form-control-file @error('installer') is-invalid @enderror"
                   id="installer" 
                   name="installer" 
                   accept=".zip,.rar,.exe">
            @error('installer')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Game Description --}}
        <div class="mb-3">
            <label for="game_detail" class="form-label">Game Description<span style="color: red">*</span></label>
            <div class="form-floating">
                <textarea name="game_detail" 
                          class="form-control @error('game_detail') is-invalid @enderror" 
                          id="game_detail" 
                          style="height: 200px">{{ old('game_detail') }}</textarea>
            </div>
            @error('game_detail')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Game Price --}}
        <div class="mb-3">
            <label for="game_price" class="form-label">Price<span style="color: red">*</span></label>
            <input type="number" 
                   class="form-control @error('game_price') is-invalid @enderror" 
                   id="game_price" 
                   name="game_price" 
                   value="{{ old('game_price') }}">
            @error('game_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Game Genre --}}
        <div class="mb-3">
            <label for="game_genre_id" class="form-label">Genre<span style="color: red">*</span></label>
            <select id="game_genre_id" 
                    class="form-select @error('game_genre_id') is-invalid @enderror" 
                    name="game_genre_id">
                <option value="">Choose Genre</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" 
                        @if(old('game_genre_id') == $genre->id) selected @endif>
                        {{ $genre->genre_name }}
                    </option>
                @endforeach
            </select>
            @error('game_genre_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- PEGI Rating --}}
        <div class="mb-3">
            <label for="game_pegi_rating" class="form-label">PEGI Rating<span style="color: red">*</span></label>
            <select id="game_pegi_rating" 
                    class="form-select @error('game_pegi_rating') is-invalid @enderror" 
                    name="game_pegi_rating">
                <option value="">Choose PEGI Rating</option>
                <option value="0" @if(old('game_pegi_rating') == 0) selected @endif>0</option>
                <option value="3" @if(old('game_pegi_rating') == 3) selected @endif>3</option>
                <option value="7" @if(old('game_pegi_rating') == 7) selected @endif>7</option>
                <option value="12" @if(old('game_pegi_rating') == 12) selected @endif>12</option>
                <option value="16" @if(old('game_pegi_rating') == 16) selected @endif>16</option>
                <option value="18" @if(old('game_pegi_rating') == 18) selected @endif>18</option>
            </select>
            @error('game_pegi_rating')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-warning mt-4">Add Game</button>
    </form>
@endsection
