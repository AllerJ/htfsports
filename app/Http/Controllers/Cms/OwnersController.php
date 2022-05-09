<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Owners\Services\OwnerService;

class OwnersController extends Controller
{
    public function __construct(OwnerService $ownerService)
    {
        $this->service = $ownerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $owners = $this->service->paginated();
        return view('cms-frontend::owners.all')->with('owners', $owners);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $owner = $this->service->find($id);
        return view('cms-frontend::owners.show')->with('owner', $owner);
    }
}
