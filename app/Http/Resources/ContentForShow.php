<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentForShow extends JsonResource
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
            'user_name' => isset($this->user) ? $this->user->name : null,
            'tag' => $this->tag,
            'content_text' => $this->content_text,
            'id' => $this->id,
            'created_at' => $this->created_at->format('Y/m/d H:i'),
            'user_id' => $this->user_id,
        ];
    }
}
