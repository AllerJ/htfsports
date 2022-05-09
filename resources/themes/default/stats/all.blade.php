@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Stat</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($stats as $stat)
                <a href="{!! URL::to('stats/'.$stat->id) !!}"><p>{!! $stat->name !!} - <span>{!! $stat->updated_at !!}</span></p></a>
            @endforeach

            {!! $stats !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('stats')
@endsection