<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'plan_id',
        'starts_at',
        'ends_at',
        'is_active'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
