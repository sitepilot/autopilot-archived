<?php

namespace App\Traits;

trait DestructiveAction
{
    /**
     * Prepare the action for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'destructive' => true
        ]);
    }
}