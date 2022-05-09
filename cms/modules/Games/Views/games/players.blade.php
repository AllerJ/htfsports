@extends('cms::layouts.dashboard')

@section('pageTitle') Game @stop

@section('content')


    <div class="col-md-12 mt-3">
        @include('games::games.breadcrumbs', ['location' => ['edit']])
    </div>
    <div class="col-sm-12">
        <h3>Players Playing on {{ $gameDay }}</h3>
        Deactivate Players by unchecking the box by their name.
    </div>
    <div class="col-md-12 mt-5"> 
        {!! Form::open(['route' => ['games.saveplayers', $game->id], 'games' => true, 'class' => 'add']); !!}
        @foreach($game->teams as $team) 
                


        <table class="table table-striped">
            
            <tbody>
            @foreach($team->players($game->id)->get() as $player)
                @if($player->position_name->subcode == '1')

                <tr>
                    <td>
                        <input type="checkbox" 
                        @if($player->stats && $player->active == "N" || $player->active == "1")
                            checked="checked"
                        @endif
                        name="player[]" value="{!! $player->id !!}" data-toggle="toggle" data-onstyle="success" data-size="small" data-on="Active" data-off="Inactive">
                    </td>
                    <td>
                    
                    
                        <div class="row">
                            <div class="col-4 align-self-center">
                            <strong>{!! $player->abbr_name !!} {!! $player->last_name !!}</strong> - {!! $player->position_name->description !!}<br>
                                
                                @if($app_review == true)
                                <img src="https://api.htfsports.com/img/player.php?color={{ $player->team->color_second }}" class="img-fluid">   <br>
                                @elseif(isset($player->espn_id))
                                <img src="{!! $player->espn_id !!}" class="img-fluid">   <br>
                                @else
                                <img src="https://api.htfsports.com/img/player.php?color={{ $player->team->color_second }}" class="img-fluid">   <br>
                                @endif
                                
                                
                                
                            </div>
                            <div class="col-8">
                                
                       
                            
                                @if($player->stats)
                            
                                
<?php
    $rushing_yds = 0;
    $receiving_yds = 0;
    $kick_yds = 0;
    $punt_yds = 0;
    
    if(isset($player->stats['rushing']['yards'])) {
        $rushing_yds = $player->stats['rushing']['yards'];
    }
    if(isset($player->stats['receiving']['yards'])) {
        $receiving_yds = $player->stats['receiving']['yards'];
    }
    if(isset($player->stats['kick_returns']['yards'])) {
        $kick_yds = $player->stats['kick_returns']['yards'];
    }
    if(isset($player->stats['punt_returns']['yards'])) {
        $punt_yds = $player->stats['punt_returns']['yards'];
    }
    $all_yds = $kick_yds + $receiving_yds + $rushing_yds + $punt_yds;
?>
                                <table class="table table-striped text-left table-sm font_12">
                                   <tbody>
	                                   <tr>
                                            <td scope="row" colspan="2">{!! $player->team->name !!}</td>
                                        </tr>
                                        
                                        <?php
	                                        
											if($player->injury){
											?>
											
												<tr>
		                                            <td scope="row" colspan="2">{!! $player->injury !!}</td>
		                                        </tr>
                                        
                                        <?php
											}	                                        
	                                        
                                        ?>
                                        
                                        <tr>
                                            <td scope="row">Jersey Number</td>
                                            <td>#{!! $player->jersey !!}</td>
                                        </tr>
                                        <tr>
                                            <td scope="row">Games Played</td>
                                            <td>{!! $player->stats['gamesPlayed'] !!}</td>
                                        </tr>
                                        @if(isset($player->stats['passing']['touchdowns']))
                                        <tr>
                                            <td scope="row">Passing Touchdowns</td>
                                            <td>{!! $player->stats['passing']['touchdowns'] !!}</td>
                                        </tr>
                                        @endif
                                        @if(isset($player->stats['receiving']['receptions']))
                                        <tr>
                                            <td scope="row">Total Receptions</td>
                                            <td>{!! $player->stats['receiving']['receptions'] !!}</td>
                                        </tr>
                                        @endif
                                        
                                        @if($rushing_yds > 0)
                                        <tr>
                                            <td scope="row">Rushing Yards</td>
                                            <td>{!! $rushing_yds !!}</td>
                                        </tr>
                                        @endif
                                        @if($receiving_yds > 0)
                                        <tr>
                                            <td scope="row">Receiving Yards</td>
                                            <td>{!! $receiving_yds !!}</td>
                                        </tr>
                                        @endif
                                        @if($kick_yds > 0)
                                        <tr>
                                            <td scope="row">Kick Yards</td>
                                            <td>{!! $kick_yds !!}</td>
                                        </tr>
                                        @endif
                                        @if($punt_yds > 0)
                                        <tr>
                                            <td scope="row">Punt Yards</td>
                                            <td>{!! $punt_yds !!}</td>
                                        </tr>
                                        @endif
                                        @if($all_yds > 0)
                                        <tr>
                                            <td scope="row">All Purpose Yards</td>
                                            <td>{!! $all_yds !!}</td>
                                        </tr>
                                        @endif
                                   </tbody>
                                </table>           
                                @endif                 
                                                            
                            </div>
                        </div>
                            
                    </td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>
      <hr>  
        @endforeach





           

            <div class="form-group text-right">
                <a href="{!! url(config('cms.backend-route-prefix', 'cms').'/games') !!}" class="btn btn-outline-primary raw-left">Cancel</a>
                {!! Form::submit('Finish', ['class' => 'btn btn-primary']) !!}
            </div>

        {!! Form::close() !!}
    </div>

@endsection
@section("stylesheets")
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<style>
    .toggle-group 


.btn-hfs { 
  color: #ffffff; 
  background-color: #499E44; 
  border-color: #130269; 
} 
 
.btn-hfs:hover, 
.btn-hfs:focus, 
.btn-hfs:active, 
.btn-hfs.active, 
.open .dropdown-toggle.btn-hfs { 
  color: #ffffff; 
  background-color: #22385A; 
  border-color: #130269; 
} 
 
.btn-hfs:active, 
.btn-hfs.active, 
.open .dropdown-toggle.btn-hfs { 
  background-image: none; 
} 
 
.btn-hfs.disabled, 
.btn-hfs[disabled], 
fieldset[disabled] .btn-hfs, 
.btn-hfs.disabled:hover, 
.btn-hfs[disabled]:hover, 
fieldset[disabled] .btn-hfs:hover, 
.btn-hfs.disabled:focus, 
.btn-hfs[disabled]:focus, 
fieldset[disabled] .btn-hfs:focus, 
.btn-hfs.disabled:active, 
.btn-hfs[disabled]:active, 
fieldset[disabled] .btn-hfs:active, 
.btn-hfs.disabled.active, 
.btn-hfs[disabled].active, 
fieldset[disabled] .btn-hfs.active { 
  background-color: #499E44; 
  border-color: #130269; 
} 
 
.btn-hfs .badge { 
  color: #499E44; 
  background-color: #ffffff; 
}


</style>

@endsection
@section("javascript")

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    
@endsection