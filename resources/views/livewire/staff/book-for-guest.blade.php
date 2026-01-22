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

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-navy-800 p-8 text-white relative">
            <h2 class="text-3xl font-black tracking-tight mb-2">Book for Guest</h2>
            <p class="text-navy-200 text-sm opacity-80 uppercase tracking-widest font-bold">New Booking Entry</p>
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                </svg>
            </div>
        </div>

        <form wire:submit.prevent="confirmBooking" class="p-8 space-y-12">
            <!-- Section 1: Client Info -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="h-8 w-1 bg-navy-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tight">1. Client Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Client Name with Search -->
                    <div class="relative">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1">Name of
                            Client</label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="searchQuery" wire:keydown.enter.prevent="selectFirstMatch"
                                class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-navy-500 transition-all font-medium text-gray-900"
                                placeholder="Start typing name or email...">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Suggestions -->
                        @if($showSuggestions && !empty($clients))
                            <div
                                class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                                @foreach($clients as $client)
                                    <button type="button"
                                        wire:click="selectClient({{ $client->id }}, '{{ $client->name }}', '{{ $client->email }}')"
                                        class="w-full px-5 py-3 text-left hover:bg-navy-50 transition-colors flex items-center justify-between group">
                                        <div>
                                            <p class="font-bold text-gray-900 group-hover:text-navy-700">{{ $client->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $client->email }}</p>
                                        </div>
                                        <span
                                            class="bg-gray-100 px-2 py-1 rounded text-[10px] font-bold text-gray-400 capitalize">{{ $client->role ?? 'Client' }}</span>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Client Email -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1">Client
                            Email</label>
                        <input type="email" wire:model="clientEmail"
                            class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-navy-500 transition-all font-medium text-gray-900 @error('clientEmail') ring-2 ring-red-500 @enderror"
                            placeholder="Automatically filled or manual entry">
                        @error('clientEmail') <span
                        class="text-red-500 text-xs mt-1 ml-1 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Date Selector -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 ml-1"
                            for="visiting_date">Visiting Date</label>
                        <input type="date" id="visiting_date" wire:model.live="bookingDate" min="{{ date('Y-m-d') }}"
                            class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-navy-500 transition-all font-medium text-gray-900">
                    </div>
                </div>
            </div>

            <!-- Section 2: Ride Tickets -->
            <div class="space-y-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-1 bg-amber-500 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tight">2. Select Ride Tickets</h3>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Section
                            Total</span>
                        <p class="text-xl font-black text-amber-600">BDT {{ number_format($rideTicketTotals) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($rides as $ride)
                        <div
                            class="bg-gray-50 p-4 rounded-2xl flex items-center justify-between border-2 border-transparent hover:border-amber-400 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group cursor-pointer">
                            <div>
                                <p class="font-bold text-gray-900 group-hover:text-amber-700 transition-colors">{{ $ride->name }}</p>
                                <p class="text-xs text-amber-600 font-bold tracking-tight">BDT
                                    {{ number_format($ride->price) }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <input type="number" wire:model.live="selectedRideTickets.{{ $ride->id }}"
                                    class="w-20 px-3 py-2 bg-white border-gray-200 rounded-xl focus:ring-amber-500 focus:border-amber-500 text-center font-bold shadow-sm group-hover:border-amber-300 transition-all"
                                    min="0" placeholder="0">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 3: Rooms -->
            <div class="space-y-6">
                <!-- ... header ... -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-1 bg-emerald-500 rounded-full shadow-lg shadow-emerald-200"></div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tight">3. Select Rooms</h3>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Section
                            Total</span>
                        <p class="text-xl font-black text-emerald-600">BDT {{ number_format($roomTotals) }}</p>
                    </div>
                </div>

                @if($availableRooms->isNotEmpty())
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($availableRooms as $room)
                            <div class="relative group">
                                <input type="checkbox" id="room_{{ $room->id }}" wire:model.live="selectedRooms.{{ $room->id }}"
                                    class="hidden peer">
                                <label for="room_{{ $room->id }}"
                                    class="block p-4 bg-gray-50 border-2 border-transparent rounded-2xl cursor-pointer 
                                    hover:bg-white hover:shadow-xl hover:-translate-y-1 hover:border-emerald-200
                                    peer-checked:border-emerald-500 peer-checked:bg-green-100 peer-checked:hover:bg-green-100 peer-checked:shadow-emerald-100 peer-checked:shadow-2xl peer-checked:-translate-y-1 peer-checked:ring-2 peer-checked:ring-emerald-500 peer-checked:ring-offset-2
                                    transition-all duration-300 ease-out h-full flex flex-col justify-center items-center relative overflow-hidden">
                                    
                                    <!-- Checkmark Icon (Visible only when checked) -->
                                    <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity duration-300">
                                        <div class="bg-emerald-500 text-white rounded-full p-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>

                                    <p class="text-center font-black text-2xl text-gray-900 peer-checked:text-emerald-700 transition-colors">{{ $room->room_number }}</p>
                                    <p class="text-center text-[10px] text-gray-500 uppercase font-bold tracking-widest peer-checked:text-emerald-600/70">
                                        {{ $room->type }}</p>
                                    <div class="mt-2 px-3 py-1 bg-white rounded-full border border-gray-100 peer-checked:border-emerald-200 peer-checked:bg-white/50 transition-colors">
                                        <p class="text-center text-xs text-emerald-600 font-bold">BDT
                                            {{ number_format($room->price_per_12h) }}</p>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-2xl p-8 text-center border-2 border-dashed border-gray-200">
                        <p class="text-gray-400 font-medium">No rooms available for the selected date.</p>
                    </div>
                @endif
            </div>

            <!-- Section 4: Food -->
            <div class="space-y-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-1 bg-purple-500 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tight">4. Add Food</h3>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Section
                            Total</span>
                        <p class="text-xl font-black text-purple-600">BDT {{ number_format($foodTotals) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($dishes as $dish)
                        <div
                            class="bg-gray-50 p-4 rounded-2xl space-y-3 border border-transparent hover:border-purple-200 transition-all">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $dish->name }}</p>
                                    <p class="text-xs text-purple-600 font-bold tracking-tight">BDT
                                        {{ number_format($dish->price) }}</p>
                                </div>
                                <input type="number" wire:model.live="selectedFood.{{ $dish->id }}"
                                    class="w-16 px-2 py-1.5 bg-white border-gray-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 text-center font-bold text-sm"
                                    min="0" placeholder="0">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Total Footer -->
            <div
                class="mt-20 pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
                <div class="text-center md:text-left">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Grand Total Amount</span>
                    <h4 class="text-5xl font-black text-navy-800 tracking-tighter">BDT
                        {{ number_format($overallTotal) }}</h4>
                </div>

                <button type="submit"
                    class="w-full md:w-auto px-12 py-5 bg-navy-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-navy-200 hover:bg-navy-700 hover:-translate-y-1 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Confirm & Create Booking</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </form>
    </div>

    <style>
        .bg-navy-800 {
            background-color: #1a365d;
        }

        .text-navy-200 {
            color: #bee3f8;
        }

        .bg-navy-600 {
            background-color: #2b6cb0;
        }

        .focus\:ring-navy-500:focus {
            --tw-ring-color: #4299e1;
        }

        .bg-navy-50 {
            background-color: #ebf8ff;
        }

        .text-navy-700 {
            color: #2b6cb0;
        }

        .shadow-navy-200 {
            --tw-shadow-color: rgba(190, 227, 248, 0.4);
            shadow-color: var(--tw-shadow-color);
        }

        /* Explicit selected state for Room Selection */
        .peer:checked + label {
            background-color: #dcfce7 !important; /* Slightly green (green-100) */
            border-color: #059669 !important; /* Emerald-600 */
        }
    </style>
</div>