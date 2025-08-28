<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response|\Illuminate\Routing\RedirectResponse
     */
    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     // Determine the intended guard based on the request
    //     if ($request->is('admin/*')) {
    //         return redirect()->route('admin.login');
    //     } elseif ($request->is('member/*')) {
    //         return redirect()->route('member.login');
    //     }

    //     // Fallback to the default login route if not admin or member
    //     return redirect()->route('login');
    // }

    protected function unauthenticated($request, AuthenticationException $exception)
{
    if ($request->expectsJson()) {
        return response()->json(['message' => $exception->getMessage()], 401);
    }

    $guard = $exception->guards()[0] ?? null;

    switch ($guard) {
        case 'admin':
            return redirect()->route('admin.login');
        case 'member':
            return redirect()->route('member.login');
        default:
            return redirect()->route('login');
    }
}
}
