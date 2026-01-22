<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use Livewire\Component;

class StaffAdminChat extends Component
{
    public $message;

    public function mount()
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff'])) {
            return redirect()->route('dashboard');
        }

        auth()->user()->update(['last_chat_read_at' => now()]);
    }

    protected $rules = [
        'message' => 'required|string|max:1000',
    ];

    public function sendMessage($messageText = null)
    {
        $text = $messageText ?? $this->message;

        if (empty($text)) {
            return;
        }

        ChatMessage::create([
            'user_id' => auth()->id(),
            'message' => $text,
        ]);

        auth()->user()->update(['last_chat_read_at' => now()]);

        $this->message = '';
        $this->dispatch('scroll-chat');
    }

    public function render()
    {
        $messages = ChatMessage::with('user')->orderBy('created_at', 'asc')->get();
        return view('livewire.staff-admin-chat', [
            'messages' => $messages
        ])->layout('layouts.app');
    }
}
