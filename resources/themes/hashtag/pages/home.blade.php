@extends('cms-frontend::layout.master')

@if (isset($page))
    @section('seoDescription') {{ $page->seo_description }} @endsection
    @section('seoKeywords') {{ $page->seo_keywords }} @endsection
@endif

@section('content')

<div class="header">
    <img src="/img/hfs-logo.png" class="pure-img">
    <h2>Join A Game</h2>
</div>
<div class="content text-center">
    <p>
        Enter game code or allow location services
    <form class="pure-form">
        <input type="text" placeholder="Game Code" name="game_code" id="game_code" class="pure-input-1" maxlength="3">
        <button type="submit" class="pure-button pure-button-primary">Join</button>
    </form>
</div>
<div class="content">

    @if (isset($page))
        {!! $page->entry !!}
    @else
        
    @endif

</div>
@endsection

@section('cms')
    @if (isset($page))
        @edit('pages', $page->id)
    @endif
@endsection
