@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $roster->id !!} - <span>{!! $roster->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('rosters', $roster->id)
@endsection
