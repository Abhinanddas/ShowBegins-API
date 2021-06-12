<?php

namespace App\Exceptions;

use App\Http\Helper;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return Helper::prettyApiResponse('Not found', 'error', 404);
        }

        if ($this->isHttpException($exception)) {
            return Helper::prettyApiResponse('Not found', 'error', 404);
        }
        return parent::render($request, $exception);

        if (!isset($request->httpReq) && $exception instanceof \Illuminate\Validation\ValidationException) {
            return  Helper::prettyApiResponse($exception->errors(), 'error', 422);
        }

        if($exception instanceof ErrorException){
        }

        $errorMessage =null;
        if ($exception instanceof QueryException) {
            $errorMessage = trans('validation.queryException');
        } else {
            if ($exception instanceof ModelNotFoundException) {
                $errorMessage =  trans('validation.no-model');
            } else {
                $errorMessage = $exception->getMessage() ?? trans('validation.no.model');
            }
        }
        if (isset($errorMessage)) {
            return Helper::prettyApiResponse($errorMessage, 'error', 400);
        }
    }
}
