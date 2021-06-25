<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
class NoticeForList extends JsonResource
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
            'content_id'=>$this->content_id,
            'content_userid' => $this->content_userid,
            'content_username'=>$this->content->user->name,
            'respond_userid'=>$this->user_id,
            'respond_username'=>$this->user->name,
        ];
    }
}
