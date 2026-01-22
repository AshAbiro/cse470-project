<?php

namespace App\Livewire\Staff;

use Livewire\Component;

use App\Models\Room;
use App\Models\RoomMaintenanceReport;
use App\Models\Notification;
use App\Models\User;
use Livewire\Attributes\Layout;

class ManageRooms extends Component
{
    public $editingRoomId;
    public $roomNumber;
    public $features;
    public $status;
    public $confirmingDeletionId;

    // Reporting Properties
    public $showStatusModal = false;
    public $reportRoomId;
    public $reportRoomNumber;
    public $reportStatus; // cleaned, unclean, repair_required
    public $availableFeatures = [];
    public $selectedDamagedItems = [];

    public function openStatusModal($roomId)
    {
        $room = Room::findOrFail($roomId);
        $this->reportRoomId = $roomId;
        $this->reportRoomNumber = $room->room_number;
        $this->reportStatus = $room->status ?? 'cleaned';

        // Parse features into array for checkboxes
        $this->availableFeatures = array_map('trim', explode(',', $room->features));
        $this->selectedDamagedItems = [];
        $this->showStatusModal = true;
    }

    public function submitStatusReport()
    {
        $this->validate([
            'reportStatus' => 'required|in:cleaned,unclean,repair_required',
            'selectedDamagedItems' => 'required_if:reportStatus,repair_required|array',
        ]);

        $room = Room::findOrFail($this->reportRoomId);
        $room->update(['status' => $this->reportStatus]);

        if ($this->reportStatus === 'repair_required') {
            $damagedItemsText = implode(', ', $this->selectedDamagedItems);

            RoomMaintenanceReport::create([
                'room_id' => $this->reportRoomId,
                'damaged_items' => $damagedItemsText,
                'status' => 'pending',
            ]);

            // Notify Admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Room Repair Required',
                    'message' => "Staff reported repairs needed for Room #{$this->reportRoomNumber}. Damaged items: {$damagedItemsText}.",
                    'type' => 'danger',
                    'link' => route('admin.park_maintenance'),
                ]);
            }
        }

        $this->closeStatusModal();
        session()->flash('success', 'Status reported successfully!');
    }

    public function closeStatusModal()
    {
        $this->showStatusModal = false;
        $this->reset(['reportRoomId', 'reportRoomNumber', 'reportStatus', 'availableFeatures', 'selectedDamagedItems']);
    }

    public function editRoom($roomId)
    {
        $room = Room::findOrFail($roomId);
        $this->editingRoomId = $roomId;
        $this->roomNumber = $room->room_number;
        $this->features = $room->features;
        $this->status = $room->status ?? 'available';
    }

    // Add Room Properties
    public $showAddModal = false;
    public $newRoomNumber;
    public $newFloor;
    public $newType = 'standard';
    public $newPrice;
    public $newFeatures;

    public function openAddModal()
    {
        $this->reset(['newRoomNumber', 'newFloor', 'newType', 'newPrice', 'newFeatures']);
        $this->showAddModal = true;
    }

    public function addRoom()
    {
        $this->validate([
            'newRoomNumber' => 'required|integer|unique:rooms,room_number',
            'newFloor' => 'required|integer|min:0',
            'newType' => 'required|in:standard,vip',
            'newPrice' => 'required|numeric|min:0',
            'newFeatures' => 'required|string',
        ]);

        Room::create([
            'ticket_uid' => strtoupper(substr(md5(uniqid()), 0, 8)),
            'room_number' => $this->newRoomNumber,
            'floor' => $this->newFloor,
            'type' => $this->newType,
            'price_per_12h' => $this->newPrice,
            'features' => $this->newFeatures,
            'status' => 'available',
        ]);

        $this->showAddModal = false;
        session()->flash('success', 'New room added successfully!');
    }

    public function cancelEdit()
    {
        $this->reset(['editingRoomId', 'roomNumber', 'features', 'status']);
    }

    public function updateRoom()
    {
        $this->validate([
            'roomNumber' => 'required|integer|unique:rooms,room_number,' . $this->editingRoomId,
            'features' => 'required|string',
            'status' => 'required|in:available,maintenance,out_of_order,cleaned,unclean,repair_required',
        ]);

        $room = Room::findOrFail($this->editingRoomId);
        $room->update([
            'room_number' => $this->roomNumber,
            'features' => $this->features,
            'status' => $this->status,
        ]);

        $this->cancelEdit();
        session()->flash('success', 'Room updated successfully!');
    }

    public function confirmDelete($roomId)
    {
        $this->confirmingDeletionId = $roomId;
    }

    public function deleteRoom($roomId)
    {
        Room::findOrFail($roomId)->delete();
        $this->confirmingDeletionId = null;
        session()->flash('success', 'Room deleted successfully!');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $rooms = Room::orderBy('room_number', 'asc')->get();
        return view('livewire.staff.manage-rooms', [
            'rooms' => $rooms
        ]);
    }
}
