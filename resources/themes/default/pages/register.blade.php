@extends('cms-frontend::layout.master')

@if (isset($page))
    @section('seoDescription') {{ $page->seo_description }} @endsection
    @section('seoKeywords') {{ $page->seo_keywords }} @endsection
@endif

@section('content')


<div class="container mt-3">
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-5">
        
            @if(isin() == false)
            <h3 class="text-center">Create Account</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form method="POST" action="/gameregister" id="register">
                {!! csrf_field() !!}
                <div class="mt-3">
                    <label>Name</label>here
                    <input class="form-control" type="text" name="full_name" placeholder="Name" value="{{ old('full_name') }}">
                    <small>This will be the name other players see. It doesn't have to be your real name, but keep it clean, please.</small>
                </div>
                <div class="mt-3">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                </div>
                <div class="mt-3">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password" placeholder="Password" id="password">
                </div>
                <div class="mt-3">
                    <label>Confirm Password</label>
                    <input class="form-control" type="password" name="password_confirmation" placeholder="Password" id="password_confirmation">
                </div>
                    <input type="hidden" name="lat" id="lat">
                    <input type="hidden" name="lng" id="lng">
                <div class="mt-3">
                    <div class="btn-toolbar justify-content-between">
                        <button class="btn btn-green" id="join_button" type="submit" oonclick="getLocation();">Join</button>                            
                    </div>
                </div>
            </form>
          
               
            @endif
               <div class="row footer_padding">
    
    </div>
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

    $( "#join_buttonn" ).click(function( event ) {
      event.preventDefault();
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
            showPosition
            },
            function (error) { 
                if (error.code == error.PERMISSION_DENIED)
                $("#register").submit();
            });  
        } else {
            $("#register").submit();
        }
    }
    function showPosition(position) {
        $("#lat").val(position.coords.latitude);
        $("#lng").val(position.coords.longitude);
        $("#register").submit();  
    }
</script>
    
@endsection