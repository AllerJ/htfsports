<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Venues\Services\VenueService;

class VenuesController extends Controller
{
    public function __construct(VenueService $venueService)
    {
        $this->service = $venueService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $venues = $this->service->paginated();
        return view('cms-frontend::venues.all')->with('venues', $venues);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $venue = $this->service->find($id);
        return view('cms-frontend::venues.show')->with('venue', $venue);
    }
}
