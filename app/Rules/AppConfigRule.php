<?php

namespace App\Rules;

use App\Traits\HasState;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AppConfigRule implements Rule
{
    private $message = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validations = [
            'name' => 'required|min:3',
            'domain' => 'required',
            'aliases' => 'required|array',
            'ssl' => 'required|boolean',
            'wordpress.db_name' => 'exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'wordpress.update_core' => 'boolean',
            'wordpress.update_plugins' => 'boolean',
            'wordpress.update_themes' => 'boolean',
            'wordpress.update_exclude' => 'array',
            'wordpress.admin_user' => 'nullable|min:3',
            'wordpress.admin_email' => 'nullable|email',
            'wordpress.admin_pass' => 'nullable|min:6'
        ];

        $vars = json_decode($value, true);
        if (!is_array($vars)) {
            $this->message = 'Invalid JSON configuration format.';
            return false;
        }

        $validator = Validator::make($vars, $validations, [
            'exists' => 'The selected :key is invalid or not provisioned.',
            'required' => 'The :key parameter is required.',
            'array' => 'The :key parameter must be an array.',
            'boolean' => 'The :key parameter must be true or false.',
            'min' => 'The :key parameter must be at least :min characters.',
            'email' => 'The :key must be a valid email address.'
        ]);

        foreach ($validator->errors()->all() as $msg) {
            $this->message .= "<br />$msg";
        }

        return !$validator->fails();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
