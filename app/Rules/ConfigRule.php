<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ConfigRule implements Rule
{
    private $message = '';
    protected $validations = [];

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
        $vars = json_decode($value, true);
        if (!is_array($vars)) {
            $this->message = 'Invalid JSON configuration format.';
            return false;
        }

        $validator = Validator::make($vars, $this->validations, [
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