<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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

        // $this->renderable(function (ThrottleRequestsException $e, $request) {
        //     return response()->json(['message'=>'Too Many requests u have to wait 2 minutes','status' => 404],404);
        // });


         $this->renderable(function (HttpException $e, $request) {
             if($e->getStatusCode() == 404){
                return response()->view('front.error_404', [], 404);
             }
             if($e->getStatusCode() == 403){
                return response()->view('front.error_403', [], 403);

             }
        });

    }
}