<?php

namespace App\Observers;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class SlugName
{
    /**
     * Handle the "creating" event.
     *
     * @param  mixed $item
     * @return void
     */
    public function creating(Model $item)
    {
        $item->name = Str::slug($item->name);
    }

    /**
     * Handle the "updating" event.
     *
     * @param  mixed $item
     * @return void
     */
    public function updating(Model $item)
    {
        $item->name = Str::slug($item->name);
    }
}
