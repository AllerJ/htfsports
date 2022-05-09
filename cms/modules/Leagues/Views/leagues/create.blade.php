@extends('cms::layouts.dashboard')

@section('pageTitle') League @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('leagues::leagues.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
        {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.leagues.store', 'leagues' => true, 'class' => 'add']); !!}

            {!! FormMaker::fromTable('leagues', Cms::moduleConfig('leagues', 'leagues')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/leagues') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
