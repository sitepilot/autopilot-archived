<?php

namespace App\Rules;

use App\Traits\HasState;

class ServerAppConfigRule extends ConfigRule
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
            'domain' => 'required',
            'aliases' => 'array',
            'ssl' => 'required|boolean',
            'wordpress.db_name' => 'exists:server_databases,name,state,' . HasState::getProvisionedIndex(),
            'wordpress.update_core' => 'boolean',
            'wordpress.update_plugins' => 'boolean',
            'wordpress.update_themes' => 'boolean',
            'wordpress.update_exclude' => 'array',
            'wordpress.admin_user' => 'nullable|min:3',
            'wordpress.admin_email' => 'nullable|email',
            'wordpress.admin_pass' => 'nullable|min:6'
        ];
    }
}
