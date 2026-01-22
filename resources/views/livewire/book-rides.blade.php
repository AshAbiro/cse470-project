<div class="py-6 px-4 sm:px-6 lg:px-8"
    x-data="{ showWarning: {{ session()->has('entry_warning') ? 'true' : 'false' }} }"
    x-init="if(showWarning) { setTimeout(() => showWarning = false, 3000) }">
    <div class="max-w-7xl mx-auto">
        @if(session()->has('entry_warning'))
            <div x-show="showWarning" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="fixed top-24 left-1/2 transform -translate-x-1/2 z-[2000] bg-rose-600 text-white px-10 py-5 rounded-3xl shadow-[0_20px_50px_rgba(225,29,72,0.4)] font-black uppercase tracking-widest border-4 border-rose-400/50 flex items-center gap-4">
                <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <span class="text-xl">{{ session('entry_warning') }}</span>
            </div>
        @endif

        @if (!$hasEntryTicket && $entryTicketType)
            <!-- Mandatory Entry Ticket Card -->
            <div class="mb-12 max-w-md mx-auto">
                <div
                    class="bg-gradient-to-br from-green-700 to-green-900 rounded-2xl shadow-2xl overflow-hidden border-4 border-green-500 relative group transform transition hover:scale-[1.01]">
                    <div class="p-6 text-center text-black">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-green-500/30 rounded-full mb-4 outline outline-2 outline-green-400/50 animate-pulse">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black mb-1 uppercase tracking-tighter text-black">Mandatory Entry Ticket</h3>
                        <p class="text-black mb-6 font-medium text-xs">To experience the thrills of our park, you must first secure your entry ticket.</p>

                        <div class="bg-green-800/40 backdrop-blur-md rounded-xl p-5 mb-6 border border-green-500/30">
                            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                                <div class="text-left w-full md:w-auto">
                                    <div class="text-[10px] uppercase tracking-widest text-black font-bold mb-1">Price</div>
                                    <div class="text-4xl font-black text-black">TK. {{ number_format($entryTicketType->price, 0) }}</div>
                                </div>
                                <div class="w-full md:w-auto">
                                    <div class="text-[10px] uppercase tracking-widest text-black font-bold mb-2">Quantity</div>
                                    <div class="flex items-center bg-green-900/50 rounded-lg p-1 border border-green-500/20">
                                        <button wire:click="decrementEntryQuantity" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-green-700/50 transition text-black text-lg font-bold">-</button>
                                        <input type="text" wire:model="entryTicketQuantity" class="w-10 bg-transparent border-none text-center text-xl font-black text-black focus:ring-0" readonly>
                                        <button wire:click="incrementEntryQuantity" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-green-700/50 transition text-black text-lg font-bold">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button wire:click="bookEntryTicket"
                            class="bg-green-500 text-black font-black py-3 px-10 rounded-xl hover:bg-green-400 transition shadow-xl active:scale-95 transform text-lg uppercase tracking-tight w-full md:w-auto border-2 border-green-400/30">
                            Add {{ $entryTicketQuantity }} to Booking
                        </button>
                    </div>
                    <!-- Decorative Elements -->
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-green-400/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-green-900/20 rounded-full blur-3xl"></div>
                </div>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Side: Rides & Entry Ticket -->
            <div class="w-full lg:w-3/5 xl:w-2/3">
                <!-- Tickets Header -->
                <h2
                    class="text-3xl font-black text-navy-800 mb-8 border-l-8 border-navy-600 pl-4 uppercase tracking-tight">
                    Select Your Adventure</h2>


                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-10 {{ (!$hasEntryTicket && !$this->isEntryTicketInCart) ? 'opacity-40 pointer-events-none grayscale' : '' }} transition-all duration-700">
                    @foreach ($rides as $ride)
                        <div
                            class="bg-white rounded-xl shadow-md overflow-hidden border border-navy-50 relative group flex flex-col transform transition duration-300 hover:shadow-lg">
                            <!-- Image & Header -->
                            <div class="h-24 bg-navy-600 relative overflow-hidden shrink-0">
                                <img src="{{ Str::startsWith($ride->image_path, 'http') ? $ride->image_path : ($ride->image_path ? asset('storage/' . $ride->image_path) : 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070') }}"
                                    alt="{{ $ride->name }}"
                                    class="w-full h-full object-cover {{ !$ride->is_active ? 'opacity-30 grayscale' : 'opacity-90 group-hover:opacity-100 group-hover:scale-105' }} transition duration-500">
                                <div
                                    class="absolute top-0 right-0 bg-navy-800/80 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded-bl-lg">
                                    UID: {{ $ride->ticket_uid }}
                                </div>
                                @if(!$ride->is_active)
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span
                                            class="bg-rose-600 text-white text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-xl border border-white/20">Maintenance</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-2.5 flex flex-col flex-grow text-center">
                                <h3 class="text-sm font-bold text-navy-800 mb-0 truncate">{{ $ride->name }}</h3>
                                <p class="text-gray-500 text-[9px] mb-1.5 line-clamp-2 h-5 leading-tight">
                                    {{ $ride->description }}</p>

                                <div class="text-[13px] font-black text-navy-600 mb-1.5">
                                    TK. {{ number_format($ride->price, 0) }}
                                </div>

                                <!-- Quantity & Add Button (Pushed to bottom) -->
                                <div class="mt-auto">
                                    @if($ride->is_active)
                                        <div class="flex items-center justify-center space-x-2 mb-2">
                                            <label
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Quantity:</label>
                                            <input type="number" min="1" wire:model.live="quantities.{{ $ride->id }}"
                                                class="w-12 h-8 py-0 rounded-md border-gray-200 text-sm focus:border-navy-500 focus:ring-navy-500 text-center font-bold">
                                        </div>

                                        <button wire:click="addToCart({{ $ride->id }})"
                                            @if(!$hasEntryTicket && !$this->isEntryTicketInCart) disabled @endif
                                            class="block w-full {{ (!$hasEntryTicket && !$this->isEntryTicketInCart) ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-navy-600 text-white hover:bg-navy-700 active:bg-navy-800' }} text-[10px] font-black py-2 rounded-lg transition shadow-sm active:scale-95 transform uppercase tracking-wide">
                                            Add to Cart
                                        </button>
                                    @else
                                        <div
                                            class="bg-gray-50 text-gray-300 font-bold py-2.5 rounded-lg uppercase text-[9px] tracking-widest border border-gray-100">
                                            Unavailable
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Side: Sticky Bookings -->
            <div class="w-full lg:w-2/5 xl:w-1/3">
                <div class="sticky top-24">
                    <div class="bg-white rounded-2xl shadow-xl border border-navy-100 overflow-hidden">
                        <div class="bg-navy-800 p-6">
                            <h2 class="text-xl font-black text-white uppercase tracking-wider flex items-center gap-3">
                                <svg class="w-6 h-6 text-navy-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                My Bookings
                            </h2>
                        </div>

                        <div class="p-4">
                            @if(!$hasEntryTicket && !$this->isEntryTicketInCart && count($cart) > 0)
                                <div class="mb-4 p-3 bg-amber-50 border-l-4 border-amber-400 rounded-r-lg flex items-start gap-2">
                                    <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <p class="text-[10px] text-amber-800 font-bold leading-tight">
                                        REQUIRED: Add Entry Ticket to confirm this booking.
                                    </p>
                                </div>
                            @endif

                            @if(count($cart) > 0)
                                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($cart as $index => $item)
                                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 group relative">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-navy-800 text-sm truncate pr-6">
                                                        {{ $item['name'] }}</h4>
                                                    <span
                                                        class="text-[10px] text-gray-400 font-medium tracking-widest uppercase">UID:
                                                        {{ $item['uid'] }}</span>
                                                </div>
                                                <button wire:click="removeFromCart({{ $index }})"
                                                    class="text-gray-300 hover:text-red-600 transition p-1">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div
                                                class="flex justify-between items-center bg-white rounded-xl p-2 px-3 border border-gray-100">
                                                <div class="text-[10px] font-bold text-gray-500 uppercase">Qty:
                                                    {{ $item['quantity'] }}</div>
                                                <div class="font-black text-navy-600 text-sm">TK.
                                                    {{ number_format($item['subtotal'], 0) }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 pt-6 border-t-2 border-dashed border-gray-200">
                                    <div class="flex justify-between items-center mb-6">
                                        <span class="text-gray-500 font-bold uppercase tracking-widest text-xs">Total
                                            Amount</span>
                                        <span class="text-2xl font-black text-navy-800">TK.
                                            {{ number_format($this->total, 0) }}</span>
                                    </div>

                                    <button wire:click="checkout"
                                        @if(!$hasEntryTicket && !$this->isEntryTicketInCart) disabled @endif
                                        class="w-full {{ (!$hasEntryTicket && !$this->isEntryTicketInCart) ? 'bg-gray-100 text-gray-300 cursor-not-allowed shadow-none' : 'bg-green-600 hover:bg-green-700 text-white shadow-[0_10px_30px_rgba(22,163,74,0.3)] active:scale-95' }} font-black py-4 rounded-2xl transition transform uppercase tracking-widest flex items-center justify-center gap-3">
                                        <span>Confirm Booking</span>
                                        <svg class="w-5 h-5 animate-bounce-x" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <div class="py-12 text-center">
                                    <div
                                        class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-400 font-bold text-sm uppercase tracking-tighter">Your list is empty
                                    </p>
                                    <p class="text-[10px] text-gray-300 px-8 mt-1">Start adding thrills to your adventure!
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Side Back Button -->
                    <a href="{{ route('dashboard') }}"
                        class="mt-4 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-500 font-bold py-3 rounded-2xl transition gap-2 uppercase text-xs tracking-widest border border-gray-200 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>