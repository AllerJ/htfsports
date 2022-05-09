@extends('cms::layouts.dashboard')

@section('pageTitle') Venue @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('venues::venues.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
        {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.venues.store', 'venues' => true, 'class' => 'add']); !!}

            {!! FormMaker::fromTable('venues', Cms::moduleConfig('venues', 'venues')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/venues') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
