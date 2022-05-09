<?php $total_points = 0 ?>


@extends('cms::layouts.dashboard')

@section('pageTitle') Roster For @stop

@section('content')

<style>
	.blue_background {
    background-color: #34598F;
    color:#fff;
	}
	.gray_background {
	    background-color: #dbdbdb;
	    color:#34598F;
	}
	.green_background {
	    background-color: #18BC9C;
	    color:#fff;
	}
	.headshot {
		border-radius: 100%;
		width: 50px;
		height: 50px;
	}
	.input-group input {
		text-align: center;
		width: 40px;
	}
	.input-group-text {
		min-width: 75px;
	}
</style>
 	<div class="col-12">
	    <h4> {!! $owner->full_name !!} <strong> <span class="score-holder"></span></strong>
		    
		    <a class="btn btn-success pull-right" href="/cms/games/{!! $game->id !!}/leaderboard"><i class="fa fa-arrow-left"></i> Back</a>
		    
	    </h4>
	    
	    
	    
	    <h5 class="">Throwing Touchdowns</h5>
        <table class="table table-striped">
            <thead>
	            
                <th colspan="2">Player</th>
                <th>Manual Stat</th>
                <th>Current</th>
                <th>TDS</th>
                <th>Points</th>
            </thead>
            <tbody>

 @foreach($codes_tt as $code)
    <?php
    $roster_player = $owner->roster->where('level_id', '=', $code->code)->where('game_id', '=', $game->id)->first();
    $need = str_replace(' TDS', '', $code->extra);
    if($roster_player) {
        if($roster_player->current_stat >= $need) {
            $row_color = "green_background";
            $total_points = $total_points + $code->more;
        } else {
            $row_color = "gray_background";
        }
    }
    ?>
    
            <tr class="{!! $row_color !!}">
                <td width="10%" class="{{ $row_color }} right_line">
	                
	                @if( $roster_player->player->espn_id)
		            <img src="{!! $roster_player->player->espn_id !!} " class="headshot" style="background-color: {!! $roster_player->player->team->color !!};">
		            @else
		            <img src="/img/player.php?color={!! $roster_player->player->team->second_color !!} " class="headshot" style="background-color: {!! $roster_player->player->team->color !!};">
		            @endif
		            
                </td>
                <td width="30%" class="{{ $row_color }} right_line">
                   {{ $roster_player->player->abbr_name.' '.$roster_player->player->last_name }}
                </td>
                <td width="30%" class="{{ $row_color }} right_line">

			
				<div class="input-group">
					<div class="input-group-prepend">
						
						
						<span class="input-group-text {{ $roster_player->player_id . '_tt'  }}" id="{{ 'addon' . $roster_player->player_id }}">TD &nbsp;
						@if(player_stat_manual($roster_player->player_id, $roster_player->game_id, 'tt') == "1")
							<i class="fa fa-hand-lizard-o"></i>
	                    @endif
						</span>
	
					</div>
					<input type="number" value="{{ player_stat($roster_player->player_id, $roster_player->game_id, 'tt') }}" class="stat_input" data-stat='tt' data-player={{$roster_player->player_id}}  name="stat" id="tt_{{$roster_player->player_id}}" class="form-control" aria-describedby="{{ 'addon' . $roster_player->player_id }}">
				</div>
			
			            			                
                </td>
                <td width="10%" class="{{ $row_color }} right_line">
                    {{ $roster_player->current_stat }}
                </td>
                <td width="10%" class="{{ $row_color }} right_line">
                    {{ $need }}
                </td>
                <td width="10%" class="{{ $row_color }} ">
                    {{ $code->more }}
                </td>
            </tr>
        
        @endforeach


            </tbody>
        </table>
    

	<h5 class="">All Purpose Yards</h5>
        <table class="table table-striped">
            <thead>
                <th colspan="2">Player</th>
                <th>Manual Stat</th>
                <th>Current</th>
                <th>TDS</th>
                <th>Points</th>
            </thead>
            <tbody>

