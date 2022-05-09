<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| League App Routes
|--------------------------------------------------------------------------
*/

Route::resource('leagues', 'LeaguesController', ['only' => ['show', 'index']]);


});