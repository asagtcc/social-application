<?php

namespace Database\Seeders;

use App\Models\SocialNetwork;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SocialNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
 public function run(): void
    {
        $networks = [
            [
                'name'      => 'Facebook',
                'slug'      => 'facebook',
                'url'  => 'https://graph.facebook.com',
                'is_active' => true,
            ],
            [
                'name'      => 'Instagram',
                'slug'      => 'instagram',
                'url'  => 'https://graph.facebook.com', 
                'is_active' => true,
            ],
            [
                'name'      => 'Twitter (X)',
                'slug'      => 'twitter',
                'url'  => 'https://api.twitter.com',
                'is_active' => true,
            ],
            [
                'name'      => 'LinkedIn',
                'slug'      => 'linkedin',
                'url'  => 'https://api.linkedin.com',
                'is_active' => true,
            ],
            [
                'name'      => 'TikTok',
                'slug'      => 'tiktok',
                'url'  => 'https://open-api.tiktokglobalshop.com',
                'is_active' => false,
            ],
            [
                'name'      => 'YouTube',
                'slug'      => 'youtube',
                'url'  => 'https://www.googleapis.com/youtube/v3',
                'is_active' => false,
            ],
        ];

        foreach ($networks as $network) {
            SocialNetwork::updateOrCreate(
                ['slug' => $network['slug']],
                $network
            );
        }
    }
}
