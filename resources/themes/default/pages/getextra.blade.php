@extends('cms-frontend::layout.master')

@if (isset($page))
    @section('seoDescription') {{ $page->seo_description }} @endsection
    @section('seoKeywords') {{ $page->seo_keywords }} @endsection
@endif

@section('content')


<div class="container mt-3">
    <div class="row justify-content-md-center">
        <div class="col-12 col-md-5">
        
            @if(isin() == true)
            <h3 class="text-center">Welcome!</h3>
            <p>Before you continue, we need to get a bit more information from you.</p>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/login/instagram/extra">
                    {!! csrf_field() !!}
                    <div class="mt-3">
                        <label>Name</label>
                        <input class="form-control" type="text" name="full_name" placeholder="Name" value="{{ $owner->full_name }}" value="{{ old('full_name') }}">
                        <small>This will be the name other players see. It doesn't have to be your real name, but keep it clean, please.</small>
                    </div>
                    <div class="mt-3">
                        <label>Email</label>
                        <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                    </div>
                    <div class="mt-3">
                    <div class="btn-toolbar justify-content-right">
                        <button class="btn btn-green" type="submit">Save & Continue</button>                            
                    </div>
                </div>
            </form>
            
            
            @endif
            <br /><br /><br /><br /><br />            <br /><br /><br /><br /><br />
        </div>
    </div>
</div>
@endsection

@section('cms')
    @if (isset($page))
        @edit('pages', $page->id)
    @endif
@endsection
