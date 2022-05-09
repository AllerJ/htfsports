@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $nfl->id !!} - <span>{!! $nfl->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('nfls', $nfl->id)
@endsection
