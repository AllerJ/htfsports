@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $team->id !!} - <span>{!! $team->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('teams', $team->id)
@endsection
