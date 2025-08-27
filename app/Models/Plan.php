<?php

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasApiTokens, HasFactory, HasSlug, SoftDeletes;
    protected $fillable = [
        'slug',
        'name',
        'posts_per_month',
        'reels_per_month',
        'stories_per_month',
        'price',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'subscriptions')
                    ->withPivot(['is_active', 'starts_at', 'ends_at']) 
                    ->withTimestamps();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
