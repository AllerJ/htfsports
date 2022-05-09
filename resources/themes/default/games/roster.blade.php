@extends('cms-frontend::layout.master')

@section('content')
<?php $total_points = 0 ?>
<div class="container-fluid mt-3 text-center">

@if(owner('owner_id') == $owner->id)
    <h3>Your Roster</h3>
    @else
    <h3>Roster Picks for<br>{{ $owner->full_name}}</h3>
    @endif
    
    <div class="row mt-3">
        <div class="col-12 text-center uppercase">
            Throwing Touchdowns
        </div>
        <div class="col-6 blue_background  text-center">
            <span class="font_8 uppercase mb-0">Player</span>
        </div>
        <div class="col-2 blue_background  text-center">
            <span class="font_8 uppercase mb-0">Current</span>
        </div>

        <div class="col-2 blue_background  text-center">
            <span class="font_8 uppercase mb-0">TDS</span>
        </div>
        <div class="col-2 blue_background text-center">
            <span class="font_8 uppercase mb-0">Points</span>
        </div>
    </div>
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

    <div class="row font_12">
        <div class="col-6 text-center {{ $row_color }} right_line">
            {{ $roster_player->player->abbr_name.' '.$roster_player->player->last_name }}
        </div>
        <div class="col-2 text-center {{ $row_color }} right_line">
            {{ $roster_player->current_stat }}
        </div>
        <div class="col-2 text-center {{ $row_color }} right_line">
            {{ $need }}
        </div>
        <div class="col-2 {{ $row_color }} text-center">
            {{ $code->more }}
        </div>  
    </div>

    
    @endforeach

    
    <div class="row mt-3">
        <div class="col-12 text-center uppercase">
            All Purpose Yards
        </div>
        <div class="col-6 blue_background  text-center">
            <span class="font_8 uppercase mb-0">Player</span>
        </div>
        <div class="col-2 blue_background  text-center">
            <span class="font_8 uppercase mb-0">Current</span>
        </div>

        <div class="col-2 blue_background  text-center">
            <span class="font_8 uppercase mb-0">YDS</span>
        </div>
        <div class="col-2 blue_background text-center">
            <span class="font_8 uppercase mb-0">Points</span>
        </div>
    </div>
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

    <div class="row font_12">
        <div class="col-6 text-center {{ $row_color }} right_line">
            {{ $roster_player->player->abbr_name.' '.$roster_player->player->last_name }}
        </div>
        <div class="col-2 text-center {{ $row_color }} right_line">
            {{ $roster_player->current_stat }}
        </div>
        <div class="col-2 text-center {{ $row_color }} right_line">
            {{ $need }}
        </div>
        <div class="col-2 {{ $row_color }} text-center">
            {{ $code->more }}
        </div>  
    </div>

    
    @endforeach        
        
        
        
        
        
        
    <div class="row mt-3">
        <div class="col-12 text-center uppercase">
            Receptions
        </div>
        <div class="col-6 blue_background  text-center">
            <span class="font_8 uppercase mb-0">Player</span>
        </div>
        <div class="col-2 blue_background  text-center">
            <span class="font_8 uppercase mb-0">Current</span>
        </div>

        <div class="col-2 blue_background  text-center">
            <span class="font_8 uppercase mb-0">RECS</span>
        </div>
        <div class="col-2 blue_background text-center">
            <span class="font_8 uppercase mb-0">Points</span>
        </div>
    </div>
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

    <div class="row font_12">
        <div class="col-6 text-center {{ $row_color }} right_line">
            {{ $roster_player->player->abbr_name.' '.$roster_player->player->last_name }}
        </div>
        <div class="col-2 text-center {{ $row_color }} right_line">
            {{ $roster_player->current_stat }}
        </div>
        <div class="col-2 text-center {{ $row_color }} right_line">
            {{ $need }}
        </div>
        <div class="col-2 {{ $row_color }} text-center">
            {{ $code->more }}
        </div>  
    </div>
    
    @endforeach

@if(owner('owner_id') == $owner->id)
    <a href="/games/rosterpng" target="_blank" class="btn btn-green mt-5">Download Image For Social</a>
@else
<br />
Start a chat with {{ $owner->full_name }}
<div class="row align-items-end">
    <div class="col-10 pr-0">
        <textarea class="form-control" style="height:38px;max-height:110px" id="txtarea">This isn't working yet.</textarea>
    </div>
    <div class="col-2 pl-0">
        <button class="btn btn-success"><i class="fal fa-paper-plane"></i></button>
    </div>
</div>

@endif
    
    
    <div class="row footer_padding">
    
    </div>
</div>
@endsection

@section('cms')
    @edit('games', $game->id)
@endsection

@section("javascript")

@if(owner('owner_id') == $owner->id)
<script>
    $(".notification-holder").text('Score: {{ $total_points }}')
</script>    
@endif
<script>
    function expandTextarea(id) {
    document.getElementById(id).addEventListener('keyup', function() {
        this.style.overflow = 'hidden';
        this.style.height = 0;
        this.style.height = this.scrollHeight + 'px';
    }, false);
}

expandTextarea('txtarea');
</script>
@endsection