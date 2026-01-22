<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogger;

class StaffController extends Controller
{
    public function dashboard()
    {
        return view('staff.dashboard');
    }

    public function my_ratings()
    {
        $staff = Auth::user();
        $ratings = \App\Models\StaffRating::with('user')
            ->where('staff_id', $staff->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('staff.my_ratings', compact('staff', 'ratings'));
    }

    public function requests()
    {
        $rideRequests = Booking::with(['user', 'ride', 'ticketType'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->booking_group_id ?? 'SINGLE-' . $item->id;
            });

        $roomRequests = RoomBooking::with(['user', 'room'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->booking_group_id ?? 'SINGLE-ROOM-' . $item->id;
            });

        $parkingRequests = \App\Models\ParkingBooking::with(['user', 'slot'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark staff notifications as read
        \App\Models\Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('staff.requests', compact('rideRequests', 'roomRequests', 'parkingRequests'));
    }

    public function accept_booking(Request $request, $type, $id)
    {
        if ($type == 'ride') {
            $bookings = Booking::where('booking_group_id', $id)
                ->orWhere('id', str_replace('SINGLE-', '', $id))
                ->get();

            if ($bookings->isEmpty()) {
                return back()->with('error', 'Booking not found.');
            }

            foreach ($bookings as $booking) {
                $booking->update(['status' => 'accepted']);
            }

            // Also accept associated room bookings if they share the group ID
            RoomBooking::where('booking_group_id', $id)
                ->where('status', 'pending')
                ->update(['status' => 'accepted']);

            $firstBooking = $bookings->first();
            $userId = $firstBooking->user_id;

            if ($bookings->count() > 1) {
                $msg = "Your ride cluster booking (" . $bookings->count() . " items) has been accepted by staff!";
            } else {
                $name = $firstBooking->ride ? $firstBooking->ride->name : ($firstBooking->ticketType ? $firstBooking->ticketType->name : 'Ticket');
                $msg = "Your ride booking for " . $name . " has been accepted by staff!";
            }
        } elseif ($type == 'room') {
            $bookings = RoomBooking::where('booking_group_id', $id)
                ->orWhere('id', str_replace('SINGLE-ROOM-', '', $id))
                ->get();

            if ($bookings->isEmpty()) {
                return back()->with('error', 'Booking not found.');
            }

            foreach ($bookings as $booking) {
                $booking->update(['status' => 'accepted']);
            }

            $firstBooking = $bookings->first();
            $userId = $firstBooking->user_id;

            if ($bookings->count() > 1) {
                $msg = "Your room cluster booking (" . $bookings->count() . " rooms) has been accepted by staff!";
            } else {
                $msg = "Your room booking for " . $firstBooking->room->room_number . " has been accepted by staff!";
            }
        } elseif ($type == 'parking') {
            $booking = \App\Models\ParkingBooking::findOrFail($id);
            $booking->update(['status' => 'accepted']);
            $userId = $booking->user_id;
            $msg = "Your parking booking for Slot " . $booking->slot->slot_number . " has been accepted by staff!";
        }

        Notification::create([
            'user_id' => $userId,
            'title' => 'Booking Accepted',
            'message' => $msg,
            'type' => 'success',
            'link' => route('client.booking-history'),
        ]);

        AuditLogger::log('booking.accepted_by_staff', [
            'type' => $type,
            'booking_group_id' => $id,
            'user_id' => $userId,
        ]);

        return back()->with('success', 'Booking accepted and client notified!');
    }

    public function reject_booking(Request $request, $type, $id)
    {
        if ($type == 'ride') {
            $bookings = Booking::where('booking_group_id', $id)
                ->orWhere('id', str_replace('SINGLE-', '', $id))
                ->get();

            if ($bookings->isEmpty()) {
                return back()->with('error', 'Booking not found.');
            }

            foreach ($bookings as $booking) {
                $booking->update(['status' => 'rejected']);
            }

            // Also reject associated room bookings if they share the group ID
            RoomBooking::where('booking_group_id', $id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            $firstBooking = $bookings->first();
            $userId = $firstBooking->user_id;

            if ($bookings->count() > 1) {
                $msg = "Your ride cluster booking (" . $bookings->count() . " items) has been rejected by staff.";
            } else {
                $name = $firstBooking->ride ? $firstBooking->ride->name : ($firstBooking->ticketType ? $firstBooking->ticketType->name : 'Ticket');
                $msg = "Your ride booking for " . $name . " has been rejected by staff.";
            }
        } elseif ($type == 'room') {
            $bookings = RoomBooking::where('booking_group_id', $id)
                ->orWhere('id', str_replace('SINGLE-ROOM-', '', $id))
                ->get();

            if ($bookings->isEmpty()) {
                return back()->with('error', 'Booking not found.');
            }

            foreach ($bookings as $booking) {
                $booking->update(['status' => 'rejected']);
            }

            $firstBooking = $bookings->first();
            $userId = $firstBooking->user_id;

            if ($bookings->count() > 1) {
                $msg = "Your room cluster booking (" . $bookings->count() . " rooms) has been rejected by staff.";
            } else {
                $msg = "Your room booking for " . $firstBooking->room->room_number . " has been rejected by staff.";
            }
        } elseif ($type == 'parking') {
            $booking = \App\Models\ParkingBooking::findOrFail($id);
            $booking->update(['status' => 'rejected']);
            $userId = $booking->user_id;
            $msg = "Your parking booking for Slot " . $booking->slot->slot_number . " has been rejected by staff.";
        }

        Notification::create([
            'user_id' => $userId,
            'title' => 'Booking Rejected',
            'message' => $msg,
            'type' => 'danger',
            'link' => route('client.booking-history'),
        ]);

        AuditLogger::log('booking.rejected_by_staff', [
            'type' => $type,
            'booking_group_id' => $id,
            'user_id' => $userId,
        ]);

        return back()->with('error', 'Booking rejected and client notified.');
    }
}
