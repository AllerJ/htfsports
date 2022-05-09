<?php 

Route::group(['namespace' => 'Cms\Modules\Owners\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 

/*
|--------------------------------------------------------------------------
| Owners Routes
|--------------------------------------------------------------------------
*/

Route::resource('owners', 'OwnersController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('owners/search', 'OwnersController@search');

});