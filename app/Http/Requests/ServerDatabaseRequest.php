<?php

namespace App\Http\Requests;

use App\Traits\HasState;
use Illuminate\Foundation\Http\FormRequest;

class ServerDatabaseRequest extends FormRequest
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
                'user_id' => 'required_without:app_id|exists:server_users,id,state,' . HasState::getProvisionedIndex(),
                'app_id' => 'required_without:user_id|exists:server_apps,id,state,' . HasState::getProvisionedIndex()
            ];
        }

        $rules = array_merge([
            //
        ], $rules);

        return $rules;
    }
}
