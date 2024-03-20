<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use PDOException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // return parent::render($request, $e);

        $statusCode = 400;
        $responseMsg = $e->getMessage();

        if ($e instanceof NotFoundHttpException)
        {
            $statusCode = 404;

            if (ENV('APP_DEBUG') == false) {
                $responseMsg = "The server returned a '404 Not Found'";
            }
        }
        elseif ($e instanceof \ErrorException)
        {
            $statusCode = 500;

            if (ENV('APP_DEBUG') == false) {
                $responseMsg = "Internal Server Error";
            }
        }
        elseif ( $e instanceof PDOException || $e instanceof QueryException)
        {
            $statusCode = 500;

            if (ENV('APP_DEBUG') == false) {
                $responseMsg = "Database Related Error";
            }
        }

        Log::debug('Error: ' . $this->getExceptionDetail($e));
        $response = [ 'message' => $responseMsg ];
        
        return response($response, $statusCode);
    }

    protected function getExceptionDetail(\Exception $e)
    {
        return $e->getMessage() . ' on ' . $e->getFile() . ' Line: ' . $e->getLine();
    }

}
