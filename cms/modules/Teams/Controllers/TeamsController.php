<?php

namespace Cms\Modules\Teams\Controllers;

use Cms;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Teams\Services\TeamService;
use Cms\Modules\Teams\Requests\TeamCreateRequest;
use Cms\Modules\Teams\Requests\TeamUpdateRequest;
use DB;


class TeamsController extends Controller
{
    public function __construct(TeamService $teamService)
    {
        $this->service = $teamService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    
	    $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.mysportsfeeds.com/".config('app.msf_ver')."/pull/nfl/current/team_stats_totals.json");
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
			$teams = json_decode($resp, true);
	//		print_r($teams['teamStatsTotals']);
		}
		curl_close($ch);
        

        



		foreach($teams['teamStatsTotals'] as $team){

			$one_team = $team['team'];

/*
			print_r($one_team);
			
			print_r( $one_team['teamColoursHex'][0]);
			print_r( $one_team['teamColoursHex'][1]);
*/
			echo "
            
            
            --------
            
            
            ";



            $payload = array();                
            $payload['team_id'] = $one_team['id'];
            $payload['league_id'] = '1';
            $payload['season'] = config('app.msf_year');
            
            $payload['name'] = $one_team['name'];
            $payload['market'] = $one_team['city'];
            $payload['alias'] = $one_team['abbreviation'];
            $payload['logo'] = $one_team['officialLogoImageSrc'].'.svg';
            
            $payload['color'] = $one_team['teamColoursHex'][0];
            $payload['color_second'] = $one_team['teamColoursHex'][1];

            print_r($payload);
                
                
//            $this->service->updateOrCreate(['team_id' => $one_team['id']], $payload);
                
            $this->service->create($payload);

            
            sleep(1.5);
            
        }

     
  

/*
        $teams = $this->service->paginated();
        return view('teams::teams.index')
            ->with('pagination', $teams->render())
            ->with('teams', $teams);
*/
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $teams = $this->service->search($request->search);
        return view('teams::teams.index')
            ->with('term', $request->search)
            ->with('pagination', $teams->render())
            ->with('teams', $teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teams::teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\TeamCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TeamCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result) {
            Cms::notification('Successfully created', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/teams/'.$result->id.'/edit');
        }

        Cms::notification('Failed to create', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/teams');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = $this->service->find($id);
        return view('teams::teams.show')->with('team', $team);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = $this->service->find($id);
        return view('teams::teams.edit')->with('team', $team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\TeamUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TeamUpdateRequest $request, $id)
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
            return redirect(config('cms.backend-route-prefix', 'cms').'/teams');
        }

        Cms::notification('Failed to delete', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/teams');
    }
}
