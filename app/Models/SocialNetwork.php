<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialNetwork  extends Model implements HasMedia
{
    use InteractsWithMedia,SoftDeletes, HasFactory, HasSlug ;
        protected $fillable = [
            'slug', 
            'name', 
            'url', 
            'is_active'
        ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }
    public function getIconUrlAttribute()
    {
        return $this->getFirstMediaUrl('icon') ?: asset('images/default-icon.png');
    }

}
