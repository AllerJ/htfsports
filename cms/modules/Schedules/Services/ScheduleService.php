<?php

namespace Cms\Modules\Schedules\Services;

use Config;
use Cms\Modules\Schedules\Models\Schedule;

class ScheduleService
{
    public function __construct(Schedule $schedule)
    {
        $this->model = $schedule;
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

        $columns = Schema::getColumnListing('schedules');

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
    
    public function onDay($gameDayStart, $gameDayEnd)
    {
        $gametime = [$gameDayStart, $gameDayEnd];
        $query = $this->model->whereBetween('schedule_at', $gametime)->orderBy('schedule_at');
        return $query;
    }
    
    public function byId($schedule_id)
    {
        $query = $this->model->where('schedule_id', '=', $schedule_id)->orderBy('schedule_at');
        return $query;
    }

    

}