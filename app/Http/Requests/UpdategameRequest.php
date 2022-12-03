<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdategameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tengame' => 'string',
            'id_theloai' => 'integer|exists:theloaigames,id',
            'link_game' => 'string',
            'mota' => 'string',
            'thumb_image' => 'string',
            'image1' => 'string',
            'image2' => 'string',
            'image3' => 'string',
            'image4' => 'string',
            'gh_dotuoi' => 'string',
            'trangthai' => 'integer',
            'soluotchoi' => 'integer',
            'like' => 'integer',
            'unlike' => 'integer'
        ];
    }
}
