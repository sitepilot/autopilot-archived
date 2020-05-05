<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServerGroupRequest extends FormRequest
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
                'name' => 'required|min:3|unique:server_groups,name'
            ];
        }

        $rules = array_merge([
            'description' => 'sometimes|min:3',
            'config' => 'array',
            'config.admin' => 'sometimes|min:3',
            'config.admin_email' => 'sometimes|email',
            'config.health_email' => 'sometimes|email',
            'config.cert_email' => 'sometimes|email',
            'config.timezone' => 'sometimes|min:3',
            'config.timezone_update' => 'sometimes|boolean',
            'config.php_post_max_size' => 'sometimes',
            'config.php_upload_max_filesize' => 'sometimes',
            'config.php_memory_limit' => 'sometimes',
            'config.pma_version' => 'sometimes',
            'config.pma_update_version' => 'sometimes|boolean',
            'config.smtp_relay_host' => 'sometimes',
            'config.smtp_relay_domain' => 'sometimes',
            'config.smtp_relay_user' => 'sometimes|email',
            'config.smtp_relay_password' => 'sometimes'
        ], $rules);

        return $rules;
    }
}
