<?php

namespace App\Rules;

class ServerGroupConfigRule extends ConfigRule
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
            'admin' => 'required|min:3',
            'admin_email' => 'required|email',
            'health_email' => 'required|email',
            'cert_email' => 'required|email',
            'timezone' => 'required|min:3',
            'timezone_update' => 'required|boolean',
            'php_post_max_size' => 'required',
            'php_upload_max_filesize' => 'required',
            'php_memory_limit' => 'required',
            'pma_version' => 'required',
            'pma_update_version' => 'required|boolean',
            'smtp_relay_host' => 'required',
            'smtp_relay_domain' => 'required',
            'smtp_relay_user' => 'required|email',
            'smtp_relay_password' => 'required'
        ];
    }
}
