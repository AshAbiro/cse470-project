<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {
            \App\Models\ParkingSlot::create([
                'slot_number' => str_pad($i + 1000, 4, '0', STR_PAD_LEFT), // e.g. 1001, 1002, ...
                'is_active' => true,
            ]);
        }
    }
}
