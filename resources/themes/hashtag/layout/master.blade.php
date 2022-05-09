<!doctype html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} @if (isset($page) && !is_null($page->title)) - {{ $page->title }} @endif</title>

        <meta name="description" content="@yield('seoDescription')">
        <meta name="keywords" content="@yield('seoKeywords')">
        <meta name="author" content="">
        
        <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/side-menu.css?v=1">
        <link rel="stylesheet" href="/css/hfs.css?v=1">
        @yield('stylesheets')
    </head>

    <body>
        <div id="layout">
            @theme('partials.navigation')
            <div id="main">
                @yield('content')
            </div>
        </div>
    </body>

    <script type="text/javascript">
        var _token = '{!! csrf_token() !!}';
        var _url = '{!! url("/") !!}';
    </script>
    @yield("pre-javascript")
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ asset('/js/fontawesome.js') }}"></script>
    
    <script type="text/javascript" src="{{ asset('/js/light.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hfs.js') }}"></script>
    @yield('javascript')
</html>
