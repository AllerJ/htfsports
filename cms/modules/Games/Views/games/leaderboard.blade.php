@extends('cms::layouts.dashboard')

@section('pageTitle') Leaderboard @stop

@section('content')


    <div class="col-12">
	    <h4> {!! date_format($game->game_at, 'm/d/Y') !!} - {!! $game->venue->name !!}</h4>
        <table class="table table-striped">
            <thead>
                <th>Owner</th>
                <th>Score</th>
                <th class="text-right">Actions</th>
            </thead>
            <tbody>

			@foreach($this_game_owners as $owner)
                <tr>
                    <td>
                        <a href="/cms/games/{{ $game->id }}/{{ $owner->id }}/roster">{{ $owner->full_name }}</a><br>{{ $owner->email }}
                    </td>
                    <td>
                        <b>{{ $owner->score }}</b>
                    </td>
                    <td class="text-right">
                        <div class="btn-toolbar justify-content-end">
							<div class="btn-group" role="group" aria-label="Basic example">
	                            <a class="btn btn-sm btn-info" href="/cms/games/{{ $game->id }}/{{ $owner->id }}/roster"><i class="fa fa-users"></i> <span class="d-none d-sm-inline"> View Roster</span></a> 
	                            <a class="btn btn-sm btn-success" href="/cms/games/{{ $game->id }}/{{ $owner->id }}/unlock"><i class="fa fa-unlock-alt"></i><span class="d-none d-sm-inline"> Unlock</span></a> 
	                            <a class="btn btn-sm btn-warning" href="/cms/games/{{ $game->id }}/{{ $owner->id }}/clear"><i class="fa fa-minus-circle"></i><span class="d-none d-sm-inline"> Clear Roster</span></a> 
	                            <a class="btn btn-sm btn-danger" href="/cms/games/{{ $game->id }}/{{ $owner->id }}/remove"><i class="fa fa-trash"></i> <span class="d-none d-sm-inline">Remove</span></a> 
							</div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


@endsection

@section('javascript')

    @parent
    <script type="text/javascript">

        // add js here

    </script>

@endsection


