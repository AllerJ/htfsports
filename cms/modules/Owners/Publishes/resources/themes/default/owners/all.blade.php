@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Owner</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($owners as $owner)
                <a href="{!! URL::to('owners/'.$owner->id) !!}"><p>{!! $owner->name !!} - <span>{!! $owner->updated_at !!}</span></p></a>
            @endforeach

            {!! $owners !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('owners')
@endsection