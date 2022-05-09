<?php

namespace Cms\Modules\Rosters\Services;

use Config;
use Cms\Modules\Rosters\Models\Roster;

class RosterService
{
    public function __construct(Roster $roster)
    {
        $this->model = $roster;
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

        $columns = Schema::getColumnListing('rosters');

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

    public function findLGO($level_id, $game_id, $owner_id)
    {
        return $this->model->where('level_id', '=', $level_id)
                            ->where('game_id', '=', $game_id)
                            ->where('owner_id', '=', $owner_id)->first();
    }    

    public function findByOwnerLevel($level, $game_id, $owner_id)
    {
        return $this->model->join('players', 'players.id', '=', 'rosters.player_id')
        					->select('rosters.level_id', 'players.abbr_name', 'players.last_name', 'rosters.player_id')
        					->where('rosters.level', '=', $level)
                            ->where('rosters.game_id', '=', $game_id)
                            ->where('rosters.owner_id', '=', $owner_id);
    }      
    
    public function findGO($game_id, $owner_id)
    {
        return $this->model->join('players', 'players.id', '=', 'rosters.player_id')
        					->join('codes', 'codes.code', '=', 'rosters.level_id')
        					->select('rosters.level_id', 'players.abbr_name', 'players.last_name', 'rosters.player_id', 'rosters.current_stat', 'rosters.level', 'codes.more', 'codes.extra_2')
        					
                            ->where('rosters.game_id', '=', $game_id)
                            ->where('rosters.owner_id', '=', $owner_id)
                            ->orderBy('codes.order')->get();


    } 


/*
    public function findGO($game_id, $owner_id)
    {
        return $this->model->where('game_id', '=', $game_id)
                            ->where('owner_id', '=', $owner_id)->get();
    } 
*/

    
    public function findByGame($game_id)
    {
        return $this->model->where('game_id', '=', $game_id)
                            ->get();
    } 
    
    public function findGameNoDupe($game_id)
    {
        return $this->model->where('game_id', '=', $game_id)
                            ->groupBy('player_id')->get();
    } 
    

    public function update($id, $payload)
    {
        return $this->find($id)->update($payload);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

}