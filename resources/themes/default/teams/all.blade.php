@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Team</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($teams as $team)
                <a href="{!! URL::to('teams/'.$team->id) !!}"><p>{!! $team->name !!} - <span>{!! $team->updated_at !!}</span></p></a>
            @endforeach

            {!! $teams !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('teams')
@endsection