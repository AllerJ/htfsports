@extends('cms::layouts.dashboard')

@section('pageTitle') Roster @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('rosters::rosters.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
        {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.rosters.store', 'rosters' => true, 'class' => 'add']); !!}

            {!! FormMaker::fromTable('rosters', Cms::moduleConfig('rosters', 'rosters')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/rosters') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
