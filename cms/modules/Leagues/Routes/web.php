<?php 

Route::group(['namespace' => 'Cms\Modules\Leagues\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 

/*
|--------------------------------------------------------------------------
| Leagues Routes
|--------------------------------------------------------------------------
*/

Route::resource('leagues', 'LeaguesController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('leagues/search', 'LeaguesController@search');

});