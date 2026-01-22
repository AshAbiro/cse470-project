<?php

namespace App\Livewire;

use App\Models\Ride;
use App\Models\Room;
use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\Notification;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;

class AdminPersonalBooking extends Component
{
    public $selectedRides = []; // [ride_id => quantity]
    public $selectedRoomIds = [];
    public $bookedRoomIds = [];
    public $checkInDate;
    public $checkOutDate;

    public function mount()
    {
        $this->checkInDate = now()->format('Y-m-d\TH:i');
        $this->checkOutDate = now()->addHours(12)->format('Y-m-d\TH:i');

        $rides = Ride::where('is_active', true)->get();
        foreach ($rides as $ride) {
            $this->selectedRides[$ride->id] = 0;
        }

        $this->checkAvailability();
    }

    #[Computed]
    public function rides()
    {
        return Ride::where('is_active', true)->get();
    }

    #[Computed]
    public function rooms()
    {
        return Room::all();
    }

    #[Computed]
    public function totalPrice()
    {
        $total = 0;

        foreach ($this->selectedRides as $id => $qty) {
            if ($qty > 0) {
                $ride = $this->rides->firstWhere('id', $id);
                if ($ride)
                    $total += $ride->price * $qty;
            }
        }

        foreach ($this->selectedRoomIds as $id) {
            $room = $this->rooms->firstWhere('id', $id);
            if ($room) {
                $checkIn = Carbon::parse($this->checkInDate);
                $checkOut = Carbon::parse($this->checkOutDate);
                $hours = max(1, $checkIn->diffInHours($checkOut));
                $multiplier = ceil($hours / 12);
                $total += $room->price_per_12h * $multiplier;
            }
        }

        return $total;
    }

    public function updatedCheckInDate()
    {
        $this->checkAvailability();
    }
    public function updatedCheckOutDate()
    {
        $this->checkAvailability();
    }

    public function checkAvailability()
    {
        $checkIn = Carbon::parse($this->checkInDate);
        $checkOut = Carbon::parse($this->checkOutDate);

        if ($checkIn->gt($checkOut)) {
            $this->bookedRoomIds = $this->rooms->pluck('id')->toArray();
            return;
        }

        $this->bookedRoomIds = RoomBooking::whereIn('status', ['pending', 'accepted', 'confirmed'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in_time', '<', $checkOut)
                    ->where('check_out_time', '>', $checkIn);
            })->pluck('room_id')->toArray();

        // Remove rooms that are now booked
        $this->selectedRoomIds = array_diff($this->selectedRoomIds, $this->bookedRoomIds);
    }

    public function incrementRide($id)
    {
        $this->selectedRides[$id] = ($this->selectedRides[$id] ?? 0) + 1;
    }

    public function decrementRide($id)
    {
        if (($this->selectedRides[$id] ?? 0) > 0) {
            $this->selectedRides[$id]--;
        }
    }

    public function selectRoom($id)
    {
        if (in_array($id, $this->bookedRoomIds))
            return;

        if (in_array($id, $this->selectedRoomIds)) {
            $this->selectedRoomIds = array_diff($this->selectedRoomIds, [$id]);
        } else {
            $this->selectedRoomIds[] = $id;
        }
    }

    public function confirmBooking()
    {
        if (empty(array_filter($this->selectedRides)) && empty($this->selectedRoomIds)) {
            session()->flash('error', 'Select at least one item');
            return;
        }

        try {
            $admin = auth()->user();
            $groupId = 'ADMIN-BOOK-' . strtoupper(bin2hex(random_bytes(4)));

            foreach ($this->selectedRides as $id => $qty) {
                if ($qty > 0) {
                    $ride = Ride::find($id);
                    Booking::create([
                        'user_id' => $admin->id,
                        'ride_id' => $id,
                        'booking_group_id' => $groupId,
                        'quantity' => $qty,
                        'total_price' => $ride->price * $qty,
                        'booking_date' => now(),
                        'status' => 'pending',
                    ]);
                }
            }

            foreach ($this->selectedRoomIds as $id) {
                $room = Room::find($id);
                $checkIn = Carbon::parse($this->checkInDate);
                $checkOut = Carbon::parse($this->checkOutDate);
                $hours = max(1, $checkIn->diffInHours($checkOut));
                $multiplier = ceil($hours / 12);

                RoomBooking::create([
                    'user_id' => $admin->id,
                    'booking_group_id' => $groupId,
                    'room_id' => $id,
                    'check_in_time' => $checkIn,
                    'check_out_time' => $checkOut,
                    'total_price' => $room->price_per_12h * $multiplier,
                    'status' => 'pending',
                ]);
            }

            $staff = User::where('role', 'staff')->get();
            foreach ($staff as $s) {
                Notification::create([
                    'user_id' => $s->id,
                    'title' => 'New Admin Booking',
                    'message' => "Admin {$admin->name} sent a new booking order.",
                    'type' => 'info',
                    'link' => route('staff.requests'),
                ]);
            }

            session()->flash('success', 'Booking Order sent');
            $this->reset(['selectedRoomIds']);
            foreach ($this->selectedRides as $k => $v)
                $this->selectedRides[$k] = 0;

        } catch (\Exception $e) {
            session()->flash('error', 'Error! Try again');
        }
    }

    public function render()
    {
        return view('livewire.admin.personal-booking')->layout('layouts.app');
    }
}
