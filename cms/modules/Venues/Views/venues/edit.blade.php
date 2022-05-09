@extends('cms::layouts.dashboard')

@section('pageTitle') Venue @stop

@section('content')

    <div class="col-md-12">
        @include('venues::venues.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($venue, ['route' => [config('cms.backend-route-prefix', 'cms').'.venues.update', $venue->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($venue, FormMaker::getTableColumns('venues')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/venues') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


