<?php

namespace Cms\Modules\Venues\Controllers;

use Cms;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Venues\Services\VenueService;
use Cms\Modules\Venues\Requests\VenueCreateRequest;
use Cms\Modules\Venues\Requests\VenueUpdateRequest;

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
        return view('venues::venues.index')
            ->with('pagination', $venues->render())
            ->with('venues', $venues);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $venues = $this->service->search($request->search);
        return view('venues::venues.index')
            ->with('term', $request->search)
            ->with('pagination', $venues->render())
            ->with('venues', $venues);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('venues::venues.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\VenueCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VenueCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result) {
            Cms::notification('Successfully created', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/venues/'.$result->id.'/edit');
        }

        Cms::notification('Failed to create', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/venues');
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
        return view('venues::venues.show')->with('venue', $venue);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $venue = $this->service->find($id);
        return view('venues::venues.edit')->with('venue', $venue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\VenueUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VenueUpdateRequest $request, $id)
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
            return redirect(config('cms.backend-route-prefix', 'cms').'/venues');
        }

        Cms::notification('Failed to delete', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/venues');
    }
}
