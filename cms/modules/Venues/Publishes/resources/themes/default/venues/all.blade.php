@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Venue</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($venues as $venue)
                <a href="{!! URL::to('venues/'.$venue->id) !!}"><p>{!! $venue->name !!} - <span>{!! $venue->updated_at !!}</span></p></a>
            @endforeach

            {!! $venues !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('venues')
@endsection