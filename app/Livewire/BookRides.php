<?php

namespace App\Livewire;

use App\Models\Ride;
use Livewire\Component;
use Livewire\Attributes\Computed;

class BookRides extends Component
{
    public $rides;
    public $cart = [];
    public $quantities = [];
    public $hasEntryTicket = false;
    public $entryTicketType;
    public $entryTicketQuantity = 1;

    public function mount()
    {
        $this->rides = Ride::all();
        foreach ($this->rides as $ride) {
            $this->quantities[$ride->id] = 1;
        }

        $this->checkEntryTicket();
        $this->entryTicketType = \App\Models\TicketType::where('name', 'Park Entry Ticket')->first();
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

    public function incrementEntryQuantity()
    {
        $this->entryTicketQuantity++;
    }

    public function decrementEntryQuantity()
    {
        if ($this->entryTicketQuantity > 1) {
            $this->entryTicketQuantity--;
        }
    }

    public function bookEntryTicket()
    {
        if ($this->hasEntryTicket) {
            session()->flash('info', 'You already have an entry ticket.');
            return;
        }

        if (!$this->entryTicketType) {
            session()->flash('error', 'Entry ticket type not found. Please contact admin.');
            return;
        }

        // Add to cart if not already there
        foreach ($this->cart as $key => $item) {
            if (isset($item['is_entry_ticket'])) {
                // Update quantity instead of preventing
                $this->cart[$key]['quantity'] += $this->entryTicketQuantity;
                $this->cart[$key]['subtotal'] = $this->cart[$key]['quantity'] * $this->cart[$key]['price'];
                session()->flash('success', 'Entry ticket quantity updated!');
                return;
            }
        }

        $this->cart[] = [
            'id' => $this->entryTicketType->id,
            'uid' => 'ENTRY-' . strtoupper(bin2hex(random_bytes(2))),
            'name' => $this->entryTicketType->name,
            'price' => $this->entryTicketType->price,
            'quantity' => $this->entryTicketQuantity,
            'subtotal' => $this->entryTicketType->price * $this->entryTicketQuantity,
            'is_entry_ticket' => true,
        ];

        session()->flash('success', 'Entry ticket added to your booking list!');
    }

    public function addToCart($rideId)
    {
        if (!$this->hasEntryTicket && !$this->isEntryTicketInCart) {
            session()->flash('entry_warning', 'Select Entry ticket first');
            return;
        }

        $ride = $this->rides->find($rideId);
        $quantity = $this->quantities[$rideId] ?? 1;

        if ($ride && $quantity > 0) {
            // Check if already in cart, update quantity if so
            foreach ($this->cart as $key => $item) {
                if ($item['id'] === $rideId) {
                    $this->cart[$key]['quantity'] += $quantity;
                    $this->cart[$key]['subtotal'] = $this->cart[$key]['quantity'] * $this->cart[$key]['price'];
                    return;
                }
            }

            // Add new item
            $this->cart[] = [
                'id' => $ride->id,
                'uid' => $ride->ticket_uid,
                'name' => $ride->name,
                'price' => $ride->price,
                'quantity' => $quantity,
                'subtotal' => $ride->price * $quantity,
            ];
        }
    }

    public function removeFromCart($index)
    {
        $removedItem = $this->cart[$index] ?? null;

        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); // Re-index array

        // Integrity Check: If entry ticket was removed and no booking exists in DB, clear everything
        if ($removedItem && isset($removedItem['is_entry_ticket'])) {
            if (!$this->hasEntryTicket) {
                $this->cart = [];
                session()->flash('info', 'All rides were removed because the mandatory entry ticket was removed from your list.');
            }
        }
    }

    #[Computed]
    public function total()
    {
        return array_reduce($this->cart, function ($carry, $item) {
            return $carry + $item['subtotal'];
        }, 0);
    }

    #[Computed]
    public function isEntryTicketInCart()
    {
        foreach ($this->cart as $item) {
            if (isset($item['is_entry_ticket'])) {
                return true;
            }
        }
        return false;
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        if (!$this->hasEntryTicket && !$this->isEntryTicketInCart) {
            $this->cart = []; // Double protection: clear cart if somehow bypassed
            session()->flash('entry_warning', 'Select Entry ticket first');
            return;
        }

        $groupId = 'RIDE-' . strtoupper(bin2hex(random_bytes(4)));

        foreach ($this->cart as $item) {
            $bookingData = [
                'user_id' => auth()->id(),
                'booking_group_id' => $groupId,
                'quantity' => $item['quantity'],
                'total_price' => $item['subtotal'],
                'booking_date' => now(),
                'status' => 'pending',
            ];

            if (isset($item['is_entry_ticket'])) {
                $bookingData['ticket_type_id'] = $item['id'];
            } else {
                $bookingData['ride_id'] = $item['id'];
            }

            \App\Models\Booking::create($bookingData);
        }

        // Check if an entry ticket was booked in this session
        $this->checkEntryTicket();

        // Notify Admin
        $count = count($this->cart);
        $totalVal = $this->total;
        $msg = auth()->user()->name . " has made a new ride " . ($count > 1 ? "cluster " : "") . "booking request ($count items). Total: BDT " . number_format($totalVal);

        \App\Models\Notification::create([
            'user_id' => 1,
            'title' => 'New Ride Booking Request',
            'message' => $msg,
            'type' => 'info',
            'link' => route('admin.booking_requests'),
        ]);

        $this->cart = [];
        $this->quantities = [];
        session()->flash('success', 'Booking request submitted successfully! Please wait for approval.');
    }

    public function render()
    {
        $this->entryTicketType = \App\Models\TicketType::where('name', 'Park Entry Ticket')->first();
        return view('livewire.book-rides')->layout('layouts.app');
    }
}
