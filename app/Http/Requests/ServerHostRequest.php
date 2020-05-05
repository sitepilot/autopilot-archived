<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServerHostRequest extends FormRequest
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
                'group_id' => 'required|exists:server_groups,id',
                'config.ansible_ssh_host' => 'required|min:3'
            ];
        }

        $rules = array_merge([
            'client_id' => 'sometimes|exists:clients,id',
            'description' => 'sometimes|min:3',
            'config' => 'array',
            'config.ansible_connection' => 'sometimes|in:ssh',
            'config.ansible_ssh_host' => 'sometimes|min:3',
            'config.ansible_ssh_port' => 'sometimes|numeric',
            'config.ansible_ssh_user' => 'sometimes|min:3',
            'config.ansible_ssh_private_key_file' => 'sometimes',
            'config.ansible_ssh_public_key_file' => 'sometimes',
            'config.ansible_python_interpreter' => 'sometimes',
            'config.ansible_ssh_common_args' => 'sometimes',
            'config.admin_pass' => 'sometimes|min:6',
            'config.mysql_root_pass' => 'sometimes|min:6',
            'config.pma_blowfish_secret' => 'sometimes|min:32|max:64',
            'config.swap_path' => 'sometimes',
            'config.swap_size' => 'sometimes|numeric',
            'config.swap_swappiness' => 'sometimes|numeric'
        ], $rules);

        return $rules;
    }
}
