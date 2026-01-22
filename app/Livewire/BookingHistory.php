<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\RoomBooking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingHistory extends Component
{
    public function mount()
    {
        \App\Models\Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function render()
    {
        $rideBookings = Booking::with(['ticketType', 'ride'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->booking_group_id ?? 'SINGLE-' . $item->id;
            });

        $roomBookings = RoomBooking::with('room')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->booking_group_id ?? 'SINGLE-' . $item->id;
            });

        $parkingBookings = \App\Models\ParkingBooking::with('slot')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.booking-history', [
            'rideBookings' => $rideBookings,
            'roomBookings' => $roomBookings,
            'parkingBookings' => $parkingBookings,
        ])->layout('layouts.app');
    }
}
