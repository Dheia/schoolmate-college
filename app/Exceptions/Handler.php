<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Mail;
use App\Mail\ExceptionOccured;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if($this->shouldReport($exception)) {
            if(env('ERROR_REPORTING')) {
                $this->sendEmail($exception);
            }
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            $message = $exception->getMessage();
            if('Unauthenticated.' === $message){
                return response()->json(['error' => $message], 401);
            }else{
                return response()->json(['error' => $message], 500);
            }
            
        }

        if($exception == null) {

            $guard = array_get($exception->guards(), 0);
            
            switch ($guard) {
                case 'admin':
                    $login = 'admin';
                    break;
                default:
                    $login = 'student';
                    break;
            }
            return redirect()->guest(route($login));
        }
        
        return parent::render($request, $exception);
    }

    /**
     * Sends an email to the developer about the exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function sendEmail(Exception $exception)
    {
        try {

            $e       = FlattenException::create($exception);
            $handler = new SymfonyExceptionHandler();
            $html    = $handler->getHtml($e);
            Mail::to('schoolmate-error@tigernethost.com')->send(new ExceptionOccured($html));

        } catch (Exception $ex) {
            dd($ex);
        }
    }
}
