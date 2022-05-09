<?php 

Route::group(['namespace' => 'Cms\Modules\Venues\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 

/*
|--------------------------------------------------------------------------
| Venues Routes
|--------------------------------------------------------------------------
*/

Route::resource('venues', 'VenuesController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('venues/search', 'VenuesController@search');

});