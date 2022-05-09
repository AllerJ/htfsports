@extends('cms-frontend::layout.master')

@section('pageTitle') Settings @stop

@section('content')

<div class="container mt-5">
    <div class="row justify-content-sm-center">
        <div class="col-12 col-sm-6 col-md-4">
        
            <div class="header">
                <h1>User Settings</h1>
            </div>
            
            <div class="content">
                <p>
                    <form method="POST" action="/user/settings" class="pure-form  pure-form-aligned">
                        {!! csrf_field() !!}
    
                        <div class="pure-control-group">
                            @input_maker_label('Email')
                            @input_maker_create('email', ['type' => 'string'], $user)
                        </div>
    
                        <div class="pure-control-group">
                            @input_maker_label('Name')
                            @input_maker_create('name', ['type' => 'string'], $user)
                        </div>
    
                        @include('user.meta')
    
                        @if ($user->roles->first()->name === 'admin' || $user->id == 1)
                            <div class="pure-control-group">
                                @input_maker_label('Role')
                                @input_maker_create('roles', ['type' => 'relationship', 'model' => 'App\Models\Role', 'label' => 'label', 'value' => 'name'], $user)
                            </div>
                        @endif
    
                        <div class="mt-3">
                            <div class="btn-toolbar justify-content-between">
                                <button class="btn btn-green" type="submit">Save</button>
                                <a class="btn btn-link" href="/user/password">Change Password</a>
                            </div>
                        </div>
                    </form>
                </p>
            </div>
        </div>
    </div>
</div>

@stop
