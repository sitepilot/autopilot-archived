<?php

namespace App\Rules;

class ServerHostConfigRule extends ConfigRule
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
            'hostname' => 'required|min:3',
            'ansible_connection' => 'required',
            'ansible_ssh_host' => 'required',
            'ansible_ssh_port' => 'required|numeric',
            'ansible_ssh_user' => 'required',
            'ansible_ssh_private_key_file' => 'required',
            'ansible_ssh_public_key_file' => 'required',
            'ansible_python_interpreter' => 'required',
            'ansible_ssh_common_args' => 'required',
            'admin_pass' => 'required|min:6',
            'mysql_root_pass' => 'required|min:6',
            'pma_blowfish_secret' => 'required|min:32|max:64',
            'swap_path' => 'required',
            'swap_size' => 'required|numeric',
            'swap_swappiness' => 'required|numeric'
        ];
    }
}
