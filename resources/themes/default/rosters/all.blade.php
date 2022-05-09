@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Roster</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($rosters as $roster)
                <a href="{!! URL::to('rosters/'.$roster->id) !!}"><p>{!! $roster->name !!} - <span>{!! $roster->updated_at !!}</span></p></a>
            @endforeach

            {!! $rosters !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('rosters')
@endsection