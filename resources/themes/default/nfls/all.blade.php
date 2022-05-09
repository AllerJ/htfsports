@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Nfl</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($nfls as $nfl)
                <a href="{!! URL::to('nfls/'.$nfl->id) !!}"><p>{!! $nfl->name !!} - <span>{!! $nfl->updated_at !!}</span></p></a>
            @endforeach

            {!! $nfls !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('nfls')
@endsection