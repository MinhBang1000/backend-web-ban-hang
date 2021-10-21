<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Laravel\Passport\Exceptions\MissingScopeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        /**
         * NOT FOUND EXCEPTION
         */
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return $this->sendError('Not Found',[],404);
            }
        });

        /**
         * EXCEPTION FOR 403 FORBIDEN BECAUSE ERROR THE SCOPE IN MIDDLEWARE
         */
        $this->renderable(function(HttpException $e, $request){ 
            if ($request->is('api/*')){
                return $this->sendError('You can not do this',[],403);
            }
        });

        /**
         * NON LOGIN EXCEPTION
         */
        $this->renderable(function(RouteNotFoundException $e, $request){
            if ($request->is('api/*')){
                return $this->sendError('You must login to do this',[],401);
            }
        });

    }

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
