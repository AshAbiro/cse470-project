<?php

namespace App\Livewire\Staff;

use Livewire\Component;

use App\Models\Dish;
use Livewire\Attributes\Layout;

class ManageFood extends Component
{
    public $name;
    public $quantity;
    public $price;
    public $description;

    public function addFood()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|string|max:255', // e.g. "1 kg", "Large", "2 pieces"
            'price' => 'required|numeric|min:0',
        ]);

        Dish::create([
            'name' => $this->name,
            'quantity_info' => $this->quantity,
            'price' => $this->price,
            'description' => $this->description ?? '',
            'is_available' => true,
        ]);

        $this->reset(['name', 'quantity', 'price', 'description']);
        session()->flash('success', 'Food item added successfully!');
    }

    public function toggleAvailability($dishId)
    {
        $dish = Dish::findOrFail($dishId);
        $dish->update(['is_available' => !$dish->is_available]);

        if (!$dish->is_available) {
            // Create a maintenance report
            \App\Models\FoodMaintenanceReport::create([
                'dish_id' => $dish->id,
                'reason' => 'Out of stock / Staff reported',
                'status' => 'pending',
            ]);

            // Notify Admins
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Food Item Unavailable',
                    'message' => "Staff reported that {$dish->name} is currently out of stock/unavailable.",
                    'type' => 'warning',
                    'link' => route('admin.park_maintenance'),
                ]);
            }
        } else {
            // If marking back to available, mark reports as fixed
            \App\Models\FoodMaintenanceReport::where('dish_id', $dish->id)->update(['status' => 'fixed']);
        }

        session()->flash('success', 'Food availability updated!');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $dishes = Dish::orderBy('created_at', 'desc')->get();
        return view('livewire.staff.manage-food', [
            'dishes' => $dishes
        ]);
    }
}
