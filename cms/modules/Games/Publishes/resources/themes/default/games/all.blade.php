@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Game</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($games as $game)
                <a href="{!! URL::to('games/'.$game->id) !!}"><p>{!! $game->name !!} - <span>{!! $game->updated_at !!}</span></p></a>
            @endforeach

            {!! $games !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('games')
@endsection