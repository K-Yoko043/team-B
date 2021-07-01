<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class RespondForList extends JsonResource
{
    /**
     * 
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'content_id' => $this->content_id,
            'url' => $this->url,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
        ];
    }
}
 