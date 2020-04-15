<?php

namespace App\Traits;

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
    public function setVar($key, $value, $override = false, $save = false)
    {
        $vars = $this->vars;
        if (!isset($vars[$key]) || $override) {
            $vars[$key] = $value;
        }
        $this->vars = $vars;

        if ($save) {
            $this->save();
        }

        return $this;
    }

    /**
     * Returns a variable.
     *
     * @param string $key
     * @return mixed
     */
    public function getVar($key, $namespace = '', $default = null)
    {
        $vars = $this->vars;

        if (!empty($namespace) && isset($vars[$namespace])) {
            $vars = $vars[$namespace];
        }

        if (is_array($vars) && isset($vars[$key])) {
            return $vars[$key];
        } elseif (is_object($vars) && isset($vars->$key)) {
            return $vars->$key;
        }

        return $default;
    }

    /**
     * Hide passwords.
     *
     * @return void
     */
    public function getSecureVarsAttribute()
    {
        $vars = $this->vars;

        foreach ($vars as $key => $item) {
            if (strpos($key, 'password') !== false || strpos($key, '_pass') !== false || strpos($key, '_secret') !== false) {
                $vars[$key] = '******';
            }
        }

        return $vars;
    }

    /**
     * Returns the default vars attribute.
     *
     * @return string
     */
    public function getDefaultVarsAttribute()
    {
        return json_encode($this->getDefaultVars());
    }
}
