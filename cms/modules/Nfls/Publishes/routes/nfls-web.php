<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Nfl App Routes
|--------------------------------------------------------------------------
*/

Route::resource('nfls', 'NflsController', ['only' => ['show', 'index']]);


});