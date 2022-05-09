@extends('cms::layouts.dashboard')

@section('pageTitle') Schedule @stop

@section('content')

    <div class="col-md-12">
        @include('schedules::schedules.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($schedule, ['route' => [config('cms.backend-route-prefix', 'cms').'.schedules.update', $schedule->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($schedule, FormMaker::getTableColumns('schedules')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/schedules') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


