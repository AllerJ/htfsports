<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Team App Routes
|--------------------------------------------------------------------------
*/

Route::resource('teams', 'TeamsController', ['only' => ['show', 'index']]);


});