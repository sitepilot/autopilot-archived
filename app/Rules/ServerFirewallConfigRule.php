<?php

namespace App\Rules;

class ServerFirewallConfigRule extends ConfigRule
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
            'port' => 'required|numeric',
            'rule' => 'required',
            'proto' => 'required|in:tcp,udp',
            'from_ip' => 'required'
        ];
    }
}
