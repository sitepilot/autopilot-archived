<?php

namespace App\Http\Requests;

use App\Traits\HasState;
use Illuminate\Foundation\Http\FormRequest;

class ServerAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if ($this->method() == 'POST') {
            $rules = [
                'user_id' => 'required|exists:server_users,id,state,' . HasState::getProvisionedIndex(),
            ];
        }

        $rules = array_merge([
            'name' => 'sometimes|unique:server_apps,name',
            'description' => 'sometimes|min:3',
            'config' => 'array',
            'config.domain' => 'sometimes|min:3',
            'config.aliases' => 'array',
            'config.ssl' => 'sometimes|boolean',
            'config.php.version' => 'sometimes|in:74,73',
            'config.wordpress.db_name' => 'sometimes|exists:server_databases,name,state,' . HasState::getProvisionedIndex(),
            'config.wordpress.update_core' => 'sometimes|boolean',
            'config.wordpress.update_plugins' => 'sometimes|boolean',
            'config.wordpress.update_themes' => 'sometimes|boolean',
            'config.wordpress.update_exclude' => 'array',
            'config.wordpress.admin_user' => 'sometimes|min:3',
            'config.wordpress.admin_email' => 'sometimes|email',
            'config.wordpress.admin_pass' => 'sometimes|min:6'
        ], $rules);

        return $rules;
    }
}
