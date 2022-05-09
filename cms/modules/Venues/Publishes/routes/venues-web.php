<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Venue App Routes
|--------------------------------------------------------------------------
*/

Route::resource('venues', 'VenuesController', ['only' => ['show', 'index']]);


});