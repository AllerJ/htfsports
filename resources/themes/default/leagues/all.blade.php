@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>League</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($leagues as $league)
                <a href="{!! URL::to('leagues/'.$league->id) !!}"><p>{!! $league->name !!} - <span>{!! $league->updated_at !!}</span></p></a>
            @endforeach

            {!! $leagues !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('leagues')
@endsection