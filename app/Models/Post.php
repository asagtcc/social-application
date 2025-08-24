<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
   use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'social_account_id',
        'type',
        'content',
        'external_post_id',
        'status',
        'published_at',
    ];


}
