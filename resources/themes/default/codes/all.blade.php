@extends('cms-frontend::layout.master')

@section('content')

<div class="container">

    <h1>Code</h1>

    <div class="row">
        <div class="col-md-12">
            @foreach($codes as $code)
                <a href="{!! URL::to('codes/'.$code->id) !!}"><p>{!! $code->name !!} - <span>{!! $code->updated_at !!}</span></p></a>
            @endforeach

            {!! $codes !!}
        </div>
    </div>

</div>

@endsection

@section('cms')
    @edit('codes')
@endsection