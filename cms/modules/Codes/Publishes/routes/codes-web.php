<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Code App Routes
|--------------------------------------------------------------------------
*/

Route::resource('codes', 'CodesController', ['only' => ['show', 'index']]);


});