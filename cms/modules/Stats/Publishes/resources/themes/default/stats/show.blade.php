@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $stat->id !!} - <span>{!! $stat->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('stats', $stat->id)
@endsection
