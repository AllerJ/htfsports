<?php 

Route::group(['namespace' => 'Cms', 'middleware' => ['web']], function () {

/*
|--------------------------------------------------------------------------
| Game App Routes
|--------------------------------------------------------------------------
*/
Route::get('/account', 'GamesController@account')->name('games.account');

Route::get('/games/draft_pick/{level}', 'GamesController@gameDraft')->name('games.draft_list');
Route::get('/games/draft_pick/{level}/{player_id}', 'GamesController@gamePostDraft')->name('games.post.draft_list');

Route::get('/games/reset/{group}', 'GamesController@gameDraftRest')->name('games.draft.reset');



Route::get('/games/code', 'GamesController@gameCode')->name('games.code');
Route::get('/games/draft', 'GamesController@draftAjax')->name('games.draft');
Route::get('/games/td', 'GamesController@touchDowns')->name('games.td');
Route::get('/games/yards', 'GamesController@allYards')->name('games.all');
Route::get('/games/receptions', 'GamesController@receptions')->name('games.recp');
Route::get('/games/roster', 'GamesController@ownerRoster')->name('games.roster');
Route::get('/games/opponent/roster/{opponent_id}', 'GamesController@opponentRoster')->name('games.opponent.roster');
Route::get('/games/rosterpngpage/{game_id}/{owner_id}', 'GamesController@rosterPngPage')->name('games.rosterpng');
Route::get('/games/rosterpng', 'GamesController@ownerRosterPNG')->name('games.rosterpng');
Route::get('/games/leaderboard', 'GamesController@leaderboard')->name('games.leaderboard');

Route::get('/games/leaderboardpngpage/{game_id}', 'GamesController@leaderboardPngPage');
Route::get('/games/leaderboardpng/{game_id}', 'GamesController@leaderboardPNG');

Route::get('/games/notifications', 'GamesController@notifications')->name('games.notifications');

Route::post('/games/join', 'GamesController@joinGame')->name('games.join');

Route::post('/games/joinmanual', 'GamesController@joinGameManual');


Route::post('/games/draft', 'GamesController@draftAjax')->name('games.draft');



Route::resource('games', 'GamesController', ['only' => ['show', 'index']]);




//USE THE BELOW TO GRAB ESPN ID / HEADSHOTS
// Route::get('/games/draft', 'GamesController@draftAjax')->name('games.draft');


});

