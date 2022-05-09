@extends('cms::layouts.dashboard')

@section('pageTitle') Stat @stop

@section('content')

<style>
    .stat_input {
        text-align: center;
        width:75px;
    }
    .input-group-text {
        width:128px;
    }
</style>
    <div class="col-md-12 mt-3">
        @include('stats::stats.breadcrumbs', ['location' => ['edit']])
    </div>

    <div class="col-md-12"> 
    
    <h3>{!! date_format($game->game_at, 'm/d/Y') !!} - {!! $game->venue->name !!} <!-- <a href="/cms/stats/{{ $game->id }}/fetch" class="btn btn-sm btn-outline-primary ml-5"><i class="fa fa-plus-square"></i> Update Stats</a> --></h3> 
    
    @foreach($players as $player)

    <div class="row  align-items-center">
        <div class="col-sm-1">
            <img alt="{{ $player->player->team->name }}" data-test-id="facemask-image-container" height="48" sizes="100vw" src="https://static.nfl.com/static/site/img/logos/svg/teams/{{$player->player->team->alias}}.svg" width="48" data-radium="true" style="border: 0px; display: block; max-width: 100%;">
        </div>
        <div class="col-sm-1">
            @if(isset($player->player->headshot->headshot))
                <img src="{!! $player->player->headshot->headshot !!}" class="img-fluid">   <br>
            @endif
        </div>

        <div class="col-sm-4 text-center">        
             <span class="numbers font-20 pull-left">{{ $player->player->jersey }}</span> {{ $player->player->abbr_name }} {{ $player->player->last_name }}
        </div>
        

        @if($player->player->position == 'QB')
        <div class="col-sm-3">

            <div class="input-group ">
                <div class="input-group-prepend">
                    <span class="input-group-text {{ $player->player_id . '_tt'  }}">Touchdowns &nbsp; 
                    @if(player_stat_manual($player->player_id, $player->game_id, 'tt') == "1")
						<i class="fa fa-hand-lizard-o"></i></span>
                    @endif
                </div>
                <input type="number" value="{{ player_stat($player->player_id, $player->game_id, 'tt') }}" class="stat_input" data-stat='tt' data-player={{$player->player_id}}  name="stat" id="tt_{{$player->player_id}}" class="form-control">
            </div>
        </div>
        @else
            @foreach(player_draft($player->player_id, $player->game_id) as $stat_type)
            <?php $level = explode('_', $stat_type->level_id); ?>
                @if($level[0] == 're')
                <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text {{ $player->player_id . '_re'  }}">Reception &nbsp;
                                @if(player_stat_manual($player->player_id, $player->game_id, 're') == "1")
			                    <i class="fa fa-hand-lizard-o"></i></span>
			                    @endif
                                </span>
                            </div>
                            <input type="number" value="{{ player_stat($player->player_id, $player->game_id, 're') }}" class="stat_input" data-stat='re' data-player={{$player->player_id}}  name="stat" id="re_{{$player->player_id}}" class="form-control">
                        </div>
                </div>
                @endif
                @if($level[0] == 'ay')
                <div class="col-sm-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text {{ $player->player_id . '_ay'  }}">Yards &nbsp; 
                                @if(player_stat_manual($player->player_id, $player->game_id, 'ay') == "1")
				                    <i class="fa fa-hand-lizard-o"></i></span>
			                    @endif
                                </span>
                            </div>
                            <input type="number" value="{{ player_stat($player->player_id, $player->game_id, 'ay') }}" class="stat_input" data-stat='ay' data-player={{$player->player_id}}  name="stat" id="ay_{{$player->player_id}}" class="form-control">
                        </div>
                </div>
                @endif
            @endforeach        
        @endif

    </div>

    <hr>
    @endforeach
    
    
    
    
    
    
    
    
    

@endsection


@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="/css/jquery.growl.css">
@endsection
@section('javascript')
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