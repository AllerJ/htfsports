@extends('cms-frontend::layout.master')

@section('content')
<?php $total_points = 0 ?>
<div class="container-fluid mt-3 text-center">


    <h3>Leaderboard</h3>
    
    
    <div class="row mt-3">
        <div class="col-2 blue_background  text-center">
            <span class="font_8 uppercase mb-0">Place</span>
        </div>
        <div class="col-8 blue_background  text-center">
            <span class="font_8 uppercase mb-0">Owner</span>
        </div>
        <div class="col-2 blue_background  text-center">
            <span class="font_8 uppercase mb-0">Points</span>
        </div>
    </div>
    <?php $count = 1; ?>
    @foreach($this_game_owners as $owner)
    <div class="row font_16">
        <div class="col-2 gray_background right_line pt-2">
           {{ $count }}
        </div>
        <div class="col-8 gray_background right_line pt-2">
        @if($owner->locked == 1)
           <a href="/games/opponent/roster/{{ $owner->id }}">{{ $owner->full_name }}</a>
        @else
            {{ $owner->full_name }}
        @endif
        
        </div>
        <div class="col-2 text-center gray_background">
            {{ $owner->score }}
        </div>
    </div>

    <?php $count++; ?>
    @endforeach
<br>
Click on a name to view roster.
    
    <div class="row footer_padding">
    
    </div>
</div>
@endsection

@section('cms')
    @edit('games', $game->id)
@endsection

@section("javascript")

<script>
    
</script>
    
@endsection