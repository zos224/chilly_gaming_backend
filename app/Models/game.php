<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
class game extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['id','id_theloai', 'thumb_image', 'link_game', 'tengame', 'slug', 'mota', 'soluotchoi', 'image1', 'image2', 'image3', 'image4', 'gh_dotuoi', 'like', 'unlike', 'trangthai'];
    
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('tengame')->saveSlugsTo('slug');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'likes');
    }
}
