@extends('cms::layouts.dashboard')

@section('pageTitle') Schedule? @stop

@section('content')

    <div class="modal fade" id="deleteModal" tabindex="-3" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="deleteModalLabel">Delete Schedule</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure want to delete this Schedule?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a id="deleteBtn" type="button" class="btn btn-warning" href="#">Confirm Delete</a>
                </div>
            </div>
        </div>
    </div>

    @include('cms::layouts.module-header', [ 'module' => 'schedules' ])

    <div class="col-12">
        @if ($schedules->isEmpty())
            <div class="well text-center">No schedules found.</div>
        @else
            <table class="table table-striped">
                <thead>
                    <th>Name</th>
                    <th width="200px" class="text-right">Actions</th>
                </thead>
                <tbody>

                @foreach($schedules as $schedule)
                    <tr>
                        <td>
                            <a href="{!! route(config('cms.backend-route-prefix', 'cms').'.schedules.edit', [$schedule->id]) !!}">{!! $schedule->name !!}</a>
                        </td>
                        <td class="text-right">
                            <div class="btn-toolbar justify-content-between">
                                <a class="btn btn-sm btn-outline-primary mr-2" href="{!! route(cms()->route('schedules.edit'), [$schedule->id]) !!}"><i class="fa fa-edit"></i> Edit</a>                                        
                                <form method="post" action="{!! cms()->url('_lower_casePlural/'.$schedule->id) !!}">
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


