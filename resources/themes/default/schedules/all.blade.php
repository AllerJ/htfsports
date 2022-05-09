@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Schedule</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($schedules as $schedule)
                <a href="{!! URL::to('schedules/'.$schedule->id) !!}"><p>{!! $schedule->name !!} - <span>{!! $schedule->updated_at !!}</span></p></a>
            @endforeach

            {!! $schedules !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('schedules')
@endsection