@extends('cms::layouts.dashboard')

@section('pageTitle') Owner @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('owners::owners.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
        {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.owners.store', 'owners' => true, 'class' => 'add']); !!}

            {!! FormMaker::fromTable('owners', Cms::moduleConfig('owners', 'owners')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/owners') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
