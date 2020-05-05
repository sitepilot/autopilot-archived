<?php

namespace App\Http\Requests;

use App\Traits\HasState;
use Illuminate\Foundation\Http\FormRequest;

class WpInstallRequest extends FormRequest
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
        return [
            'database_id' => 'required|exists:server_databases,id,state,' . HasState::getProvisionedIndex(),
            'admin_user' => 'required|min:3',
            'admin_pass' => 'required|min:6',
            'admin_email' => 'required|email'
        ];
    }
}
