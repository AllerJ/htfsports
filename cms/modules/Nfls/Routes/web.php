<?php 

Route::group(['namespace' => 'Cms\Modules\Nfls\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 

/*
|--------------------------------------------------------------------------
| Nfls Routes
|--------------------------------------------------------------------------
*/

Route::resource('nfls', 'NflsController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('nfls/search', 'NflsController@search');

});