@extends('cms::layouts.dashboard')

@section('pageTitle') Nfl @stop

@section('content')

    <div class="col-md-12">
        @include('nfls::nfls.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($nfl, ['route' => [config('cms.backend-route-prefix', 'cms').'.nfls.update', $nfl->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($nfl, FormMaker::getTableColumns('nfls')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/nfls') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


