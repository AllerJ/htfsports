@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $league->id !!} - <span>{!! $league->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('leagues', $league->id)
@endsection
