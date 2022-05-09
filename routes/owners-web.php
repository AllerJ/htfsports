<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Owner App Routes
|--------------------------------------------------------------------------
*/

Route::resource('owners', 'OwnersController', ['only' => ['show', 'index']]);


});