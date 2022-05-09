@extends('cms::layouts.dashboard')

@section('pageTitle') Player @stop

@section('content')

    <div class="col-md-12">
        @include('players::players.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12">            
        {!! Form::model($player, ['route' => [config('cms.backend-route-prefix', 'cms').'.players.update', $player->id], 'method' => 'patch', 'class' => 'edit']) !!}

            {!! FormMaker::fromObject($player, FormMaker::getTableColumns('players')) !!}

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/players') !!}" class="btn btn-default raw-left">Cancel</a>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection


