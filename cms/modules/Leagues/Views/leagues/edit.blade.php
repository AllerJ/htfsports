@extends('cms::layouts.dashboard')

@section('pageTitle') League @stop

@section('content')

    <div class="col-md-12">
        @include('leagues::leagues.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($league, ['route' => [config('cms.backend-route-prefix', 'cms').'.leagues.update', $league->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($league, FormMaker::getTableColumns('leagues')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/leagues') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


