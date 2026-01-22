<?php

namespace App\Livewire\Staff;

use Livewire\Component;
use App\Models\StaffTask;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class MyTasks extends Component
{
    public $staffResponse = '';
    public $activeTaskId = null;

    public function updateStatus($taskId, $status)
    {
        $task = StaffTask::where('staff_id', Auth::id())->findOrFail($taskId);
        $task->update(['status' => $status]);
        session()->flash('success', 'Task status updated to ' . ucwords(str_replace('_', ' ', $status)));
    }

    public function openCompleteModal($taskId)
    {
        $this->activeTaskId = $taskId;
        $this->staffResponse = '';
        $this->dispatch('open-complete-modal');
    }

    public function completeTask()
    {
        $this->validate([
            'staffResponse' => 'required|string|max:1000',
        ]);

        $task = StaffTask::where('staff_id', Auth::id())->findOrFail($this->activeTaskId);
        $task->update([
            'status' => 'completed',
            'staff_response' => $this->staffResponse,
            'completed_at' => now(),
        ]);

        // Notify Admin
        Notification::create([
            'user_id' => $task->admin_id,
            'title' => 'Task Completed: ' . $task->item_name,
            'message' => "Staff member " . Auth::user()->name . " has completed the task: " . $task->description . ". Response: " . $this->staffResponse,
            'type' => 'success',
            'link' => route('admin.park_maintenance'), // Admin can see it here
        ]);

        $this->reset(['activeTaskId', 'staffResponse']);
        $this->dispatch('close-complete-modal');
        session()->flash('success', 'Task marked as completed and admin notified!');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $tasks = StaffTask::where('staff_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.staff.my-tasks', [
            'tasks' => $tasks
        ]);
    }
}
