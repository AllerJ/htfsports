<?php

namespace Cms\Modules\Nfls\Controllers;

use Cms;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Nfls\Services\NflService;
use Cms\Modules\Nfls\Requests\NflCreateRequest;
use Cms\Modules\Nfls\Requests\NflUpdateRequest;

class NflsController extends Controller
{
    public function __construct(NflService $nflService)
    {
        $this->service = $nflService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nfls = $this->service->paginated();
        return view('nfls::nfls.index')
            ->with('pagination', $nfls->render())
            ->with('nfls', $nfls);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $nfls = $this->service->search($request->search);
        return view('nfls::nfls.index')
            ->with('term', $request->search)
            ->with('pagination', $nfls->render())
            ->with('nfls', $nfls);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('nfls::nfls.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\NflCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NflCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result) {
            Cms::notification('Successfully created', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/nfls/'.$result->id.'/edit');
        }

        Cms::notification('Failed to create', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/nfls');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nfl = $this->service->find($id);
        return view('nfls::nfls.show')->with('nfl', $nfl);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nfl = $this->service->find($id);
        return view('nfls::nfls.edit')->with('nfl', $nfl);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\NflUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NflUpdateRequest $request, $id)
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
            return redirect(config('cms.backend-route-prefix', 'cms').'/nfls');
        }

        Cms::notification('Failed to delete', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/nfls');
    }
}
