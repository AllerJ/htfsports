<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Game App Routes
|--------------------------------------------------------------------------
*/

Route::resource('games', 'GamesController', ['only' => ['show', 'index']]);


});