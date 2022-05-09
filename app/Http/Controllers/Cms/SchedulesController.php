<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Schedules\Services\ScheduleService;

class SchedulesController extends Controller
{
    public function __construct(ScheduleService $scheduleService)
    {
        $this->service = $scheduleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $schedules = $this->service->paginated();
        return view('cms-frontend::schedules.all')->with('schedules', $schedules);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = $this->service->find($id);
        return view('cms-frontend::schedules.show')->with('schedule', $schedule);
    }
}
