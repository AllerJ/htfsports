@extends('cms::layouts.dashboard')

@section('pageTitle') Owner @stop

@section('content')

    <div class="col-md-12">
        @include('owners::owners.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($owner, ['route' => [config('cms.backend-route-prefix', 'cms').'.owners.update', $owner->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($owner, FormMaker::getTableColumns('owners')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/owners') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


