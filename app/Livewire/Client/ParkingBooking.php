<?php

namespace App\Livewire\Client;

use Livewire\Component;

class ParkingBooking extends Component
{
    public $selectedDate;
    public $selectedTimeSlot;
    public $timeSlots = [];
    public $hasEntryTicket = false;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->timeSlots = [
            '00:00 - 02:00',
            '02:00 - 04:00',
            '04:00 - 06:00',
            '06:00 - 08:00',
            '08:00 - 10:00',
            '10:00 - 12:00',
            '12:00 - 14:00',
            '14:00 - 16:00',
            '16:00 - 18:00',
            '18:00 - 20:00',
            '20:00 - 22:00',
            '22:00 - 00:00'
        ];
        $this->selectedTimeSlot = $this->timeSlots[4]; // Default to 08:00 - 10:00
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

    public function bookSlot($slotId)
    {
        if (!$this->hasEntryTicket) {
            session()->flash('error', 'You must book a Park Entry Ticket before you can book a parking slot.');
            return;
        }

        // Prevent past date/time booking
        $now = now();
        $selectedDate = \Carbon\Carbon::parse($this->selectedDate);

        if ($selectedDate->isPast() && !$selectedDate->isToday()) {
            session()->flash('error', 'Cannot book for a past date.');
            return;
        }

        if ($selectedDate->isToday()) {
            // Extract start time from '08:00 - 10:00'
            $startTimeStr = explode(' - ', $this->selectedTimeSlot)[0];
            $startDateTime = \Carbon\Carbon::parse($this->selectedDate . ' ' . $startTimeStr);

            if ($startDateTime->isPast()) {
                session()->flash('error', 'Cannot book a past time slot.');
                return;
            }
        }

        // Check if already booked (pending, accepted, or confirmed)
        $exists = \App\Models\ParkingBooking::where('parking_slot_id', $slotId)
            ->where('date', $this->selectedDate)
            ->where('time_slot', $this->selectedTimeSlot)
            ->whereIn('status', ['pending', 'accepted', 'confirmed'])
            ->exists();

        if ($exists) {
            session()->flash('error', 'This slot is already reserved or booked for the selected time.');
            return;
        }

        $booking = \App\Models\ParkingBooking::create([
            'user_id' => auth()->id(),
            'parking_slot_id' => $slotId,
            'date' => $this->selectedDate,
            'time_slot' => $this->selectedTimeSlot,
            'status' => 'pending',
            'price' => 150.00 // Fixed price for 2 hours
        ]);

        // Notify Admin (User ID 1)
        \App\Models\Notification::create([
            'user_id' => 1,
            'title' => 'New Parking Booking Request',
            'message' => auth()->user()->name . " has requested Slot " . $booking->slot->slot_number . " for " . $booking->date . ".",
            'type' => 'info',
            'link' => route('admin.booking_requests'),
        ]);

        // Notify Staff
        $staffMembers = \App\Models\User::where('role', 'staff')->get();
        foreach ($staffMembers as $staff) {
            \App\Models\Notification::create([
                'user_id' => $staff->id,
                'title' => 'New Parking Booking Request',
                'message' => auth()->user()->name . " has requested Slot " . $booking->slot->slot_number . " for " . $booking->date . ".",
                'type' => 'info',
                'link' => route('staff.requests'),
            ]);
        }

        session()->flash('success', 'Parking slot booking request submitted!');
    }

    public function render()
    {
        $slots = \App\Models\ParkingSlot::all()->map(function ($slot) {
            $isPast = false;
            $selectedDate = \Carbon\Carbon::parse($this->selectedDate);
            if ($selectedDate->isToday()) {
                $startTimeStr = explode(' - ', $this->selectedTimeSlot)[0];
                $startDateTime = \Carbon\Carbon::parse($this->selectedDate . ' ' . $startTimeStr);
                if ($startDateTime->isPast()) {
                    $isPast = true;
                }
            } elseif ($selectedDate->isPast()) {
                $isPast = true;
            }

            $slot->is_past = $isPast;
            $slot->is_booked = \App\Models\ParkingBooking::where('parking_slot_id', $slot->id)
                ->where('date', $this->selectedDate)
                ->where('time_slot', $this->selectedTimeSlot)
                ->whereIn('status', ['pending', 'accepted', 'confirmed'])
                ->exists();
            return $slot;
        });

        return view('livewire.client.parking-booking', [
            'slots' => $slots
        ]);
    }
}
