@extends('cms::layouts.dashboard')

@section('pageTitle') Team @stop

@section('content')

    <div class="col-md-12">
        @include('teams::teams.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($team, ['route' => [config('cms.backend-route-prefix', 'cms').'.teams.update', $team->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($team, FormMaker::getTableColumns('teams')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/teams') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


