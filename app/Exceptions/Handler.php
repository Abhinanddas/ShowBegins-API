<?php

namespace App\Exceptions;

use App\Http\Helper;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\LoginFailException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\InvalidFormDataException;
use Throwable;
use Illuminate\Support\Facades\Log;

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
        if ($exception instanceof ModelNotFoundException && $request->wantsJson())
        {
            return Helper::prettyApiResponse('Not found', 'error', [], 404);
        }

        if ($this->isHttpException($exception))
        {
            return Helper::prettyApiResponse('Not found', 'error', [], 404);
        }

        if (!isset($request->httpReq) && $exception instanceof \Illuminate\Validation\ValidationException)
        {
            return  Helper::prettyApiResponse($exception->errors(), 'error', [], 422);
        }

        if ($exception instanceof ErrorException)
        {
            return Helper::prettyApiResponse($exception->getMessage(), 'error', [], 500);
        }

        if ($exception instanceof LoginFailException)
        {
            return Helper::prettyApiResponse(
                trans('messages.login_failure'),
                'error',
                [],
                404
            );
        }

        if ($exception instanceof DataNotFoundException)
        {
            return Helper::prettyApiResponse(
                trans('messages.not_found'),
                'error',
                [],
                204
            );
        }

        if ($exception instanceof InvalidFormDataException)
        {
            return Helper::prettyApiResponse($exception->getMessage(), 'error', [], 422);
        }

        if ($exception instanceof QueryException)
        {
            Log::error($exception->getMessage());
            return Helper::prettyApiResponse(trans('messages.query_exception'), 'error', [], 500);
        }


        if ($exception instanceof ModelNotFoundException)

        {
            Log::error($exception->getMessage());
            $errorMessage =  trans('validation.no-model');
            return Helper::prettyApiResponse(trans('messages.general_error'), 'error', [], 500);
        }

        Log::error($exception->getMessage());
        return Helper::prettyApiResponse(status: false, statusCode: 500, message: trans('messages.general_error'));
    }
}
