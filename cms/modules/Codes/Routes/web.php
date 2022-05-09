<?php 

Route::group(['namespace' => 'Cms\Modules\Codes\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 

/*
|--------------------------------------------------------------------------
| Codes Routes
|--------------------------------------------------------------------------
*/

Route::resource('codes', 'CodesController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('codes/search', 'CodesController@search');

});