@extends('cms::layouts.dashboard')

@section('pageTitle') Stat? @stop

@section('content')

    <div class="modal fade" id="deleteModal" tabindex="-3" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="deleteModalLabel">Delete Stat</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this Stat?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a id="deleteBtn" type="button" class="btn btn-warning" href="#">Confirm Delete</a>
                </div>
            </div>
        </div>
    </div>

    @include('cms::layouts.module-header', [ 'module' => 'stats' ])

    <div class="col-12">
        @if ($stats->isEmpty())
            <div class="well text-center">No stats found.</div>
        @else
            <table class="table table-striped">
                <thead>
                    <th>Name</th>
                    <th width="200px" class="text-right">Actions</th>
                </thead>
                <tbody>

                @foreach($stats as $stat)
                    <tr>
                        <td>
                            <a href="{!! route(config('cms.backend-route-prefix', 'cms').'.stats.edit', [$stat->id]) !!}">{!! $stat->name !!}</a>
                        </td>
                        <td class="text-right">
                            <div class="btn-toolbar justify-content-between">
                                <a class="btn btn-sm btn-outline-primary mr-2" href="{!! route(cms()->route('stats.edit'), [$stat->id]) !!}"><i class="fa fa-edit"></i> Edit</a>                                        
                                <form method="post" action="{!! cms()->url('_lower_casePlural/'.$stat->id) !!}">
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


