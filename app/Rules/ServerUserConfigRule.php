<?php

namespace App\Rules;

class ServerUserConfigRule extends ConfigRule
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
            'full_name' => 'required|min:3',
            'email' => 'email',
            'isolated' => 'required|boolean',
            'password' => 'required|min:6',
            'mysql_password' => 'required|min:6',
            'auth_keys' => 'array'
        ];
    }
}
