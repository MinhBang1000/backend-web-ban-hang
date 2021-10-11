<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Send Response With Data, Msg, Code 
     */
    public function sendResponse($data, $msg = '', $code = 200){
        $response = [
            'success'=>true,
            'code'=>$code,
            'msg'=>$msg,
            'data'=>$data,
        ];
        return response()->json($response,$code);
    }

    /**
     * Send Error With Msg, Error, Code
     */
    public function sendError($msg, $error = [], $code = 404){
        $response = [
            'success'=>false,
            'code'=>$code,
            'msg'=>$msg,
            'errors'=>$error,
        ];
        return response()->json($response,$code);
    }
}
