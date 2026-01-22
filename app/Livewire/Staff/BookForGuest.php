<?php

namespace App\Livewire\Staff;

use Livewire\Component;

use App\Models\User;
use App\Models\TicketType;
use App\Models\Room;
use App\Models\Dish;
use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\DishBooking;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class BookForGuest extends Component
{
    // Client Details
    public $searchQuery = '';
    public $selectedClientId = null;
    public $clientName = '';
    public $clientEmail = '';

    // Booking Details
    public $bookingDate;

    // Rides (Tickets)
    public $selectedRideTickets = []; // [ride_id => quantity]
    public $rideTicketTotals = 0;

    // Rooms
    public $selectedRooms = []; // [room_id => price]
    public $roomTotals = 0;

    // Food
    public $selectedFood = []; // [dish_id => quantity]
    public $foodTotals = 0;

    public $overallTotal = 0;

    public function mount()
    {
        $this->bookingDate = date('Y-m-d');
    }

    public $showSuggestions = false;

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->selectedClientId = null;
            $this->showSuggestions = false;
            return;
        }
        $this->showSuggestions = true;
    }

    public function selectClient($id, $name, $email)
    {
        $this->selectedClientId = $id;
        $this->clientName = $name;
        $this->clientEmail = $email;
        $this->searchQuery = $name;
        $this->showSuggestions = false;
    }

    public function selectFirstMatch()
    {
        $clients = $this->clients;
        if ($clients->isNotEmpty()) {
            $first = $clients->first();
            $this->selectClient($first->id, $first->name, $first->email);
        }
    }

    public function getClientsProperty()
    {
        if (strlen($this->searchQuery) < 2)
            return [];
        return User::where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhere('email', 'like', '%' . $this->searchQuery . '%')
            ->limit(5)
            ->get();
    }

    public function updatedSelectedRideTickets()
    {
        $this->calculateTotals();
    }

    public function updatedSelectedRooms()
    {
        $this->calculateTotals();
    }

    public function updatedSelectedFood()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->rideTicketTotals = 0;
        foreach ($this->selectedRideTickets as $id => $quantity) {
            if ($quantity > 0) {
                $ride = \App\Models\Ride::find($id);
                if ($ride) {
                    $this->rideTicketTotals += $ride->price * $quantity;
                }
            }
        }

        $this->roomTotals = 0;
        foreach ($this->selectedRooms as $id => $selected) {
            if ($selected) {
                $room = Room::find($id);
                if ($room) {
                    $this->roomTotals += $room->price_per_12h;
                }
            }
        }

        $this->foodTotals = 0;
        foreach ($this->selectedFood as $id => $quantity) {
            if ($quantity > 0) {
                $dish = Dish::find($id);
                if ($dish) {
                    $this->foodTotals += $dish->price * $quantity;
                }
            }
        }

        $this->overallTotal = $this->rideTicketTotals + $this->roomTotals + $this->foodTotals;
    }

    public function confirmBooking()
    {
        $this->validate([
            'clientName' => 'required|string|max:255',
            'clientEmail' => 'required|email|max:255',
            'bookingDate' => 'required|date|after_or_equal:today',
        ]);

        if (!$this->selectedClientId) {
            // Your existing user finding logic
            $user = User::where('email', $this->clientEmail)->first();
            if (!$user) {
                $this->addError('clientEmail', 'This email is not registered. Please register the client first or select from the list.');
                return;
            }
            $this->selectedClientId = $user->id;
        }

        $groupId = 'GUEST-' . strtoupper(bin2hex(random_bytes(4)));

        // Create Ride Bookings
        foreach ($this->selectedRideTickets as $id => $quantity) {
            if ($quantity > 0) {
                $ride = \App\Models\Ride::find($id);
                Booking::create([
                    'user_id' => $this->selectedClientId,
                    'booking_group_id' => $groupId,
                    'ride_id' => $id,
                    'ticket_type_id' => null, // Explicitly null as we are booking a ride directly
                    'quantity' => $quantity,
                    'total_price' => $ride->price * $quantity,
                    'booking_date' => $this->bookingDate,
                    'status' => 'accepted', // Staff bookings are auto-accepted
                ]);
            }
        }

        // Create Room Bookings
        foreach ($this->selectedRooms as $id => $selected) {
            if ($selected) {
                $room = Room::find($id);
                RoomBooking::create([
                    'user_id' => $this->selectedClientId,
                    'booking_group_id' => $groupId,
                    'room_id' => $id,
                    'check_in_time' => $this->bookingDate . ' 12:00:00',
                    'check_out_time' => date('Y-m-d', strtotime($this->bookingDate . ' +1 day')) . ' 10:00:00',
                    'status' => 'accepted',
                    'total_price' => $room->price_per_12h,
                ]);
            }
        }

        // Create Food Bookings
        foreach ($this->selectedFood as $id => $quantity) {
            if ($quantity > 0) {
                $dish = Dish::find($id);
                DishBooking::create([
                    'user_id' => $this->selectedClientId,
                    'booking_group_id' => $groupId,
                    'dish_id' => $id,
                    'quantity' => $quantity,
                    'total_price' => $dish->price * $quantity,
                    'booking_date' => $this->bookingDate,
                    'status' => 'accepted',
                ]);
            }
        }

        // Notify Client
        Notification::create([
            'user_id' => $this->selectedClientId,
            'title' => 'Guest Booking Confirmed',
            'message' => "A booking was made for you by staff on " . $this->bookingDate . ". Total: BDT " . $this->overallTotal,
            'type' => 'info',
            'link' => route('client.booking-history'),
        ]);

        session()->flash('message', 'Booking successfully confirmed for ' . $this->clientName);
        return redirect()->route('staff.dashboard');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        //$tickets = TicketType::all(); // Replaced by Rides
        $rides = \App\Models\Ride::where('is_active', true)->get();

        // Rooms available on selected date
        $bookedRoomIds = RoomBooking::where('status', '!=', 'rejected')
            ->whereDate('check_in_time', '<=', $this->bookingDate)
            ->whereDate('check_out_time', '>', $this->bookingDate)
            ->pluck('room_id')
            ->toArray();

        $rooms = Room::whereNotIn('id', $bookedRoomIds)
            ->whereNotIn('status', ['maintenance', 'out_of_order', 'repair_required'])
            ->get();
        $dishes = Dish::where('is_available', true)->get();

        return view('livewire.staff.book-for-guest', [
            'rides' => $rides,
            'availableRooms' => $rooms,
            'dishes' => $dishes,
            'clients' => $this->clients,
        ])->layout('layouts.app');
    }
}
