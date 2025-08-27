<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'posts_per_month' => 10,
                'reels_per_month' => 5,
                'stories_per_month' => 20,
                'price' => 5,
            ],
            [
                'name' => 'Standard',
                'posts_per_month' => 30,
                'reels_per_month' => 15,
                'stories_per_month' => 60,
                'price' => 5.2,
            ],
            [
                'name' => 'Premium',
                'posts_per_month' => 100,
                'reels_per_month' => 50,
                'stories_per_month' => 200,
                'price' => 7.2,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
