<?php

namespace App;

use App\Traits\HasVars;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerFirewallRule extends Model
{
    use HasVars;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'vars' => 'array',
    ];

    /**
     * Returns an array with default firewall rule variables.
     *
     * @return void
     */
    public function getDefaultVars()
    {
        return [
            'name' => $this->name,
            'port' => '22',
            'rule' => 'allow',
            'proto' => 'tcp',
            'from_ip' => 'any'
        ];
    }

    /**
     * Returns firewall rule hosts.
     * 
     * @return HasMany
     */
    public function hosts()
    {
        return $this->belongsToMany(ServerHost::class, 'server_firewall_rule_host', 'rule_id', 'host_id');
    }

    /**
     * Returns the port variable.
     *
     * @return void
     */
    public function getPortAttribute()
    {
        return $this->getVar('port');
    }

    /**
     * Set the port variable.
     *
     * @return void
     */
    public function setPortAttribute($value)
    {
        $this->setVar('port', $value, true, true);
    }
}
