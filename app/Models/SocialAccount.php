<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
        protected $fillable = [
            'user_id', 
            'social_network_id', 
            'account_name', 
            'account_id',
            'access_token',
            'refresh_token',
            'expires_at',
            'status',
        ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function socialNetwork()
    {
        return $this->belongsTo(SocialNetwork::class);
    }        
}
