@extends('cms::layouts.dashboard')

@section('pageTitle') Game @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('games::games.breadcrumbs', ['location' => ['edit']])
    </div>
    <div class="col-sm-12">
        <h3>Teams Playing on {{ $gameDay }}</h3>
        
        Select the checkbox next to the teams to include
    </div>
    <div class="col-md-12 mt-5"> 
        {!! Form::open(['route' => ['games.saveteams', $game->id], 'games' => true, 'class' => 'add']); !!}
        

            @foreach($daySchedule as $team)
            <div class="row">
                <div class="col-sm-12">
                {{ $team->schedule_at->timezone('America/New_York')->format('M d, Y g:i A') }} at {{ $team->venue }} <br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                <br>
                    <input type="checkbox" name="schedule_id[]" value="{{ $team->schedule_id}}" data-toggle="toggle" data-onstyle="success" data-size="small" data-on="Include" data-off="Not Included">
                </div>
                
                <div class="col-sm-3">
                    {{ $team->visitorTeam->market }} {{ $team->visitorTeam->name }}
                    <br>
                    <img alt="{{ $team->visitorTeam->name }}" data-test-id="facemask-image-container" height="48" sizes="100vw" src="{{$team->visitorTeam->logo}}" width="48" data-radium="true" style="border: 0px; display: block; max-width: 100%;">
    
                </div>
                <div class="col-sm-3">
                    <b>@</b> {{ $team->homeTeam->market }} {{ $team->homeTeam->name }}
                    <br>
                    <img alt="{{ $team->homeTeam->name }}" data-test-id="facemask-image-container" height="48" sizes="100vw" src="{{$team->homeTeam->logo}}" width="48" data-radium="true" style="border: 0px; display: block; max-width: 100%;">
                    
    
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>    
            @endforeach

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/games') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Next', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
@section("stylesheets")
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<style>
 
.btn-default { 
  color: #FFFFFF; 
  background-color: #130269; 
  border-color: #499E44; 
} 
 
.btn-success { 
  color: #FFFFFF; 
  background-color: #499E44; 
  border-color: #499E44; 
} 
 
</style>

@endsection
@section("javascript")

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    
@endsection