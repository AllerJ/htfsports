@extends('cms::layouts.dashboard')

@section('pageTitle') Team @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('teams::teams.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
        {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.teams.store', 'teams' => true, 'class' => 'add']); !!}

            {!! FormMaker::fromTable('teams', Cms::moduleConfig('teams', 'teams')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/teams') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
