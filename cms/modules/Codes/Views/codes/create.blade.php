@extends('cms::layouts.dashboard')

@section('pageTitle') Code @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('codes::codes.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
        {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.codes.store', 'codes' => true, 'class' => 'add']); !!}

            {!! FormMaker::fromTable('codes', Cms::moduleConfig('codes', 'codes')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/codes') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
