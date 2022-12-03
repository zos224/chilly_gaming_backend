<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class report extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'game_id', 'name', 'email', 'loi', 'motaloi', 'trangthai'];

    public function game()
    {
        return $this->belongsTo(game::class);
    }
}
