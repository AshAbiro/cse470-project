<?php

namespace App\Livewire\Staff;

use Livewire\Component;

use App\Models\Ride;
use App\Models\MaintenanceReport;
use App\Models\Notification;
use App\Models\User;
use Livewire\Attributes\Layout;

class ManageRides extends Component
{
    public $showReportModal = false;
    public $selectedRideId;
    public $selectedRideName;
    public $damageTime;
    public $reason = 'technical';
    public $repairPrice = 0;

    // Add Ride Properties
    public $showAddModal = false;
    public $name;
    public $price;
    public $description;
    public $min_height;
    public $thrill_level = 1;

    public function openAddModal()
    {
        $this->reset(['name', 'price', 'description', 'min_height', 'thrill_level']);
        $this->showAddModal = true;
    }

    public function addRide()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'min_height' => 'nullable|integer|min:0',
            'thrill_level' => 'required|integer|min:1|max:5',
        ]);

        Ride::create([
            'name' => $this->name,
            'ticket_uid' => strtoupper(substr(md5(uniqid()), 0, 8)),
            'price' => $this->price,
            'description' => $this->description,
            'min_height' => $this->min_height,
            'thrill_level' => $this->thrill_level,
            'is_active' => true,
        ]);

        $this->showAddModal = false;
        session()->flash('success', 'New ride added successfully!');
    }

    public function mount()
    {
        $this->damageTime = now()->format('Y-m-d\TH:i');
    }

    public function openReportModal($rideId, $rideName)
    {
        $this->selectedRideId = $rideId;
        $this->selectedRideName = $rideName;
        $this->showReportModal = true;
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
        $this->reset(['selectedRideId', 'selectedRideName', 'reason', 'repairPrice']);
    }

    public function submitReport()
    {
        $this->validate([
            'damageTime' => 'required',
            'reason' => 'required|in:technical,staff_fault,customer_fault',
            'repairPrice' => 'required|numeric|min:0',
        ]);

        $ride = Ride::findOrFail($this->selectedRideId);
        $ride->update(['is_active' => false]);

        MaintenanceReport::create([
            'ride_id' => $this->selectedRideId,
            'damage_time' => $this->damageTime,
            'reason' => $this->reason,
            'repair_price' => $this->repairPrice,
            'status' => 'pending',
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Ride Damage Reported',
                'message' => "Staff reported damage for " . $this->selectedRideName . ". Reason: " . $this->reason . ".",
                'type' => 'warning',
                'link' => route('admin.park_maintenance'),
            ]);
        }

        session()->flash('success', 'Report submitted successfully for ' . $this->selectedRideName);
        $this->closeReportModal();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $rides = Ride::all();
        return view('livewire.staff.manage-rides', [
            'rides' => $rides
        ]);
    }
}
