<?php

namespace App\Livewire;

use App\Models\Quote;
use Livewire\Component;

class ManageQuotes extends Component
{
    public $content;
    public $author;
    public $is_active = true;
    public $editingId;

    public function mount()
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'staff') {
            abort(403);
        }
    }

    protected $rules = [
        'content' => 'required|string|max:500',
        'author' => 'nullable|string|max:100',
        'is_active' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $quote = Quote::find($this->editingId);
            $quote->update([
                'content' => $this->content,
                'author' => $this->author,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Quote updated successfully!');
        } else {
            Quote::create([
                'content' => $this->content,
                'author' => $this->author,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Quote added successfully!');
        }

        $this->reset(['content', 'author', 'is_active', 'editingId']);
    }

    public function edit($id)
    {
        $quote = Quote::findOrFail($id);
        $this->editingId = $quote->id;
        $this->content = $quote->content;
        $this->author = $quote->author;
        $this->is_active = $quote->is_active;
    }

    public function delete($id)
    {
        Quote::destroy($id);
        session()->flash('error', 'Quote deleted.');
    }

    public function cancel()
    {
        $this->reset(['content', 'author', 'is_active', 'editingId']);
    }

    public function render()
    {
        return view('livewire.manage-quotes', [
            'quotes' => Quote::orderBy('created_at', 'desc')->get()
        ])->layout('layouts.app');
    }
}