<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::where('role', 'client')->get();
        $rides = \App\Models\Ride::all();
        $rooms = \App\Models\Room::all();
        $ticketTypes = \App\Models\TicketType::all();

        // 1. Seed Dishes
        $dishes = [
            ['name' => 'Monster Burger', 'price' => 450],
            ['name' => 'Special Pizza', 'price' => 800],
            ['name' => 'Vanilla Shake', 'price' => 250],
            ['name' => 'Caramel Popcorn', 'price' => 150],
            ['name' => 'Grilled Chicken', 'price' => 600],
        ];

        foreach ($dishes as $d) {
            \App\Models\Dish::create([
                'name' => $d['name'],
                'price' => $d['price'],
                'description' => 'Delicious ' . $d['name'],
            ]);
        }
        $allDishes = \App\Models\Dish::all();

        // 2. Seed bookings for the last 30 days
        $now = now();
        for ($i = 0; $i < 30; $i++) {
            $date = (clone $now)->subDays($i);

            // Random number of transactions per day
            $transactions = rand(5, 15);

            for ($j = 0; $j < $transactions; $j++) {
                $user = $users->random();

                // Randomly pick a category to book
                $category = rand(1, 5);

                switch ($category) {
                    case 1: // Ride
                        $ride = $rides->random();
                        \App\Models\Booking::create([
                            'user_id' => $user->id,
                            'ride_id' => $ride->id,
                            'ticket_type_id' => null,
                            'quantity' => rand(1, 4),
                            'total_price' => $ride->price * rand(1, 4),
                            'booking_date' => $date,
                            'status' => 'confirmed'
                        ]);
                        break;
                    case 2: // Room
                        $room = $rooms->random();
                        \App\Models\RoomBooking::create([
                            'user_id' => $user->id,
                            'room_id' => $room->id,
                            'check_in_time' => $date,
                            'check_out_time' => (clone $date)->addDays(1),
                            'total_price' => $room->price_per_12h * 2,
                            'status' => 'booked'
                        ]);
                        break;
                    case 3: // Dish
                        $dish = $allDishes->random();
                        \App\Models\DishBooking::create([
                            'user_id' => $user->id,
                            'dish_id' => $dish->id,
                            'quantity' => rand(1, 5),
                            'total_price' => $dish->price * rand(1, 5),
                            'booking_date' => $date,
                            'status' => 'confirmed'
                        ]);
                        break;
                    case 4: // Entry Ticket (for Crowdiest Day)
                        if ($ticketTypes->count() > 0) {
                            $tt = $ticketTypes->random();
                            \App\Models\Booking::create([
                                'user_id' => $user->id,
                                'ticket_type_id' => $tt->id,
                                'ride_id' => null,
                                'quantity' => rand(1, 5),
                                'total_price' => $tt->price * rand(1, 5),
                                'booking_date' => $date,
                                'status' => 'confirmed'
                            ]);
                        }
                        break;
                }
            }
        }
    }
}
