<?php

namespace Cms\Modules\Players\Services;

use Config;
use Cms\Modules\Players\Models\Player;
use Cms\Modules\Players\Models\PlayerImage;
use DB;

class PlayerService
{
    public function __construct(Player $player, PlayerImage $images)
    {
        $this->model = $player;
        $this->images = $images;
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

        $columns = Schema::getColumnListing('players');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$payload.'%');
        };

        return $query->paginate(Config::get('cms.pagination', 24));
    }
    
    public function autocomplete($payload, $game_id)
    {
        $query = $this->model->select(DB::raw('CONCAT(abbr_name, " ", last_name) AS full_name'), 'id')->where('game_id', '=', $game_id)->where('active', '=', '1')->orderBy('last_name', 'desc');
        

        $query->where(function ($query) use($payload) {
            return $query->where('first_name', 'LIKE', '%'.$payload.'%')
               ->orWhere('abbr_name', 'LIKE', '%'.$payload.'%')
               ->orWhere('last_name', 'LIKE', '%'.$payload.'%');
        })->get();
        
        return $query->get();
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
    
    public function findByGame($game_id)
    {
        $query = $this->model->orderBy('team_id', 'desc');
        $query->where('game_id', '=', $game_id);
        return $query->get();
    }

    public function findByNameTeam($first_name, $last_name)
    {
        $query = $this->model->where('abbr_name', '=', $first_name)->where('last_name', '=', $last_name)->get();
        return $query;
    }    

    public function findImageByNameTeam($first_name, $last_name, $name)
    {
        $query = $this->images->where('first_name', '=', $first_name)->where('last_name', '=', $last_name)->where('team', '=', $name)->first();
        return $query;
    }    
    
    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

}