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
            <h3 class="text-center">Password Reset</h3>

@if (isset($errors))
        {!! implode('', $errors->all('<p class="alert alert-danger">:message</p>')) !!}
@endif
            <form method="POST" action="/api/resetpassword" id="game_login">

                    <div class="mt-3">
                        <label>Recovery Code</label>
                        <input class="form-control" type="text" name="code" value="{{ $code }}">
                    </div>
                    <div class="mt-3">
                        <label>New Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Password" id="password">
                        <small> [ 6 Characters or more ] </small>
                    </div>
                                       <div class="mt-3">
                        <div class="btn-toolbar justify-content-between">
                            <button class="btn btn-green" id="login_button">Update Password</button>                            
                        </div>
                    </div>
            </form>



               

            <br /><br /><br /><br /><br />
        </div>
    </div>
</div>
@endsection


@section('javascript')
    

@endsection