<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'username' => $this->user->username,
            'name' => $this->user->name,
            'avatar' => $this->user->avatar_url,
            'game_id' => $this->game_id,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'replies' => ReplyResource::collection(Comment::with('reply')->find($this->id)->reply)
        ];
    }
}
