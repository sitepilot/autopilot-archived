<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasVars
{
    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::updating(function (Model $model) {
            $originalVars = $model->getOriginal('vars');
            $updatedVars = array_merge($model->getDefaultVars(), is_array($model->vars) ? $model->vars : []);
            if (isset($originalVars['name'])) $updatedVars['name'] = $originalVars['name'];
            if (isset($originalVars['hostname'])) $updatedVars['hostname'] = $originalVars['hostname'];
            $model->vars = $updatedVars;
        });

        self::creating(function (Model $model) {
            $model->vars = $vars = array_merge($model->getDefaultVars(), is_array($model->vars) ? $model->vars : []);
            $model->name = (isset($vars['name']) ? $vars['name'] : (isset($vars['hostname']) ? $vars['hostname'] : $model->name));
        });
    }

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
