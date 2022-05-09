<?php 

Route::group(['namespace' => 'Cms\Modules\Schedules\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 

/*
|--------------------------------------------------------------------------
| Schedules Routes
|--------------------------------------------------------------------------
*/

Route::resource('schedules', 'SchedulesController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('schedules/search', 'SchedulesController@search');

});