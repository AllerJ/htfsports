@extends('cms-frontend::layout.master')

@section('content')
<style>
	.responsive {
  width: 100%;
  height: auto;
}
</style>

<div class="container mt-3">
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-5">
        <center>
<img src="/img/hfs-logo.png" class="responsive">
        </center>
            <h3 class="text-center">Password Updated</h3>
@if(Session::has('message'))
<p class="alert alert-{{ Session::get('message.level') }}">{{ Session::get('message.content') }}</p>
@endif
            <p>Thank you. Your password was updated, you may return to the app and log in.</p>


               

            <br /><br /><br /><br /><br />
        </div>
    </div>
</div>
@endsection


@section('javascript')
    

@endsection