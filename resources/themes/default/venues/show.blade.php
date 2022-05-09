@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $venue->id !!} - <span>{!! $venue->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('venues', $venue->id)
@endsection
