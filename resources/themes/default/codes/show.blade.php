@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $code->id !!} - <span>{!! $code->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('codes', $code->id)
@endsection
