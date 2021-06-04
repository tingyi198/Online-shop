<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use App\Models\LogError;

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
            $user = auth()->user();
            LogError::create([
                'user_id' => $user ? $user->id : 0,
                'exception' => $e->getMessage(),
                'message' => get_class($e),
                'line' => $e->getline(),
                'trace' => array_map(function($trace) {
                    unset($trace['args']);
                    return $trace;
                }, $e->getTrace()),
                'method' => request()->getMethod(),
                'params' => request()->all(),
                'uri' => request()->getPathInfo(),
                'user_agent' => request()->userAgent(),
                'header' => request()->headers->all()
            ]);

        });

        $this->renderable(function(Throwable $e) {
            return response()->view('error');
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response('授權失敗', 401);
    }
}
