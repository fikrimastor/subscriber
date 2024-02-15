<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\SendEmailVerificationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\EmailVerificationNotificationSentResponse;
use Laravel\Fortify\Fortify;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? new JsonResponse('', 204)
                : redirect()->intended(Fortify::redirects('email-verification'));
        }

        SendEmailVerificationEvent::dispatch($request->user());

        return app(EmailVerificationNotificationSentResponse::class);
    }
}
