@extends('cms::layouts.dashboard')

@section('pageTitle') Roster @stop

@section('content')

    <div class="col-md-12">
        @include('rosters::rosters.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($roster, ['route' => [config('cms.backend-route-prefix', 'cms').'.rosters.update', $roster->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($roster, FormMaker::getTableColumns('rosters')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/rosters') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


