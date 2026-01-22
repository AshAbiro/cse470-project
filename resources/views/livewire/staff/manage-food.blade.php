<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    <!-- Sticky Back Button -->
    <div class="sticky top-20 z-10 mb-6">
        <a href="{{ route('staff.dashboard') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-full shadow-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all active:scale-95 group">
            <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Form Section -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 sticky top-36">
                <div class="bg-navy-800 p-6 text-white">
                    <h2 class="text-xl font-black uppercase tracking-tight">Add New Food</h2>
                </div>
                <form wire:submit.prevent="addFood" class="p-6 space-y-4">
                    @if (session()->has('success'))
                        <div class="p-3 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Food
                            Name</label>
                        <input type="text" wire:model="name"
                            class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold text-sm"
                            placeholder="e.g. Burger">
                        @error('name') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Quantity
                            Info</label>
                        <input type="text" wire:model="quantity"
                            class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold text-sm"
                            placeholder="e.g. 500g, 1 plate, XL">
                        @error('quantity') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Price
                            (BDT)</label>
                        <input type="number" wire:model="price"
                            class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold text-sm"
                            placeholder="0.00">
                        @error('price') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Description
                            (Optional)</label>
                        <textarea wire:model="description"
                            class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold text-sm"
                            rows="3"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-navy-800 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg hover:bg-navy-900 transition-all active:scale-95">
                        Add Food Item
                    </button>
                </form>
            </div>
        </div>

        <!-- List Section -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-xl font-black text-navy-800 uppercase tracking-tight">Available Food Items</h2>
                    <span
                        class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold">{{ $dishes->count() }}
                        Total</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($dishes as $dish)
                        <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="h-12 w-12 bg-navy-50 rounded-xl flex items-center justify-center text-navy-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-gray-900">{{ $dish->name }}</h3>
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $dish->is_available ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                            {{ $dish->is_available ? 'In Stock' : 'Out of Stock' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">
                                        {{ $dish->quantity_info }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-6">
                                <div class="text-right">
                                    <p class="text-lg font-black text-navy-600">BDT {{ number_format($dish->price) }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold tracking-tighter">Added:
                                        {{ $dish->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <button wire:click="toggleAvailability({{ $dish->id }})"
                                    class="p-2 {{ $dish->is_available ? 'text-rose-600 hover:bg-rose-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition-colors group relative"
                                    title="{{ $dish->is_available ? 'Report Out of Stock' : 'Mark as Available' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                    <span
                                        class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap">
                                        {{ $dish->is_available ? 'Report Out of Stock' : 'Mark Available' }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <p class="text-gray-400 font-bold opacity-50">No food items added yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-navy-800 {
            background-color: #1a365d;
        }

        .text-navy-600 {
            color: #2b6cb0;
        }

        .bg-navy-50 {
            background-color: #f0f4f8;
        }
    </style>
</div>