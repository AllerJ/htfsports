@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Player</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($players as $player)
                <a href="{!! URL::to('players/'.$player->id) !!}"><p>{!! $player->name !!} - <span>{!! $player->updated_at !!}</span></p></a>
            @endforeach

            {!! $players !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('players')
@endsection