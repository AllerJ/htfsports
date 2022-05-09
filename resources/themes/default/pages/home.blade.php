@extends('cms-frontend::layout.master')

@if (isset($page))
    @section('seoDescription') {{ $page->seo_description }} @endsection
    @section('seoKeywords') {{ $page->seo_keywords }} @endsection
@endif

@section('content')


<div class="container mt-3">
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-5">
        

            <h3 class="text-center">Play Along With #FantasySports {!! bin2hex(openssl_random_pseudo_bytes(2)) !!}</h3>
@if(Session::has('message'))
<p class="alert alert-{{ Session::get('message.level') }}">{{ Session::get('message.content') }}</p>
@endif
            <form method="POST" action="/gamelogin" id="game_login">
                    {!! csrf_field() !!}
                    <div class="mt-3">
                        <label>Email</label>
                        <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                    </div>
                    <div class="mt-3">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" placeholder="Password" id="password">
                    </div>
                    <div class="mt-3">
                        <label>
                            Remember Me <input type="checkbox" name="remember">
                        </label>
                    </div>
                    <input type="hidden" name="lat" id="lat">
                    <input type="hidden" name="lng" id="lng">
                    <div class="mt-3">
                        <div class="btn-toolbar justify-content-between">
                        <a class="btn btn-blue" href="/gameregister">Sign Up</a><br />
                            <button class="btn btn-green" id="login_button" onclick="getLocation();">Login</button>                            
                        </div>
                    </div>
            </form>


            <p class="mt-3"><small>To play along you must ALLOW Location Services. We do not store or track your location. It is an initial step to verify you are in the venue hosting a #FantasySports game.</small></p>

<!--
            <div class="mt-5">
            <a href="/login/instagram" class="btn btn-block btn-instagram instagram btn-lg"><i class="fab fa-instagram fa-2x"></i> &nbsp; Log In With Instagram</a>
            </div>
-->
            
        
            @if (isset($page))
                {!! $page->entry !!}
            @else
               
            @endif
            <br /><br /><br /><br /><br />
        </div>
    </div>
</div>
@endsection

@section('cms')
    @if (isset($page))
        @edit('pages', $page->id)
    @endif
@endsection


@section('javascript')
    
<script>

/*
    $( "#login_button" ).click(function( event ) {
      event.preventDefault();
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
            showPosition
            },
            function (error) { 
                if (error.code == error.PERMISSION_DENIED)
                $("#game_login").submit();
            });  
        } else {
            $("#game_login").submit();
        }
    }
    function showPosition(position) {
        $("#lat").val(position.coords.latitude);
        $("#lng").val(position.coords.longitude);
        $("#game_login").submit();  
    }
*/
    
</script>
    
@endsection