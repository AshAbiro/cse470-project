<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketType;

class EntryTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TicketType::updateOrCreate(
            ['name' => 'Park Entry Ticket'],
            [
                'price' => 200.00,
                'description' => 'Mandatory entry ticket to access the park facilities and rides.',
                'valid_duration_days' => 1,
            ]
        );
    }
}
