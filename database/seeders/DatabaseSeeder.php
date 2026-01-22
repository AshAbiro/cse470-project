<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ride;
use App\Models\TicketType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create exactly 3 Admins
        $admins = [
            ['name' => 'Admin One', 'email' => 'admin1@amusementpark.com', 'designation' => 'General Manager', 'phone' => '01700000001'],
            ['name' => 'Admin Two', 'email' => 'admin2@amusementpark.com', 'designation' => 'Operations Director', 'phone' => '01700000002'],
            ['name' => 'Admin Three', 'email' => 'admin3@amusementpark.com', 'designation' => 'HR Manager', 'phone' => '01700000003'],
        ];

        foreach ($admins as $admin) {
            User::factory()->create([
                'name' => $admin['name'],
                'email' => $admin['email'],
                'phone' => $admin['phone'],
                'designation' => $admin['designation'],
                'role' => 'admin',
                'password' => bcrypt('password'),
                // Using a placeholder image service that generates faces
                'profile_photo_path' => null, // Let Jetstream handle it or leave null for UI Avatar
            ]);
        }

        // 2. Create Staff
        $staffMembers = [
            ['name' => 'Staff One', 'email' => 'staff1@amusementpark.com', 'designation' => 'Senior Ride Operator', 'phone' => '01800000001'],
            ['name' => 'Staff Two', 'email' => 'staff2@amusementpark.com', 'designation' => 'Customer Service Lead', 'phone' => '01800000002'],
            ['name' => 'Staff Three', 'email' => 'staff3@amusementpark.com', 'designation' => 'Safety Inspector', 'phone' => '01800000003'],
            ['name' => 'Staff Four', 'email' => 'staff4@amusementpark.com', 'designation' => 'Ticket Counter', 'phone' => '01800000004'],
        ];

        foreach ($staffMembers as $staff) {
            User::factory()->create([
                'name' => $staff['name'],
                'email' => $staff['email'],
                'phone' => $staff['phone'],
                'designation' => $staff['designation'],
                'role' => 'staff',
                'password' => bcrypt('password'),
            ]);
        }

        // 3. Create Client
        User::factory()->create([
            'name' => 'Taseen',
            'email' => 'aajmhtaseen@gmail.com',
            'role' => 'client',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'John Client',
            'email' => 'client@gmail.com',
            'role' => 'client',
            'password' => bcrypt('password'),
        ]);

        // 4. Create 21 Rides
        $rides = [
            'Thunderbolt',
            'Sky View',
            'Aqua Plunge',
            'Vortex',
            'Gravitron',
            'Speed Demon',
            'Cloud Jumper',
            'Forest Run',
            'Mountain Peak',
            'River Rapid',
            'Haunted Mansion',
            'Space Odyssey',
            'Jungle Safari',
            'Pirate Ship',
            'Tea Cups',
            'Carousel',
            'Bumper Cars',
            'Drop Tower',
            'Swing Ride',
            'Ferris Wheel',
            'Roller Coaster X'
        ];

        foreach ($rides as $index => $rideName) {
            Ride::create([
                'name' => $rideName,
                'ticket_uid' => $this->generateUniqueId(),
                'price' => rand(1, 10) * 100, // Random price 100-1000, divisible by 100
                'description' => 'Experience the thrill of ' . $rideName,
                'image_path' => 'https://picsum.photos/seed/' . ($index + 1) . '/400/300', // Random placeholder image
                'min_height' => rand(100, 150),
                'thrill_level' => rand(1, 5),
                'is_active' => true,
            ]);
        }

        // Seed Rooms (Resort)
        $this->seedRooms();

        // Seed Entry Ticket
        $this->call(EntryTicketSeeder::class);
    }

    private function seedRooms()
    {
        // 2nd Floor (201-210)
        for ($i = 1; $i <= 10; $i++) {
            $roomNumber = 200 + $i;
            $isVip = ($roomNumber == 202 || $roomNumber == 203);

            \App\Models\Room::create([
                'ticket_uid' => $this->generateUniqueId(),
                'room_number' => $roomNumber,
                'floor' => 2,
                'type' => $isVip ? 'vip' : 'standard',
                'price_per_12h' => $isVip ? 5000.00 : 3000.00,
                'features' => $isVip ? 'King Bed, Jacuzzi, Ocean View, Breakfast' : 'Queen Bed, TV, AC, WiFi',
                'rating' => 5,
                'image_path' => 'https://picsum.photos/seed/room_' . $roomNumber . '/400/300',
            ]);
        }

        // 3rd Floor (301-310)
        for ($i = 1; $i <= 10; $i++) {
            $roomNumber = 300 + $i;

            \App\Models\Room::create([
                'ticket_uid' => $this->generateUniqueId(),
                'room_number' => $roomNumber,
                'floor' => 3,
                'type' => 'standard',
                'price_per_12h' => 3000.00,
                'features' => 'Queen Bed, TV, AC, WiFi, Balcony',
                'rating' => 5,
                'image_path' => 'https://picsum.photos/seed/room_' . $roomNumber . '/400/300',
            ]);
        }

        // 5. Create Ticket Types
        TicketType::create([
            'name' => 'Adult Day Pass',
            'price' => 50.00,
            'description' => 'Full access for one adult for one day.',
            'valid_duration_days' => 1,
        ]);

        TicketType::create([
            'name' => 'Child Day Pass',
            'price' => 30.00,
            'description' => 'Full access for one child for one day.',
            'valid_duration_days' => 1,
        ]);
    }

    private function generateUniqueId()
    {
        do {
            $id = strtoupper(Str::random(8));
        } while (Ride::where('ticket_uid', $id)->exists());

        return $id;
    }
}
