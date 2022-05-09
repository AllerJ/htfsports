<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Schedule App Routes
|--------------------------------------------------------------------------
*/

Route::resource('schedules', 'SchedulesController', ['only' => ['show', 'index']]);


});