<?php 

Route::group(['namespace' => 'Cms\Modules\Players\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 

/*
|--------------------------------------------------------------------------
| Players Routes
|--------------------------------------------------------------------------
*/

Route::resource('players', 'PlayersController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('players/search', 'PlayersController@search');

});