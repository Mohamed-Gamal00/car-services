<?php

namespace App\Listeners;

use App\Events\UserAuthenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Cart;

class UpdateCartUserId
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(UserAuthenticated $event): void
    {
        $user = $event->user;

        // Check if the user is authenticated via the 'web' guard
        if (Auth::guard('web')->check()) {
            Cart::where('user_id', null)
                ->where('cookie_id', request()->cookie('cart_id'))
                ->update(['user_id' => $user->id]);
        }
    }
}
