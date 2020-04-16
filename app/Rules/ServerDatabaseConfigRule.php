<?php

namespace App\Rules;

class ServerDatabaseConfigRule extends ConfigRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->validations = [
            'name' => 'required|min:3'
        ];
    }
}
