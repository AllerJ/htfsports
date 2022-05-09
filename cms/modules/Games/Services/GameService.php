<?php

namespace Cms\Modules\Games\Services;

use Config;
use Cms\Modules\Games\Models\Game;
use Cms\Modules\Games\Models\GameTeam;
use Cms\Modules\Games\Models\GameMessage;
use DB;

class GameService
{
    public function __construct(Game $game, GameTeam $teams, GameMessage $messages)
    {
        $this->model = $game;
        $this->teams = $teams;
        $this->messages = $messages;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginated()
    {
        $model = $this->model;

        if (isset(request()->dir) && isset(request()->field)) {
            $model = $model->orderBy(request()->field, request()->dir);
        } else {
            $model = $model->orderBy('created_at', 'desc');
        }

        return $model->paginate(config('cms.pagination', 25));
    }

    public function search($payload)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        $query->where('id', 'LIKE', '%'.$payload.'%');

        $columns = Schema::getColumnListing('games');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$payload.'%');
        };

        return $query->paginate(Config::get('cms.pagination', 24));
    }

    public function create($payload)
    {
        return $this->model->create($payload);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function update($id, $payload)
    {
        return $this->find($id)->update($payload);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
    
    public function findByCode($game_code)
    {
        return $this->model->where('game_code', '=', $game_code)->first();
    }

	public function findByDate($today)
    {
        return $this->model->where('game_at', '=', $today)->get();
    }

	public function findById($today)
    {
        return $this->model->where('id', '=', $today)->get();
    }

    public function findByGameTeam($game_id, $team_id)
    {
        return $this->teams->where('game_id', '=', $game_id)
                            ->where('team_id', '=', $team_id)->first();
    }

    public function findMessages($game_id)
    {
        return $this->messages->where('game_id', '=', $game_id)->get();
    }


    public function findNearby($lat,$lng)
    {
        return DB::table('venues')
                ->selectRaw('games.id as game_id, games.game_code, games.artwork, games.game_at, games.start_at, games.end_at, games.notes, venues.address, venues.city, venues.zip, venues.logo, venues.id, venues.name, (3959 * acos (cos ( radians('.$lat.') ) * cos( radians( venues.lat ) ) * cos( radians( venues.lon ) - radians('.$lng.') ) + sin ( radians('.$lat.') ) * sin( radians( venues.lat ) ) ) ) AS distance')
                ->join('games', 'venues.id', '=', 'games.venue_id')
                ->whereDate('games.game_at', '=', date("Y-m-d 00:00:00"))
                ->having('distance', '<', .9)
                ->first();  
    }

}