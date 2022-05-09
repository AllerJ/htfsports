@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>{!! $schedule->id !!} - <span>{!! $schedule->updated_at !!}</span></h1>

</div>

@endsection

@section('cms')
    @edit('schedules', $schedule->id)
@endsection
