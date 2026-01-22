<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-navy-800 uppercase tracking-tighter">Daily Quote Manager</h2>
                <p class="text-gray-500 font-bold">Manage quotes that appear on the landing page</p>
            </div>
            <a href="{{ auth()->user()->role === 'admin' ? route('home') : route('staff.dashboard') }}"
                class="bg-navy-800 text-white hover:bg-navy-900 font-bold py-3 px-8 rounded-2xl transition shadow-lg active:scale-95 uppercase tracking-widest text-xs">
                Back to Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Form Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 sticky top-24">
                    <h3 class="text-xl font-black text-navy-700 mb-6 uppercase tracking-widest">
                        {{ $editingId ? 'Edit Quote' : 'Add New Quote' }}
                    </h3>

                    <form wire:submit.prevent="save" class="space-y-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Quote
                                Content</label>
                            <textarea wire:model="content" rows="4"
                                class="w-full bg-gray-50 border-gray-200 rounded-2xl focus:ring-navy-500 focus:border-navy-500 font-bold text-gray-700"
                                placeholder="Enter the inspiring quote here..."></textarea>
                            @error('content') <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Author</label>
                            <input type="text" wire:model="author"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold text-gray-700"
                                placeholder="Author name (optional)">
                            @error('author') <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="is_active" id="is_active"
                                class="w-5 h-5 text-navy-600 rounded-lg border-gray-300 focus:ring-navy-500">
                            <label for="is_active"
                                class="text-sm font-black text-gray-600 uppercase tracking-widest cursor-pointer">Active
                                on Website</label>
                        </div>

                        <div class="pt-4 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full bg-navy-600 hover:bg-navy-700 text-white font-black py-4 rounded-2xl transition shadow-lg shadow-navy-100 active:scale-95 uppercase tracking-widest text-xs">
                                {{ $editingId ? 'Update Quote' : 'Post Quote' }}
                            </button>
                            @if($editingId)
                                <button type="button" wire:click="cancel"
                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-500 font-black py-4 rounded-2xl transition active:scale-95 uppercase tracking-widest text-xs">
                                    Cancel
                                </button>
                            @endif
                        </div>
                    </form>

                    @if (session()->has('success'))
                        <div
                            class="mt-6 p-4 bg-green-50 border border-green-200 text-green-600 rounded-2xl text-xs font-bold text-center animate-bounce">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- List Card -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-navy-800 text-white">
                                    <th class="p-6 text-xs font-black uppercase tracking-widest">Quote</th>
                                    <th class="p-6 text-xs font-black uppercase tracking-widest">Author</th>
                                    <th class="p-6 text-xs font-black uppercase tracking-widest">Status</th>
                                    <th class="p-6 text-xs font-black uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($quotes as $quote)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="p-6">
                                            <p class="text-gray-800 font-bold leading-relaxed">"{{ $quote->content }}"</p>
                                        </td>
                                        <td class="p-6">
                                            <span
                                                class="text-sm text-gray-500 font-black uppercase">{{ $quote->author ?? 'Anonymous' }}</span>
                                        </td>
                                        <td class="p-6">
                                            @if($quote->is_active)
                                                <span
                                                    class="px-3 py-1 rounded-full bg-green-100 text-green-600 text-[10px] font-black uppercase tracking-widest">Active</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 rounded-full bg-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="p-6 text-right">
                                            <div class="flex justify-end gap-2 text-xs">
                                                <button wire:click="edit({{ $quote->id }})"
                                                    class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button
                                                    onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                                    wire:click="delete({{ $quote->id }})"
                                                    class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-gray-400 font-bold uppercase tracking-widest">No quotes
                                                    found. Start by adding one!</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>