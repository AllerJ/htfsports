@extends('cms-frontend::layout.master')



@section('content')

<div class="container-fluid mt-3 text-center">
    <small>Draft your players by tapping in each section</small>
    
    @foreach($codes as $code)
    <div class="row mt-3">
        <div class="col-6 blue_background  text-center">
            <span class="font_12 uppercase mb-0">{{ $code->alt }}</span>
        </div>
        <div class="col-6 blue_background text-center">
            <span class="font_12 uppercase mb-0">Points</span>
        </div>
    </div>
    <div class="row">
        <div class="col-6 text-center green_background right_line">
            <span class="font_12 uppercase">{{ $code->extra }}</span>
        </div>
        <div class="col-6 green_background text-center">
            <span class="font_12 uppercase">{{ $code->more }}</span>
        </div>  
    </div>
    <div class="row top_line">
        <div class="col-12 py-2 gray_background">
            <div class="btn-group btn-block">       
                   
              <a  class="btn btn-light btn-block" href="/api/draft_pick/{{ $code->code }}">
              <?php
$roster_players = $owner->roster->where('level_id', '=', $code->code)->where('game_id', '=', $game->id);
?>
                @if(count($roster_players) > 0)
                    @foreach($roster_players as $roster_player)
                       {{ $roster_player->player->abbr_name }} {{ $roster_player->player->last_name }} 
                    @endforeach
                @else    
                    Tap to Draft Player
                @endif

              </a>
                                 
            </div>             
        </div>
    </div>
    @endforeach

</div>
@endsection

@section('cms')
    @edit('games', $game->id)
@endsection

@section("javascript")

    
@endsection