<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
class GameResource extends JsonResource
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
            'id_theloai' => $this->id_theloai,
            'thumb_image' => $this->thumb_image,
            'link_game' => $this->link_game,
            'tengame' => $this->tengame,
            'slug' => $this->slug,
            'mota' => $this->mota,
            'soluotchoi' => $this->soluotchoi,
            'gh_dotuoi' => $this->gh_dotuoi,
            'like' => $this->like,
            'unlike' => $this->unlike,
            'image1' => $this->image1,
            'image2' => $this->image2,
            'image3' => $this->image3,
            'image4' => $this->image4,
            'trangthai' => $this->trangthai,
        ];
    }
}
