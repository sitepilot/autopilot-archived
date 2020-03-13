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
    }

    /**
     * Returns a variable.
     *
     * @param string $key
     * @return mixed
     */
    public function getVar($key)
    {
        $vars = $this->vars;
        if (isset($vars[$key])) {
            return $vars[$key];
        }
        return null;
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
