<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GorillerForList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'user_name' => isset($this->user) ? $this->user->name : null,
            'is_admin' => isset($this->user) ? $this->user->is_admin : false,
        ];
    }
}