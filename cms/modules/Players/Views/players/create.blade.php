@extends('cms::layouts.dashboard')

@section('pageTitle') Player @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('players::players.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
        {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.players.store', 'players' => true, 'class' => 'add']); !!}

            {!! FormMaker::fromTable('players', Cms::moduleConfig('players', 'players')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/players') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
