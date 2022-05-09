@extends('cms::layouts.dashboard')

@section('pageTitle') Game? @stop

@section('content')

    <div class="modal fade" id="deleteModal" tabindex="-3" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="deleteModalLabel">Delete Game</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this Game?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a id="deleteBtn" type="button" class="btn btn-warning" href="#">Confirm Delete</a>
                </div>
            </div>
        </div>
    </div>

    @include('cms::layouts.module-header', [ 'module' => 'games' ])

    <div class="col-12">
        @if ($games->isEmpty())
            <div class="well text-center">No games found.</div>
        @else
            <table class="table table-striped">
                <thead>
                    <th>Game Date - Location</th>
                    <th>Game Code</th>
                    <th class="text-right">Actions</th>
                </thead>
                <tbody>

                @foreach($games as $game)
                    <tr>
                        <td>
                            <a href="{!! route(config('cms.backend-route-prefix', 'cms').'.games.edit', [$game->id]) !!}">{!! date_format($game->game_at, 'm/d/Y') !!} - {!! $game->venue->name !!} </a>
                        </td>
                        <td>
                            <b>{{ $game->game_code }}</b>
                        </td>
                        <td class="text-right">
                            <div class="btn-toolbar justify-content-between">
<!--                                 <a class="btn btn-sm btn-outline-primary mr-2" href="{!! route(cms()->route('stats.enter'), [$game->id]) !!}"><i class="fa fa-plus-square"></i> Enter Stats During Game</a>      -->
<div class="btn-groupg" role="group" aria-label="Basic example">
                               
	                            <a class="btn btn-sm btn-primary d-block d-sm-inline" href="/cms/stats/enter/{{$game->id}}"><i class="fa fa-trophy"></i> Enter Stats</a>   
                                <a class="btn btn-sm btn-info mt-2 mt-sm-0 d-block d-sm-inline" href="/cms/games/{{ $game->id }}/pickplayers"><i class="fa fa-users"></i> Modify Players</a> 
                                <a class="btn btn-sm btn-warning mt-2 mt-sm-0 d-block d-sm-inline" href="/cms/games/{{ $game->id }}/trashtalk"><i class="fa fa-comment"></i>TrashTalk</a> 
                                <a class="btn btn-sm btn-secondary mt-2 mt-sm-0 d-block d-sm-inline" href="/cms/games/{{ $game->id }}/leaderboard"><i class="fa fa-user"></i>Leaderboard</a> 

</div>                                      
                                <form method="post" action="{!! cms()->url('games/'.$game->id) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="delete-btn btn  mt-2 mt-sm-0 btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                </form>                                            
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="text-center">
        {!! $pagination !!}
    </div>

@endsection

@section('javascript')

    @parent
    <script type="text/javascript">

        // add js here

    </script>

@endsection


