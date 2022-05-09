@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $player->id !!} - <span>{!! $player->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('players', $player->id)
@endsection
