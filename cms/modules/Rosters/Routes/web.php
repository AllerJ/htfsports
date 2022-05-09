<?php 

Route::group(['namespace' => 'Cms\Modules\Rosters\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 

/*
|--------------------------------------------------------------------------
| Rosters Routes
|--------------------------------------------------------------------------
*/

Route::resource('rosters', 'RostersController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('rosters/search', 'RostersController@search');

});