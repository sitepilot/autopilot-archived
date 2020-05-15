<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServerHostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $pubKey = file_get_contents(substr($this->getVar('ansible_ssh_public_key_file'), 0, 1) == '/' ? $this->getVar('ansible_ssh_public_key_file') : storage_path("app/" . $this->getVar('ansible_ssh_public_key_file')));

        $return = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'group_id' => $this->group_id,
            'client_id' => $this->client_id,
            'state' => $this->state,
            'state_text' => $this->getStateText(),
            'config' => $this->vars,
            'public_key' => $pubKey,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        return $return;
    }
}
