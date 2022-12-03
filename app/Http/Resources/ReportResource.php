<?php

namespace App\Http\Resources;

use App\Models\report;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'game_id' => $this->game_id,
            'name' => $this->name,
            'email' => $this->email,
            'loi' => $this->loi,
            'motaloi' => $this->motaloi,
            'trangthai' => $this->trangthai,
            'tengame' => report::with('game')->find($this->id)->game->tengame
        ];
    }
}
