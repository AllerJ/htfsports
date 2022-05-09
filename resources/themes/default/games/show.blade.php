@extends('cms-frontend::layout.master')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
            <img src="{{ $game->venue->logo }}" class="img-fluid"> 
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h3>{{ $game->venue->name }}</h3>
            {{ $game->venue->address }}<br />
            {{ $game->venue->city }}, {{ $game->venue->state }} {{ $game->venue->zip }}
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
        <form method="post" action="/games/join">
        {!! csrf_field() !!}
        <input type="hidden" name="game_code" id="game_code" value="{{$game->game_code}}">
            <button type="submit" class="btn btn-green btn-block">Start Your Draft</button>
        </form>
            <h1 class="text-center">{{ date_format($game->game_at, 'M d, Y') }}</h1>
            <p>{{ $game->notes }}</p>
            
            <img src="{{ $game->artwork }}" class="img-fluid">
        </div>
    </div>

<br /><br /><br /><br />
</div>

@endsection
