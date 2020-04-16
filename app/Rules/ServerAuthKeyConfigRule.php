<?php

namespace App\Rules;

class ServerAuthKeyConfigRule extends ConfigRule
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
            'name' => 'required|min:3',
            'key' => 'required|min:250'
        ];
    }
}
