<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServerDatabaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $return = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'app_id' => $this->app_id,
            'user_id' => $this->user_id,
            'state' => $this->state,
            'state_text' => $this->getStateText(),
            'config' => $this->vars,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        return $return;
    }
}
