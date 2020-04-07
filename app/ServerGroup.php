<?php

namespace App;

use App\Traits\HasVars;
use App\Traits\Encryptable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerGroup extends Model
{
    use HasVars;
    use SoftDeletes;
    use Encryptable;

    /**
     * The attributes that should be encrypted.
     *
     * @var array
     */
    protected $encryptable = [
        'vars',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'vars' => 'array',
    ];

    /**
     * Returns an array with default group variables.
     *
     * @return void
     */
    public function getDefaultVars()
    {
        return [
            'name' => $this->name,
            'admin' => 'sitepilot',
            'admin_email' => 'support@sitepilot.io',
            'health_email' => 'health@sitepilot.io',
            'timezone' => 'Europe/Amsterdam',
            'timezone_update' => true,
            'php_post_max_size' => '64M',
            'php_upload_max_filesize' => '32M',
            'php_memory_limit' => '512M',
            'pma_version' => '5.0.1',
            'pma_update_version' => false,
            'smtp_relay_host' => 'smtp.eu.mailgun.org',
            'smtp_relay_domain' => 'mg.example.com',
            'smtp_relay_user' => 'postmaster@mg.example.com',
            'smtp_relay_password' => Str::random(12)
        ];
    }

    /**
     * Returns group hosts.
     * 
     * @return HasMany
     */
    public function hosts()
    {
        return $this->hasMany(ServerHost::class, 'group_id');
    }
}
