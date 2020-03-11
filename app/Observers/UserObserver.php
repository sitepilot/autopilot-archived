<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the "creating" event.
     *
     * @param  mixed $item
     * @return void
     */
    public function creating($item)
    {
        if (Auth::user()) {
            if (empty($item->user_id)) {
                $item->user_id = Auth::user()->id;
            }
        }
    }
}
