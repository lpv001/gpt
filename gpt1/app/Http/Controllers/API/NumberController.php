<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use Validator;

use App\CodeData;

class NumberController extends Controller
{
    /**
     *
     * @return Response
     */
    public function clearCodes($id)
    {
        $coder = new CodeData();
        $coder->devClearCode($id);
    }
    
    
    /**
     *
     * @return Response
     */
    public function generateCodes(Request $request)
    {
        $id = $request->input('id');
        $is_debug = $request->input('is_debug');
        $is_cron = $request->input('is_cron');
        
        $coder = new CodeData();
        $coder->runCodeGenerator($id, $is_cron, $is_debug);
    }
}
