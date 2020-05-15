<?php

namespace App\Http\Requests;

use App\Traits\HasState;
use Illuminate\Foundation\Http\FormRequest;

class ServerUserRequest extends FormRequest
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
                'host_id' => 'required|exists:server_hosts,id',
            ];
        }

        $rules = array_merge([
            'client_id' => 'sometimes|exists:clients,id',
            'description' => 'sometimes|min:3',
            'config' => 'array',
            'config.full_name' => 'sometimes|min:3',
            'config.email' => 'sometimes|email',
            'config.isolated' => 'sometimes|boolean',
            'config.password' => 'sometimes|min:6',
            'config.mysql_password' => 'sometimes|min:6'
        ], $rules);

        return $rules;
    }
}
