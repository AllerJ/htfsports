@extends('cms::layouts.dashboard')

@section('pageTitle') Game @stop

@section('content')

    <div class="col-md-12">
        @include('games::games.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($game, ['route' => [config('cms.backend-route-prefix', 'cms').'.games.update', $game->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($game, FormMaker::getTableColumns('games')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/games') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


