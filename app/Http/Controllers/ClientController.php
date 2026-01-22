<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Staff Rating
    public function rate_staff()
    {
        $staff = \App\Models\User::where('role', 'staff')->get();
        $myRatings = \App\Models\StaffRating::where('user_id', auth()->id())->pluck('rating', 'staff_id');

        return view('client.rate_staff', compact('staff', 'myRatings'));
    }

    public function submit_staff_rating(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:0|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        \App\Models\StaffRating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'staff_id' => $request->staff_id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Rating submitted successfully!');
    }

    // Ride Rating
    public function rate_rides()
    {
        $rides = \App\Models\Ride::where('is_active', true)->get();
        $myRatings = \App\Models\RideRating::where('user_id', auth()->id())->pluck('rating', 'ride_id');

        return view('client.rate_rides', compact('rides', 'myRatings'));
    }

    public function submit_ride_rating(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|exists:rides,id',
            'rating' => 'required|integer|min:0|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        \App\Models\RideRating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'ride_id' => $request->ride_id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Rating submitted successfully!');
    }

    // Room Rating
    public function rate_rooms()
    {
        $rooms = \App\Models\Room::all();
        $myRatings = \App\Models\RoomRating::where('user_id', auth()->id())->pluck('rating', 'room_id');

        return view('client.rate_rooms', compact('rooms', 'myRatings'));
    }

    public function submit_room_rating(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'rating' => 'required|integer|min:0|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        \App\Models\RoomRating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'room_id' => $request->room_id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Rating submitted successfully!');
    }
}
