<?php 

Route::group(['namespace' => 'Cms\Modules\Teams\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 

/*
|--------------------------------------------------------------------------
| Teams Routes
|--------------------------------------------------------------------------
*/

Route::resource('teams', 'TeamsController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('teams/search', 'TeamsController@search');

});