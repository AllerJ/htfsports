<?php

namespace Cms\Modules\Stats\Services;

use Config;
use Cms\Modules\Stats\Models\Stat;
use Cms\Modules\Schedules\Models\Schedule;
use DB;

class StatService
{
    public function __construct(Stat $stat, Schedule $schedule)
    {
        $this->model = $stat;
        $this->schedule = $schedule;
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

        $columns = Schema::getColumnListing('stats');

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


public function findOurSchedule($home, $away)
    {
        return DB::table('schedules')
	        ->selectRaw('schedules.id one_game_id, schedules.schedule_at, hometeams.name home, hometeams.team_id home_id, awayteams.team_id away_id, awayteams.name away')
	        ->join('teams as hometeams', 'schedules.home_id', '=', 'hometeams.team_id')
	        ->join('teams as awayteams', 'schedules.visitor_id', '=', 'awayteams.team_id')
	        ->where('hometeams.name', '=', $home)
	        ->where('awayteams.name',  '=', $away)
	        ->first();
    }



    public function findByPGT($player_id, $game_id, $stat_type)
    {
        return $this->model->where('player_id', '=', $player_id)
                            ->where('game_id', '=', $game_id)
                            ->where('stat_type', '=', $stat_type)
                            ->first();
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