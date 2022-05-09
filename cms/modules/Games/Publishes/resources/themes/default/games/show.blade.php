@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $game->id !!} - <span>{!! $game->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('games', $game->id)
@endsection
