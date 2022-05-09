@extends('cms::layouts.dashboard')

@section('pageTitle') Player? @stop

@section('content')

    <div class="modal fade" id="deleteModal" tabindex="-3" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="deleteModalLabel">Delete Player</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this Player?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a id="deleteBtn" type="button" class="btn btn-warning" href="#">Confirm Delete</a>
                </div>
            </div>
        </div>
    </div>

    @include('cms::layouts.module-header', [ 'module' => 'players' ])

    <div class="col-12">
        @if ($players->isEmpty())
            <div class="well text-center">No players found.</div>
        @else
            <table class="table table-striped">
                <thead>
                    <th>Name</th>
                    <th width="200px" class="text-right">Actions</th>
                </thead>
                <tbody>

                @foreach($players as $player)
                    <tr>
                        <td>
                            <a href="{!! route(config('cms.backend-route-prefix', 'cms').'.players.edit', [$player->id]) !!}">{!! $player->name !!}</a>
                        </td>
                        <td class="text-right">
                            <div class="btn-toolbar justify-content-between">
                                <a class="btn btn-sm btn-outline-primary mr-2" href="{!! route(cms()->route('players.edit'), [$player->id]) !!}"><i class="fa fa-edit"></i> Edit</a>                                        
                                <form method="post" action="{!! cms()->url('_lower_casePlural/'.$player->id) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button class="delete-btn btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i> Delete</button>
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


