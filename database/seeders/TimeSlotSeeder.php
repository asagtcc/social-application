<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slots = [
            ['label' => '08:30 PM', 'time' => '08:30:00'],
            ['label' => '17:00 PM',  'time' => '17:00:00'],
        ];

        foreach ($slots as $slot) {
            TimeSlot::updateOrCreate(['time' => $slot['time']], $slot);
        }
    }
}
