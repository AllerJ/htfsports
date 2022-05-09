@extends('cms-frontend::layout.image')

@section('content')
<style>
	.player {
		border-bottom:solid 1px #c0c0c0;
		margin-bottom:20px;
	}
	
	.headshot {
	  position: relative;
	  width: 100%;
	  height: 250px;
	  overflow: hidden;
	}
	
	.headshot img{
	  position: absolute;
	  top: -9999px;
	  left: -9999px;
	  right: -9999px;
	  bottom: -9999px;
	  margin: auto;
	}

	.btn {
		border-radius: 0;
	}

</style>


<div class="input-group mb-3">
  <div class="input-group-prepend">
    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-sort-alpha-down"></i>
      <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu">
      <a class="dropdown-item" href="#" id="by_team">Team</a>
      <a class="dropdown-item" href="#" id="by_lastname">Last Name</a>
	  <a class="dropdown-item" href="#" id="by_tds">Average Touchdowns</a>
      <a class="dropdown-item" href="#" id="by_yrds">Average Yards</a>
      <a class="dropdown-item" href="#" id="by_recp">Average Receptions</a>
    </div>
    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
  </div>
    <input type="text" class="form-control" id="filter" placeholder="Search Name">
</div>

<div class=" text-center player-stats" style="width:100%" id="{{ $code->code }}">
	@foreach($players as $player)
<?php

if($player['games_played']) {
	$played = $player['games_played'];
	$yrds = $player['all_yards'];
	$tds = $player['ptd'];
	$recp = $player['receptions'];	

	$avg_yrds = round($yrds/$played, 2);
	$avg_tds = round($tds/$played, 2);
	$avg_recp = round($recp/$played, 2);	
} else {
	$avg_yrds = 0;
	$avg_tds = 0;
	$avg_recp = 0;
}

	
?>
	
	
	<div class="row player" data-player="{!! $player['first_name'] !!} {!! $player['last_name'] !!} {!! $player['team'] !!}" data-lastname="{!! $player['last_name'] !!}" data-team="{!! $player['team'] !!}" data-tds="{!! $avg_tds !!}" data-recp="{!! $avg_recp !!}" data-yrds="{!! $avg_yrds !!}">
				
				<div class="col-12 pb-3 font-20">
					<img alt="{{ $player['team'] }}" data-test-id="facemask-image-container" height="35" src="{{ $player['logo'] }}"  data-radium="true"> <strong>{!! $player['first_name'] !!} {!! $player['last_name'] !!}</strong>
	
					
	
				</div>
	
                <div class="col-6  less-right">
                    @if($player['headshot'])
                    <div class="headshot">
                    <img src="{!! $player['headshot'] !!}" class="iimg-fluid">
                    </div>

                    @endif
                    <a href="/games/draft_pick/{{ $code->code }}/{!! $player['player_id'] !!}" class="btn btn-block btn-green mt-3 draft_player" >Draft</a>             
                </div>
                <div class="col-6 less-left">
                    
       

                    <table class="table table-striped text-left table-sm font_12">
                       <tbody>
                        <tr>
                            <td scope="row">Team</td>
                            <td>{!! $player['team'] !!}</td>
                            </tr>
                            <td colspan="2" class="text-center">
                                {!! $player['position'] !!}
                            </td>
                            </tr>
                            <tr>
                                <td scope="row">Jersey Number</td>
                                <td>#{!! $player['jersey'] !!}</td>
                            </tr>
                            <td scope="row" colspan="2" class="text-center">Opponent <img alt="{{ $player['opponent'] }}" data-test-id="facemask-image-container" height="25" src="{!! $player['opponent_logo'] !!}"  data-radium="true"> {!! $player['opponent'] !!}</td>
                            </tr>

                            
                            <tr>
                            	<td colspan="2" class=" bold text-center blue_background">
                            		Per Game Averages
                            	</td>
                            </tr>
                            <tr>
                                <td scope="row" class="blue_background line_right">Touchdowns</td>
                                <td class="green_background bold">{!! $avg_tds !!}</td>
                            </tr>
                            <tr>
                                <td scope="row" class="blue_background line_right">All Purpose Yards</td>
                                <td class="green_background bold">{!! $avg_yrds !!}</td>
                            </tr>
                            <tr>
                                <td scope="row" class="blue_background line_right">Receptions</td>
                                <td class="green_background bold">{!! $avg_recp !!}</td>
                            </tr>
                                                        <tr>
                                <td scope="row">Games Played</td>
                                <td>{!! $player['games_played'] !!}</td>
                            </tr>
                       </tbody>
                    </table>                              
                </div>
            </div>
 
             @endforeach  
              
  </div>
	<div class="col">
	    <br /><br /><br /> 
		<a href="/games/{{ $code->expire }}" class="btn btn-blue btn-block">Return</a>
<br /><br /><br />
	</div>
  
  @endsection
  

@section('cms')
    @edit('games', $game->id)
@endsection

@section('javascript')
<script>
$("#filter").keyup(function(){
    	var selectSize = $(this).val();
        filter(selectSize);
    });
    function filter(e) {
        var regex = new RegExp('\\b\\w*' + e + '\\w*\\b', 'i');
        $('.player').hide().filter(function () {
            return regex.test($(this).data('player'))
        }).show();
    }	

$('#by_team').click(function() {
	$('.player').sort(function(a, b) {
	  if (a.dataset.team < b.dataset.team) {
	    return -1;
	  } else {
	    return 1;
	  }
	}).appendTo('.player-stats');	
});
$('#by_lastname').click(function() {
	$('.player').sort(function(a, b) {
	  if (a.dataset.lastname < b.dataset.lastname) {
	    return -1;
	  } else {
	    return 1;
	  }
	}).appendTo('.player-stats');	
});
$('#by_yrds').click(function() {
	$('.player').sort(function(a, b) {
	  var bdiv = Number(b.dataset.yrds);
	  var adiv = Number(a.dataset.yrds);
	  if (bdiv < adiv) {
	    return -1;
	  } else {
	    return 1;
	  }
	}).appendTo('.player-stats');	
});
$('#by_tds').click(function() {
	$('.player').sort(function(a, b) {
	  var bdiv = Number(b.dataset.tds);
	  var adiv = Number(a.dataset.tds);
	  if (bdiv < adiv) {
	    return -1;
	  } else {
	    return 1;
	  }
	}).appendTo('.player-stats');	
});
$('#by_recp').click(function() {
	$('.player').sort(function(a, b) {
	  var bdiv = Number(b.dataset.recp);
	  var adiv = Number(a.dataset.recp);
	  if (bdiv < adiv) {
	    return -1;
	  } else {
	    return 1;
	  }
	}).appendTo('.player-stats');	
});
</script>
	
@endsection