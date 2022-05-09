@extends('cms::layouts.dashboard')

@section('pageTitle') Schedule @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('schedules::schedules.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
        {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.schedules.store', 'schedules' => true, 'class' => 'add']); !!}

            {!! FormMaker::fromTable('schedules', Cms::moduleConfig('schedules', 'schedules')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/schedules') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
