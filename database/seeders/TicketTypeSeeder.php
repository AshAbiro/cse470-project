<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketType;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prevent duplicates
        if (TicketType::count() > 0) {
            return;
        }

        TicketType::create([
            'name' => 'Adult Day Pass',
            'price' => 500.00,
            'description' => 'Full access for one adult for one day.',
            'valid_duration_days' => 1,
        ]);

        TicketType::create([
            'name' => 'Child Day Pass',
            'price' => 300.00,
            'description' => 'Full access for one child for one day.',
            'valid_duration_days' => 1,
        ]);

        TicketType::create([
            'name' => 'VIP Fast Pass',
            'price' => 1200.00,
            'description' => 'Skip the line access for all rides.',
            'valid_duration_days' => 1,
        ]);

        TicketType::create([
            'name' => 'Family Bundle (4 Person)',
            'price' => 1500.00,
            'description' => 'Day pass for 2 Adults and 2 Children.',
            'valid_duration_days' => 1,
        ]);
    }
}
