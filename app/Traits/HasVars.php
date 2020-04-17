<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait HasVars
{
    /**
     * Set a variable.
     *
     * @param string $key
     * @param mixed $value
     * @param boolean $override
     * @return void
     */
    public function setVar($key, $value)
    {
        $vars = $this->vars;

        Arr::set($vars, $key, $value);

        $this->vars = $vars;

        return $this;
    }

    /**
     * Returns a variable.
     *
     * @param string $key
     * @return mixed
     */
    public function getVar($key, $default = null)
    {
        return Arr::get($this->vars, $key, $default);
    }

    /**
     * Hide passwords.
     *
     * @return void
     */
    public function getSecureVarsAttribute()
    {
        return $this->hidePasswords($this->vars);
    }

    /**
     * Hide passwords with stars in array.
     *
     * @param array $vars
     * @return arrau $vars
     */
    private function hidePasswords($vars)
    {
        if (is_array($vars)) {
            foreach ($vars as $key => $item) {
                if (!is_array($item)) {
                    if (strpos($key, 'password') !== false || strpos($key, '_pass') !== false || strpos($key, '_secret') !== false) {
                        $vars[$key] = '******';
                    }
                } else {
                    $vars[$key] = $this->hidePasswords($item);
                }
            }
        }

        return $vars;
    }

    /**
     * Returns all default and optional vars.
     *
     * @return array
     */
    public function getAllVars()
    {
        return $this->getDefaultVars();
    }

    /**
     * Returns the default vars attribute.
     *
     * @return string
     */
    public function getDefaultVarsAttribute()
    {
        return json_encode($this->getAllVars());
    }
}
