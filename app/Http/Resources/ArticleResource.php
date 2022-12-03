<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'thumb_image' => $this->thumb_image,
            'rate' => $this->rate,
            'soluotdanhgia' => $this->soluotdanhgia,
            'created' => $this->created_at,
            'luotxem' => $this->luotxem
        ];
    }
}
