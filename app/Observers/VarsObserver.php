<?php

namespace App\Observers;

use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class VarsObserver
{
    /**
     * Handle the "creating" event.
     *
     * @param  mixed $item
     * @return void
     */
    public function creating(Model $item)
    {
        $item->vars = array_merge($item->getDefaultVars(), is_array($item->vars) ? $item->vars : []);
    }

    /**
     * Handle the "updating" event.
     *
     * @param  mixed $item
     * @return void
     */
    public function updating(Model $item)
    {
        $originalVars = $item->getOriginal('vars');
        $updatedVars = array_merge($item->getDefaultVars(), is_array($item->vars) ? $item->vars : []);
        
        // Prevent name update
        if (isset($originalVars['name'])) $updatedVars['name'] = $originalVars['name'];
        if (isset($originalVars['hostname'])) $updatedVars['hostname'] = $originalVars['hostname'];
        
        $item->vars = $updatedVars;
    }
}
