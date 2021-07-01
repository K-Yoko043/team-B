<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'user_id' => $this->user_id,
            'user_name' => isset($this->user) ? $this->user->name : null,
            'tag' => $this->tag,
            'content_text' => $this->content_text,
            'is_bookmark'=>$this->Bookmark->where('user_id',Auth::user()->id)->count(),
        ];
    }
}
