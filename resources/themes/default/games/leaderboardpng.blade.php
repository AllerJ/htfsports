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
        </td>
    </tr>
</table>
<hr>
<div class="container-fluid mt-5 text-center">


    <h4 class="text-center">Leaderboard</h4>
    <table width="100%">
        <thead>
            <tr class="blue_background ">
                <td class="text-center">Player</td>
                <td class="text-center">Score</td>
            </th>
        </thead>
        <tbody>
        
    @foreach($this_game_owners as $owner)
            <tr>
                <td width="80%" class="gray_background text-center right_line">
                    {{ $owner->full_name }}
                </td>
                <td width="20%" class="gray_background text-center right_line">
                     {{ $owner->score }}
                </td>
            </tr>
    @endforeach
        </tbody>
    </table>
</div>
</div>

@endsection

@section('cms')
@endsection

@section("javascript")
    
@endsection