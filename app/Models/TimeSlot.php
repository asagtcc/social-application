<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeSlot extends Model
{
     use HasApiTokens, HasFactory, SoftDeletes;

        protected $fillable = [
        'label',
        'time',
    ];
}
