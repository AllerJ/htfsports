<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Roster App Routes
|--------------------------------------------------------------------------
*/

Route::resource('rosters', 'RostersController', ['only' => ['show', 'index']]);


});