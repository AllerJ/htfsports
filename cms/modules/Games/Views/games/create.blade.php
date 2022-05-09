@extends('cms::layouts.dashboard')

@section('pageTitle') Game @stop

@section('content')
<?php
$rand = substr(str_shuffle(str_repeat("ABCDEFGHIJKLMNPQRSTUVWXYZ", 3)), 0, 3);
$array = ['notes'=>'', 'game_code' => $rand, 'artwork'=>''];
$object = (object) $array;
?>

    <div class="col-md-12 mt-3">
        @include('games::games.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
    <h2>Set Up A Game</h2>
        <div class="mt-4">
            {!! Form::open(['route' => config('cms.backend-route-prefix', 'cms').'.games.store', 'games' => true, 'class' => 'add', 'files' => true]); !!}
    
                {!! FormMaker::setColumns(3)->fromTable('games', config('cms.modules.games.forms.game_setup.game_time')) !!}
                {!! FormMaker::setColumns(1)->fromObject($object, config('cms.modules.games.forms.game_setup.game_details')) !!}
    
                <hr>
    
                <h2>Select Existing or Create a Venue</h2>
                
                <div class="row pb-5">
                    <div class="col-12">
                        <label class="control-label" for="prevenue">Previous Venues</label>
                <select name="prevenue" id="prevenue" class="form-control">
                        <option value="0">Create New Venue - Use Form Below</option>
                    @foreach($venues as $venue)
                        <option value="{!! $venue->id !!}">{!! $venue->name !!} - {!! $venue->address !!}, {!! $venue->city !!}</option>
                    @endforeach
                </select>
                    </div>
                </div>                   
                {!! FormMaker::setColumns(2)->fromTable('games', config('cms.modules.games.forms.game_setup.venue.identity')) !!}
                {!! FormMaker::setColumns(1)->fromTable('games', config('cms.modules.games.forms.game_setup.venue.address')) !!}
                {!! FormMaker::setColumns(3)->fromTable('games', config('cms.modules.games.forms.game_setup.venue.csz')) !!}
    
                <div class="form-group text-right">
                    <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/games') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                    {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
                </div>
    
            {!! Form::close() !!}
        </div>
    </div>

@endsection
