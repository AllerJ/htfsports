<?php

namespace Cms\Modules\Schedules\Controllers;

use Cms;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Schedules\Services\ScheduleService;
use Cms\Modules\Schedules\Requests\ScheduleCreateRequest;
use Cms\Modules\Schedules\Requests\ScheduleUpdateRequest;
use Cms\Modules\Schedules\Models\Schedule;
use Carbon\Carbon;
use DB;

class SchedulesController extends Controller
{
    public function __construct(ScheduleService $scheduleService, Schedule $this_schedule)
    {
        $this->service = $scheduleService;
        $this->schedule = $this_schedule;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        echo "https://api.mysportsfeeds.com/".config('app.msf_ver')."/pull/nfl/".config('app.msf_year')."-".config('app.msf_season')."/games.json";
        echo "
        
        ";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.mysportsfeeds.com/".config('app.msf_ver')."/pull/nfl/".config('app.msf_year')."-".config('app.msf_season')."/games.json");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, "gzip");
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Authorization: Basic " . config('app.msf_api')
		]);
		$resp = curl_exec($ch);
		
		if (!$resp) {
			die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
		} else {
			$schedules = json_decode($resp, true);
		//	print_r($schedules);
		}
		curl_close($ch);
        
        foreach($schedules['games'] as $schedule) {
             
             
             	
				$game = $schedule['schedule'];

 if($game['id'] > 56980 ) {
	


                $game_at = str_replace("T"," ", $game['startTime']);

				$game_at = explode('.', $game_at);


                $game_at = Carbon::parse($game_at[0]);

				$game_at = $game_at->format('Y-m-d H:i:s');

				

                $payload = array();                
                $payload['schedule_id'] = $game['id'];
                $payload['schedule_at'] = $game_at;
                $payload['venue'] = $game['venue']['name'];
                $payload['home_id'] = $game['homeTeam']['id'];
                $payload['visitor_id'] = $game['awayTeam']['id'];
                $payload['league_id'] = '1';
                print_r($payload);
   
                echo "
                
                
                --------
                
                
                ";


                $this->schedule->updateOrCreate(['schedule_id' => $game['id']], $payload);
 //              $this->service->create($payload);
               } 
            
        }
        
        

/*


        
        $schedules = $this->service->paginated();
        return view('schedules::schedules.index')
            ->with('pagination', $schedules->render())
            ->with('schedules', $schedules);
            
          
*/



    }


 


    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $schedules = $this->service->search($request->search);
        return view('schedules::schedules.index')
            ->with('term', $request->search)
            ->with('pagination', $schedules->render())
            ->with('schedules', $schedules);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('schedules::schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ScheduleCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScheduleCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result) {
            Cms::notification('Successfully created', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/schedules/'.$result->id.'/edit');
        }

        Cms::notification('Failed to create', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/schedules');
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
        return view('schedules::schedules.show')->with('schedule', $schedule);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $schedule = $this->service->find($id);
        return view('schedules::schedules.edit')->with('schedule', $schedule);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ScheduleUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ScheduleUpdateRequest $request, $id)
    {
        $result = $this->service->update($id, $request->except(['_token', '_method']));

        if ($result) {
            Cms::notification('Successfully updated', 'success');
            return back();
        }

        Cms::notification('Failed to update', 'warning');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            Cms::notification('Successfully deleted', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/schedules');
        }

        Cms::notification('Failed to delete', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/schedules');
    }
}
