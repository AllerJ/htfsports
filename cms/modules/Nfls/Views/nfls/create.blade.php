@extends('cms::layouts.dashboard')

@section('pageTitle') Nfl @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('nfls::nfls.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
        {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.nfls.store', 'nfls' => true, 'class' => 'add']); !!}

            {!! FormMaker::fromTable('nfls', Cms::moduleConfig('nfls', 'nfls')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/nfls') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
