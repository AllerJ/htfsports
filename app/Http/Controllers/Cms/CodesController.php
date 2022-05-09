<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Codes\Services\CodeService;

class CodesController extends Controller
{
    public function __construct(CodeService $codeService)
    {
        $this->service = $codeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $codes = $this->service->paginated();
        return view('cms-frontend::codes.all')->with('codes', $codes);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $code = $this->service->find($id);
        return view('cms-frontend::codes.show')->with('code', $code);
    }
}
