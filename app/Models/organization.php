<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class organization extends Model  implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasSlug, SoftDeletes, InteractsWithMedia;
     protected $fillable = [
        'slug', 
        'name',
        'description',
        'is_active',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    protected $casts = [
        'is_active' => 'boolean',
    ]; 
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

  
}
