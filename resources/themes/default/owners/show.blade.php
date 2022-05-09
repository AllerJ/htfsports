@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $owner->id !!} - <span>{!! $owner->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('owners', $owner->id)
@endsection
