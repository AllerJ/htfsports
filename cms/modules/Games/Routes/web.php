<?php 


Route::group(['namespace' => 'Cms\Modules\Games\Controllers', 'prefix' => config('cms.backend-route-prefix', 'cms'), 'middleware' => ['web', 'auth', 'cms']], function () { 


/*
|--------------------------------------------------------------------------
| Games Routes
|--------------------------------------------------------------------------
*/

Route::resource('games', 'GamesController', [ 'except' => ['show'], 'as' => config('cms.backend-route-prefix', 'cms') ]);
Route::post('games/search', 'GamesController@search');

Route::get('games/{game_id}/pickteams', 'GamesController@pickTeams')->name('games.pickteams');
Route::post('games/{game_id}/pickteams', 'GamesController@saveTeams')->name('games.saveteams');

Route::get('games/{game_id}/pickplayers', 'GamesController@pickPlayers')->name('games.pickplayers');
Route::post('games/{game_id}/pickplayers', 'GamesController@savePlayers')->name('games.saveplayers');

Route::get('games/{game_id}/leaderboard', 'GamesController@leaderboard')->name('games.admin.leaderboard');
Route::get('games/{game_id}/trashtalk', 'GamesController@trashtalk')->name('games.admin.trashtalk');
Route::get('games/{game_id}/{owner_id}/roster', 'GamesController@rosterPage')->name('games.admin.owner.roster');
Route::get('games/trashtalk', 'GamesController@trashtalkAdd')->name('games.admin.trashtalk.add');

Route::get('games/trashtalk/msg', 'GamesController@trashtalkView')->name('games.admin.trashtalk.view');

});