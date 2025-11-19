@extends('Components.admin')

@section('title', 'Update Game | GameSlot')

@section('content')
    <form action="{{route('admin.games.index')}}">
        <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back</button>
    </form>
    <br>
    <h3>Update game</h3>
    <form action="{{route('admin.games.update', ['id' => $game->id])}}" method="POST" enctype="multipart/form-data">
        @method('patch')
        @csrf

        <div class="mb-3">
            <label for="" class="form-label">Name<span style="color: red">*</label>
            <input type="text" value="{{ $game->game_name }}" class="form-control @error('game_name') is-invalid @enderror" id="" name="game_name">
            @error('game_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <div class="row">
                <label for="exampleFormControlFile1">Photo<span style="color: red">*</label>
                <label for="exampleFormControlFile1" style="margin-bottom: 2px"><p style="font-weight: bold">File Type: .jpg/.jpeg/.png</p></label>
                <input type="file" class="form-control-file @error('game_image') is-invalid @enderror" id="exampleFormControlFile1" name="game_image">
            </div>
            @error('game_image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
<div class="form-group mb-3">
    <label for="installer" class="form-label">Game Installer File</label>
    <p style="font-weight: bold;">Allowed File Types: .zip / .rar / .exe</p>

    @if($game->installer)
        <p>Current Installer:
            <a href="{{ asset('storage/installers/' . $game->installer) }}" target="_blank">
                {{ $game->installer }}
            </a>
        </p>
    @endif

    <input type="file" class="form-control-file @error('installer') is-invalid @enderror"
           id="installer" name="installer" accept=".zip,.rar,.exe">

    @error('installer')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

        <div class="mb-3">
            <label for="" class="form-label">Game Description<span style="color: red">*</label>
            <div class="form-floating">
                <textarea name="game_detail" type="long text" class="form-control @error('game_detail') is-invalid @enderror" id="floatingTextarea2" style="height: 200px">{{ $game->game_detail }}</textarea>
            </div>
            @error('game_detail')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="" class="form-label">Price<span style="color: red">*</label>
            <input type="text" value="{{ $game->game_price}}" class="form-control @error('game_price') is-invalid @enderror" id="" name="game_price">
            @error('game_price')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
    <label for="genre_id" class="form-label">Genre<span style="color: red">*</span></label>
    <select id="genre_id" class="form-select @error('genre_id') is-invalid @enderror" name="genre_id">
        <option value="">Choose Genre</option>
        @foreach($genres as $genre)
            <option value="{{ $genre->id }}" 
                @if ($game->genre_id == $genre->id) selected @endif>
                {{ $genre->genre_name }}
            </option>
        @endforeach
    </select>

    @error('genre_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

        <div class="mb-3">
            <label for="" class="form-label">PEGI Rating<span style="color: red">*</label>
                <p>Recent PEGI Rating: <span style="font-weight: bold">{{$game->game_pegi_rating}}</span></p>
                <select id="inputState" class="form-select @error('game_pegi_rating') is-invalid @enderror" name="game_pegi_rating">
                <option value="">Choose PEGI Rating</option>
                <option value="0" @if (old('game_pegi_rating') == 0) {{'selected'}} @endif>0</option>
                <option value="3" @if (old('game_pegi_rating') == 3) {{'selected'}} @endif>3</option>
                <option value="7" @if (old('game_pegi_rating') == 7) {{'selected'}} @endif>7</option>
                <option value="12" @if (old('game_pegi_rating') == 12) {{'selected'}} @endif>12</option>
                <option value="16" @if (old('game_pegi_rating') == 16) {{'selected'}} @endif>16</option>
                <option value="18" @if (old('game_pegi_rating') == 18) {{'selected'}} @endif>18</option>
            </select>
            @error('game_pegi_rating')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-warning mt-4">Update game</button>
    </form>
@endsection
