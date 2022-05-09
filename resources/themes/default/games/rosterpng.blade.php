@extends('cms-frontend::layout.image')

@section('content')
<?php $total_points = 0 ?>
<div class="container-fluid mt-2">
<style>
    .score-holder {
        font-size:30px;    
    }
    thead {
        font-size: 30px;
        font-weight: bold;
    }
    tbody {
        font-size: 30px;
    }
    h4 {
        font-size: 35px;
        font-weight: bold;
    }
</style>
<table width="100%">
    <tr>
        <td width="40%">
            <img src="/img/hfs-logo.png" class="img-fluid">
        </td>
        <td width="10%"></td>
        <td width="40% text-center">
            <img src="{{ $game->venue->logo }}" class="img-fluid">
            <div class="mt-3">
            {{ $game->venue->name }}
            </div>
            <div class="mt-2">
            <hr>
            <div class="score-holder"></div>
            </div>
        </td>
    </tr>
</table>
<hr>
<div class="container-fluid mt-5 text-center">
    <h4 class="text-center">Throwing Touchdowns</h4>
    <table width="100%">
        <thead>
            <tr class="blue_background ">
                <td class="text-center">Player</td>
                <td class="text-center">Current</td>
                <td class="text-center">TDS</td>                
                <td class="text-center">Points</td>
            </th>
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
    
            <tr>
                <td width="50%" class="{{ $row_color }} text-center right_line">
                    {{ $roster_player->player->abbr_name.' '.$roster_player->player->last_name }}
                </td>
                <td width="16%" class="{{ $row_color }} text-center right_line">
                    {{ $roster_player->current_stat }}
                </td>
                <td width="16%" class="{{ $row_color }} text-center right_line">
                    {{ $need }}
                </td>
                <td width="16%" class="{{ $row_color }} text-center">
                    {{ $code->more }}
                </td>
            </tr>
        
        @endforeach
        </tbody>
    </table>
    
    
    
    <h4 class="text-center mt-4">All Purpose Yards</h4>
    <table width="100%">
        <thead>
            <tr class="blue_background ">
                <td class="text-center">Player</td>
                <td class="text-center">Current</td>
                <td class="text-center">YDS</td>                
                <td class="text-center">Points</td>
            </th>
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
    
            <tr>
                <td width="50%" class="{{ $row_color }} text-center right_line">
                    {{ $roster_player->player->abbr_name.' '.$roster_player->player->last_name }}
                </td>
                <td width="16%" class="{{ $row_color }} text-center right_line">
                    {{ $roster_player->current_stat }}
                </td>
                <td width="16%" class="{{ $row_color }} text-center right_line">
                    {{ $need }}
                </td>
                <td width="16%" class="{{ $row_color }} text-center">
                    {{ $code->more }}
                </td>
            </tr>
        
        @endforeach
        </tbody>
    </table>
    
    
     <h4 class="text-center mt-4">Receptions</h4>
    <table width="100%">
        <thead>
            <tr class="blue_background ">
                <td class="text-center">Player</td>
                <td class="text-center">Current</td>
                <td class="text-center">RECS</td>                
                <td class="text-center">Points</td>
            </th>
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
    
            <tr >
                <td width="50%" class="{{ $row_color }} text-center right_line">
                    {{ $roster_player->player->abbr_name.' '.$roster_player->player->last_name }}
                </td>
                <td width="16%" class="{{ $row_color }} text-center right_line">
                    {{ $roster_player->current_stat }}
                </td>
                <td width="16%" class="{{ $row_color }} text-center right_line">
                    {{ $need }}
                </td>
                <td width="16%" class="{{ $row_color }} text-center">
                    {{ $code->more }}
                </td>
            </tr>
        
        @endforeach
        </tbody>
    </table>
</div>
</div>

@endsection

@section('cms')
    @edit('games', $game->id)
@endsection

@section("javascript")

<script>
    $(".score-holder").text('{{ $owner->full_name }} Score: {{ $total_points }}')
</script>
    
@endsection