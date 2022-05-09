<?php 

Route::group(['namespace' => 'Cms\Modules\Stats\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web']], function () { 
Route::get('scrape/{season}', 'StatsController@scrape');

Route::get('cron/{game_id}/{cron}', 'StatsController@getStats');


});

Route::group(['namespace' => 'Cms\Modules\Stats\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 



/*
|--------------------------------------------------------------------------
| Stats Routes
|--------------------------------------------------------------------------
*/

Route::get('stats/autocomplete/{game_id}', 'StatsController@autocomplete')->name('cms.stats.autocomplete');
Route::get('stats/enter/{game_id}', 'StatsController@enter')->name('cms.stats.enter');
Route::resource('stats', 'StatsController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('stats/search', 'StatsController@search');

Route::get('stats/{game_id}/fetch', 'StatsController@getStats')->name('cms.stats.fetch');


});