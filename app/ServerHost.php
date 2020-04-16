<?php

namespace App;

use Exception;
use App\Traits\HasVars;
use App\Traits\HasState;
use phpseclib\Crypt\RSA;
use App\Traits\Encryptable;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServerHost extends Model
{
    use HasVars;
    use HasState;
    use SoftDeletes;
    use Actionable;
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
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function (ServerHost $host) {
            $count = $host->where('group_id', $host->group_id)->count();
            $host->name = $host->group->name . ($count + 10);
        });

        self::created(function (ServerHost $host) {
            if ($host->getVar('ansible_connection') == 'ssh' && !$host->getVar('ansible_ssh_private_key_file')) {
                $host->generatePrivatePublicKey();
            }
        });

        self::deleting(function (ServerHost $host) {
            if ($host->getVar('ansible_ssh_private_key_file')) {
                Storage::delete($host->getVar('ansible_ssh_private_key_file'));
            }
            if ($host->getVar('ansible_ssh_public_key_file')) {
                Storage::delete($host->getVar('ansible_ssh_public_key_file'));
            }
        });
    }

    /**
     * Returns an array with default host variables.
     *
     * @return void
     */
    public function getDefaultVars()
    {
        return [
            'hostname' => $this->name,
            'ansible_connection' => 'ssh',
            'ansible_ssh_host' => '0.0.0.0',
            'ansible_ssh_port' => '22',
            'ansible_ssh_user' => 'root',
            'ansible_ssh_private_key_file' => null,
            'ansible_ssh_public_key_file' => null,
            'ansible_python_interpreter' => '/usr/bin/python3',
            'ansible_ssh_common_args' => '-o StrictHostKeyChecking=no',
            'autopilot_host' => false,
            'admin_pass' => Str::random(12),
            'mysql_root_pass' => Str::random(12),
            'pma_blowfish_secret' => Str::random(32),
            'swap_path' => '/swapfile',
            'swap_size' => '1024',
            'swap_swappiness' => '60',
            'firewall' => [],
            'auth_keys' => []
        ];
    }

    /**
     * Generate a SSH public / private key pair.
     *
     * @return void
     */
    public function generatePrivatePublicKey()
    {
        try {
            $rsa = new RSA();
            $rsa->setPrivateKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
            $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
            $rsa->setComment('autopilot-host-' . $this->id . '@sitepilot.io');
            $keys = $rsa->createKey(4096);

            Storage::put($this->getPrivateKeyPath(), $keys["privatekey"]);
            Storage::put($this->getPublicKeyPath(), $keys["publickey"]);

            chmod($this->getPrivateKeyPath(false), 0600);
            chmod($this->getPublicKeyPath(false), 0600);

            $this->setVar('ansible_ssh_private_key_file', $this->getPrivateKeyPath(), true, true);
            $this->setVar('ansible_ssh_public_key_file', $this->getPublicKeyPath(), true, true);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Returns private key storage path.
     *
     * @return string $path
     */
    public function getPrivateKeyPath($relative = true)
    {
        $path = "keys/" . md5("site-" . $this->id) . ".key";
        if (!$relative) {
            return storage_path("app/" . $path);
        }
        return $path;
    }

    /**
     * Returns public key storage path.
     *
     * @return string $path
     */
    public function getPublicKeyPath($relative = true)
    {
        $path = "keys/" . md5("site-" . $this->id) . ".pub";
        if (!$relative) {
            return storage_path("app/" . $path);
        }
        return $path;
    }

    /**
     * Returns host group.
     *
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(ServerGroup::class, 'group_id');
    }

    /**
     * Returns the client.
     *
     * @return BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Returns host users.
     *
     * @return BelongsTo
     */
    public function users()
    {
        return $this->hasMany(ServerUser::class, 'host_id');
    }

    /**
     * Returns host firewall rules.
     * 
     * @return BelongsToMany
     */
    public function firewallRules()
    {
        return $this->belongsToMany(ServerFirewallRule::class, 'server_firewall_rule_host', 'host_id', 'rule_id');
    }

    /**
     * Returns the host auth keys.
     *
     * @return MorphToMany
     */
    public function authKeys()
    {
        return $this->morphToMany(ServerAuthKey::class, 'keyable', 'server_auth_keyables', 'keyable_id', 'key_id');
    }
}
