@extends('cms-frontend::layout.master')

@if (isset($page))
    @section('seoDescription') {{ $page->seo_description }} @endsection
    @section('seoKeywords') {{ $page->seo_keywords }} @endsection
@endif

@section('content')


<div class="container mt-3">
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-5">
        
            <h3 class="text-center">Play Along With #FantasySports</h3>
            <p class="mt-5">
            <span class="bold">No games are being played at this time, or your location services are turned off.</span>
            </p>
            <p>If you are at a venue hosting a game, please contact one of the #FantasySports Representatives to get the "Game Code."</p>
            <form method="POST" action="/games/joinmanual">
                {!! csrf_field() !!}
                <div class="col-md-12 mt-5">
                    <label>Enter A Game Code</label>
                    <input class="form-control uppercase" type="text" name="game_code" placeholder="ABC">
                </div>
                <div class="col-md-12 mt-3  justify-content-between">
                        <button class="btn btn-green" type="submit">Start</button>
                </div>
            </form>
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
    
@endsection