@extends('cms::layouts.dashboard')

@section('pageTitle') Stat @stop

@section('content')

    <div class="col-md-12">
        @include('stats::stats.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($stat, ['route' => [config('cms.backend-route-prefix', 'cms').'.stats.update', $stat->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($stat, FormMaker::getTableColumns('stats')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/stats') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


