<?php

use Illuminate\Http\Request;


Route::post('login', 'API\NewOwnerControllerr@login');
Route::get('register', 'API\NewOwnerControllerr@register');
Route::get('recoverpassword', 'API\NewOwnerControllerr@recoverPassword');
Route::get('resetpassword/{code}', 'API\NewOwnerControllerr@resetPasswordView');
Route::post('resetpassword', 'API\NewOwnerControllerr@resetPassword');
Route::get('findgeogame', 'Cms\GamesController@api_findGameByGeo');




Route::group(['middleware' => 'auth:api'], function(){
	Route::post('details', 'API\OwnerController@details');
	
	Route::get('checktoken', 'Cms\GamesController@api_checkToken');
	
	Route::post('sendmessages', 'Cms\GamesController@api_sendMessages');

	Route::get('messages', 'Cms\GamesController@api_getMessages');

	
	Route::get('findgame', 'Cms\GamesController@api_findGame');
			
	Route::get('joingame', 'Cms\GamesController@api_joinGame');

// HTML //
	Route::get('auto_joingame', 'Cms\GamesController@auto_joinGame');
	Route::get('manual_joingame', 'Cms\GamesController@manual_joingame');
	Route::get('touchdowns', 'Cms\GamesController@html_touchDowns');
	Route::get('yards', 'Cms\GamesController@html_allYards');
	Route::get('receptions', 'Cms\GamesController@html_receptions');
	Route::get('draft_pick/{level}', 'Cms\GamesController@html_gameDraft');
	
	
 	Route::get('levels/{level}', 'Cms\GamesController@api_levels');
	
	
	Route::get('player_list/{level}', 'Cms\GamesController@api_gameDraft');
	Route::get('clear_draft/{group}', 'Cms\GamesController@api_gameDraftRest');
	Route::get('draft/{level}/{player_id}', 'Cms\GamesController@api_gamePostDraft');		
	Route::get('leaderboard', 'Cms\GamesController@api_leaderboard');
	Route::get('roster', 'Cms\GamesController@api_roster');
	Route::get('roster/{owner_id}', 'Cms\GamesController@api_roster');
	
	
/*
	
	Route::get('td', 'Cms\GamesController@api_touchDowns');
	Route::get('yards', 'Cms\GamesController@api_allYards');
	Route::get('receptions', 'Cms\GamesController@api_receptions');
	Route::get('yards', 'Cms\GamesController@api_allYards');
	Route::get('receptions', 'Cms\GamesController@api_receptions');

	Route::get('clear_draft/{group}', 'Cms\GamesController@api_gameDraftRest');
	Route::get('player_list/{level}', 'Cms\GamesController@api_gameDraft');
	Route::get('draft/{level}/{player_id}', 'Cms\GamesController@api_gamePostDraft');	
	
	Route::get('leaderboard', 'Cms\GamesController@api_leaderboard');
	Route::get('roster', 'Cms\GamesController@api_roster');
	Route::get('roster/{owner_id}', 'Cms\GamesController@api_roster');
*/
	
});