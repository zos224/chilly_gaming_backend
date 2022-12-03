<?php

namespace App\Http\Resources;

use App\Models\game;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'avatar_url' => $this->avatar_url,
            'name' => $this->name,
            'role' => $this->role,
            'social_id' => $this->social_id,
            'likedGames' => GameResource::collection(User::with('game')->find($this->id)->game), 
        ];
    }
}
