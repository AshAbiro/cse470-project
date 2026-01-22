<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\DishBooking;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Create a new booking for a ride
     */
    public function createRideBooking($userId, $rideId, $numberOfTickets, $totalPrice)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id' => $userId,
                'ride_id' => $rideId,
                'number_of_tickets' => $numberOfTickets,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'booking_date' => now(),
            ]);

            // Create notification
            $this->createNotification($userId, 'Booking Created', 'Your ride booking has been created and is pending approval.');

            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create a room booking
     */
    public function createRoomBooking($userId, $roomId, $checkInDate, $checkOutDate, $numberOfGuests, $totalPrice)
    {
        try {
            DB::beginTransaction();

            $booking = RoomBooking::create([
                'user_id' => $userId,
                'room_id' => $roomId,
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'number_of_guests' => $numberOfGuests,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Create notification
            $this->createNotification($userId, 'Room Booking Created', 'Your room booking has been created and is pending approval.');

            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create a dish booking
     */
    public function createDishBooking($userId, $dishId, $quantity, $totalPrice)
    {
        try {
            DB::beginTransaction();

            $booking = DishBooking::create([
                'user_id' => $userId,
                'dish_id' => $dishId,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Create notification
            $this->createNotification($userId, 'Food Order Created', 'Your food order has been created and is pending confirmation.');

            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Confirm a booking
     */
    public function confirmBooking($bookingType, $bookingId)
    {
        try {
            DB::beginTransaction();

            $model = match ($bookingType) {
                'ride' => Booking::find($bookingId),
                'room' => RoomBooking::find($bookingId),
                'dish' => DishBooking::find($bookingId),
                default => null
            };

            if (!$model) {
                throw new \Exception('Booking not found');
            }

            $model->update(['status' => 'confirmed']);

            // Create notification
            $this->createNotification($model->user_id, 'Booking Confirmed', 'Your booking has been confirmed!');

            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking($bookingType, $bookingId, $reason = null)
    {
        try {
            DB::beginTransaction();

            $model = match ($bookingType) {
                'ride' => Booking::find($bookingId),
                'room' => RoomBooking::find($bookingId),
                'dish' => DishBooking::find($bookingId),
                default => null
            };

            if (!$model) {
                throw new \Exception('Booking not found');
            }

            $model->update(['status' => 'cancelled']);

            // Create notification
            $message = 'Your booking has been cancelled.';
            if ($reason) {
                $message .= ' Reason: ' . $reason;
            }
            $this->createNotification($model->user_id, 'Booking Cancelled', $message);

            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create a notification
     */
    public function createNotification($userId, $title, $message)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    /**
     * Get user's total spending
     */
    public function getUserTotalSpending($userId)
    {
        $rideBookings = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->sum('total_price');

        $roomBookings = RoomBooking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->sum('total_price');

        $dishBookings = DishBooking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->sum('total_price');

        return $rideBookings + $roomBookings + $dishBookings;
    }

    /**
     * Get user's booking history
     */
    public function getUserBookingHistory($userId)
    {
        $rideBookings = Booking::where('user_id', $userId)
            ->with('ride')
            ->get()
            ->map(fn($b) => [
                'type' => 'ride',
                'id' => $b->id,
                'name' => $b->ride->name ?? 'Unknown',
                'price' => $b->total_price,
                'date' => $b->booking_date,
                'status' => $b->status,
            ]);

        $roomBookings = RoomBooking::where('user_id', $userId)
            ->with('room')
            ->get()
            ->map(fn($b) => [
                'type' => 'room',
                'id' => $b->id,
                'name' => $b->room->name ?? 'Unknown',
                'price' => $b->total_price,
                'date' => $b->check_in_date,
                'status' => $b->status,
            ]);

        return $rideBookings->concat($roomBookings)->sortByDesc('date');
    }
}
