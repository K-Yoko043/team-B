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
            'goriller_id' => isset($this->goriller) ? $this->goriller->id : null,
            'tag' => $this->tag,
            'title' => $this->title,
            'content_text' => $this->content_text,
        ];
    }
}
