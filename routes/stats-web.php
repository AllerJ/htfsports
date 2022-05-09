<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Stat App Routes
|--------------------------------------------------------------------------
*/

Route::resource('stats', 'StatsController', ['only' => ['show', 'index']]);


});