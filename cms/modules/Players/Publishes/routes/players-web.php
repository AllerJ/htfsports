<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Player App Routes
|--------------------------------------------------------------------------
*/

Route::resource('players', 'PlayersController', ['only' => ['show', 'index']]);


});