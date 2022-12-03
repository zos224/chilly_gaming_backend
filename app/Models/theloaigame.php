<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class theloaigame extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['id','tentheloai','slug','mota'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('tentheloai')->saveSlugsTo('slug');
    }
}
