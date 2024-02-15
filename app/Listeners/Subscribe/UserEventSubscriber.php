<?php

namespace App\Listeners\Subscribe;

use App\Events\Auth\SendEmailVerificationEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

class UserEventSubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handleUserRegistered(Registered $event): void
    {
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $this->handleEmailVerification($event);
        }
    }

    public function handleEmailVerification(object $event): void
    {
        $event->user->sendEmailVerificationNotification();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Registered::class => 'handleUserRegistered',
            SendEmailVerificationEvent::class => 'handleEmailVerification',
        ];
    }
}