@foreach($codes_ay as $code)
    <?php
    $roster_player = $owner->roster->where('level_id', '=', $code->code)->where('game_id', '=', $game->id)->first();
    $need = str_replace(' YDS', '', $code->extra);
    if($roster_player) {
        if($roster_player->current_stat >= $need) {
            $row_color = "green_background";
            $total_points = $total_points + $code->more;
        } else {
            $row_color = "gray_background";
        }
    }
    ?>
    
            <tr class="{!! $row_color !!}">
                <td width="10%" class="{{ $row_color }} right_line">
	                
	                @if( $roster_player->player->espn_id)
		            <img src="{!! $roster_player->player->espn_id !!} " class="headshot" style="background-color: {!! $roster_player->player->team->color !!};">
		            @else
		            <img src="/img/player.php?color={!! $roster_player->player->team->second_color !!} " class="headshot" style="background-color: {!! $roster_player->player->team->color !!};">
		            @endif
		            
                </td>
                <td width="30%" class="{{ $row_color }} right_line">
                   {{ $roster_player->player->abbr_name.' '.$roster_player->player->last_name }}
                </td>
                <td width="30%" class="{{ $row_color }} right_line">

			
				<div class="input-group">
					<div class="input-group-prepend">
						
						
						<span class="input-group-text {{ $roster_player->player_id . '_ay'  }}" id="{{ 'addon' . $roster_player->player_id }}">YD &nbsp;
						@if(player_stat_manual($roster_player->player_id, $roster_player->game_id, 'ay') == "1")
							<i class="fa fa-hand-lizard-o"></i>
	                    @endif
						</span>
	
					</div>
					<input type="number" value="{{ player_stat($roster_player->player_id, $roster_player->game_id, 'ay') }}" class="stat_input" data-stat='ay' data-player={{$roster_player->player_id}}  name="stat" id="ay_{{$roster_player->player_id}}" class="form-control" aria-describedby="{{ 'addon' . $roster_player->player_id }}">
				</div>
			
			            			                
                </td>
                <td width="10%" class="{{ $row_color }} right_line">
                    {{ $roster_player->current_stat }}
                </td>
                <td width="10%" class="{{ $row_color }} right_line">
                    {{ $need }}
                </td>
                <td width="10%" class="{{ $row_color }} ">
                    {{ $code->more }}
                </td>
            </tr>
        
        @endforeach
        </tbody>
    </table>



	<h5 class="">Receptions</h5>
        <table class="table table-striped">
            <thead>
                <th colspan="2">Player</th>
                <th>Manual Stat</th>
                <th>Current</th>
                <th>RECS</th>
                <th>Points</th>
            </thead>
            <tbody>
	
    @foreach($codes_re as $code)
    <?php
    $roster_player = $owner->roster->where('level_id', '=', $code->code)->where('game_id', '=', $game->id)->first();
    $need = str_replace(' RECS', '', $code->extra);
    if($roster_player) {
        if($roster_player->current_stat >= $need) {
            $row_color = "green_background";
            $total_points = $total_points + $code->more;
        } else {
            $row_color = "gray_background";
        }
    }
    ?>
    
            <tr class="{!! $row_color !!}">
                <td width="10%" class="{{ $row_color }} right_line">
	                
	                @if( $roster_player->player->espn_id)
		            <img src="{!! $roster_player->player->espn_id !!} " class="headshot" style="background-color: {!! $roster_player->player->team->color !!};">
		            @else
		            <img src="/img/player.php?color={!! $roster_player->player->team->second_color !!} " class="headshot" style="background-color: {!! $roster_player->player->team->color !!};">
		            @endif
		            
                </td>
                <td width="30%" class="{{ $row_color }} right_line">
                   {{ $roster_player->player->abbr_name.' '.$roster_player->player->last_name }}
                </td>
                <td width="30%" class="{{ $row_color }} right_line">

			
				<div class="input-group">
					<div class="input-group-prepend">
						
						
						<span class="input-group-text {{ $roster_player->player_id . '_re'  }}" id="{{ 'addon' . $roster_player->player_id }}">RE &nbsp;
						@if(player_stat_manual($roster_player->player_id, $roster_player->game_id, 're') == "1")
							<i class="fa fa-hand-lizard-o"></i>
	                    @endif
						</span>
	
					</div>
					<input type="number" value="{{ player_stat($roster_player->player_id, $roster_player->game_id, 're') }}" class="stat_input" data-stat='re' data-player={{$roster_player->player_id}}  name="stat" id="re_{{$roster_player->player_id}}" class="form-control" aria-describedby="{{ 'addon' . $roster_player->player_id }}">
				</div>
			
			            			                
                </td>
                <td width="10%" class="{{ $row_color }} right_line">
                    {{ $roster_player->current_stat }}
                </td>
                <td width="10%" class="{{ $row_color }} right_line">
                    {{ $need }}
                </td>
                <td width="10%" class="{{ $row_color }} ">
                    {{ $code->more }}
                </td>
            </tr>
        
        @endforeach
        </tbody>
    </table>
 	</div>

@endsection


@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.growl.css">
@endsection


@section('javascript')

    @parent
	<script>
	    $(".score-holder").text('Score: {{ $total_points }}')
	</script>
    <script src="/js/jquery.growl.js"></script>
	<script>
	    $(document).ready(function(){
	       
	        $(".stat_input").focusout(function() {
	            var stat = $(this).val();
	            var stat_type = $(this).attr("data-stat");
	            var player_id = $(this).attr("data-player");
	            var game_id = {!! $game->id !!};
	            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	            
	            var postedData =  {_token: CSRF_TOKEN, stat_type:stat_type, stat:stat, player_id:player_id, game_id:game_id};      
	
	            if(stat != "") {
	                $.ajax({
	                    url: '/cms/stats',
	                    type: 'POST',
	                    data: postedData,
	                    dataType: 'json',
	                    success: function (data) { 
	                        console.log(data);
	                        $.growl.notice({ title: 'SCORE!', message: data.message });
	                    }
	                });                 
	    			$( "." + player_id + '_' + stat_type ).append(  "<i class=\"fa fa-hand-lizard-o\"></i>" );
	
	            }
	
	            
	        });
	    });
	</script>
    
@endsection




