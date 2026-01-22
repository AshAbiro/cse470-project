<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\RoomBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NawabPalace extends Component
{
    public $rooms;
    public $selectedTime;
    public $selectedDuration = 12; // Default 12 hours
    public $bookedRooms = []; // room_ids that are currently booked
    public $hasEntryTicket = false;

    public function mount()
    {
        $this->rooms = Room::all();
        $this->selectedTime = Carbon::now()->format('Y-m-d\TH:i'); // Default current time
        $this->checkAvailability();
        $this->checkEntryTicket();
    }

    public function checkEntryTicket()
    {
        $this->hasEntryTicket = \App\Models\Booking::where('user_id', auth()->id())
            ->whereNotNull('ticket_type_id')
            ->whereHas('ticketType', function ($query) {
                $query->where('name', 'Park Entry Ticket');
            })
            ->whereIn('status', ['pending', 'accepted', 'confirmed'])
            ->exists();
    }

    public function updatedSelectedTime()
    {
        $this->checkAvailability();
    }

    public function updatedSelectedDuration()
    {
        $this->checkAvailability();
    }

    public function checkAvailability()
    {
        $checkIn = Carbon::parse($this->selectedTime);
        $checkOut = $checkIn->copy()->addHours((int) $this->selectedDuration);

        // Find bookings that overlap with the selected range and are accepted
        // Overlap condition: ExistingStart < NewEnd AND ExistingEnd > NewStart
        $this->bookedRooms = RoomBooking::where('status', 'accepted')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in_time', '<', $checkOut)
                    ->where('check_out_time', '>', $checkIn);
            })->pluck('room_id')->toArray();
    }

    public function bookRoom($roomId)
    {
        if (!$this->hasEntryTicket) {
            session()->flash('error', 'You must book a Park Entry Ticket before you can book a room at Nawab Palace.');
            return;
        }

        $checkIn = Carbon::parse($this->selectedTime);

        if ($checkIn->isPast()) {
            session()->flash('error', 'You cannot book a room in the past. Please select a future date and time.');
            return;
        }

        if (in_array($roomId, $this->bookedRooms)) {
            session()->flash('error', 'This room is not available for the selected time.');
            return;
        }

        $room = Room::find($roomId);
        $checkIn = Carbon::parse($this->selectedTime);
        $checkOut = $checkIn->copy()->addHours((int) $this->selectedDuration);

        // Calculate Price: (Duration / 12) * PricePer12h
        $multiplier = (int) $this->selectedDuration / 12;
        $totalPrice = $room->price_per_12h * $multiplier;

        $groupId = 'ROOM-' . strtoupper(bin2hex(random_bytes(4)));

        \App\Models\RoomBooking::create([
            'user_id' => Auth::id(),
            'booking_group_id' => $groupId,
            'room_id' => $roomId,
            'check_in_time' => $checkIn,
            'check_out_time' => $checkOut,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Notify Admin
        \App\Models\Notification::create([
            'user_id' => 1, // Assume admin user ID 1
            'title' => 'New Room Booking Request',
            'message' => Auth::user()->name . ' has requested to book room ' . $room->room_number . '.',
            'type' => 'info',
            'link' => route('admin.bookings.nawab_palace'),
        ]);

        session()->flash('success', 'Room ' . $room->room_number . ' booking request submitted! Please wait for approval.');
        $this->checkAvailability(); // Refresh availability
    }

    public function render()
    {
        return view('livewire.nawab-palace')->layout('layouts.app');
    }
}
