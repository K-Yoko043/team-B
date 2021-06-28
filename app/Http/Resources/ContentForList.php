<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
class ContentForList extends JsonResource
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
            'user_name' => isset($this->user) ? $this->user->name : null,
            'tag' => $this->tag,
            'content_text' => $this->content_text,
            'created_at' => $this->created_at->format('Y/m/d H:i'),
            'user_id' => $this->user_id,
            'own_like_good'=>$this->likes->where('user_id', Auth::user()->id)->where('reaction_no',1)->count(),
            'own_like_heart'=>$this->likes->where('user_id', Auth::user()->id)->where('reaction_no',2)->count(),
            'own_like_check'=>$this->likes->where('user_id', Auth::user()->id)->where('reaction_no',3)->count(),
            'count_good'=>$this->likes->where('reaction_no',1)->count(),
            'count_heart'=>$this->likes->where('reaction_no',2)->count(),
            'count_check'=>$this->likes->where('reaction_no',3)->count(),
            'comment_visusal'=>false,
            'comment_count'=>$this->responds->where('content_id',$this->id)->count(),
        ];
    }
}
