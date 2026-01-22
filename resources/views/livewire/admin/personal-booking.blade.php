<div class="min-h-screen bg-gray-50 pb-20">
    <!-- Sticky Navbar -->
    <header class="sticky top-0 bg-navy-900 shadow-2xl transition-all duration-300" style="z-index: 999999 !important;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-4">
                    <div class="p-2.5 bg-white/10 rounded-2xl backdrop-blur-md border border-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-white tracking-tight uppercase">Admin Booking</h1>
                        <p class="text-[10px] text-navy-300 font-bold tracking-widest uppercase opacity-70">Personal Service Panel</p>
                    </div>
                </div>
                
                <a href="{{ route('home') }}" class="group flex items-center gap-3 bg-white hover:bg-navy-50 text-navy-900 px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg active:scale-95">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Dashboard
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 relative z-0">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            <!-- Left Side: Selection -->
            <div class="lg:col-span-8 space-y-12">
                
                <!-- Rides Selection -->
                <section>
                    <div class="flex items-center gap-5 mb-8">
                        <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-indigo-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Available Rides</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($this->rides as $ride)
                            @php $qty = $selectedRides[$ride->id] ?? 0; @endphp
                            <div wire:key="ride-{{ $ride->id }}"
                                class="bg-white p-5 rounded-[2.5rem] border-2 transition-all duration-300 hover:shadow-xl group
                                {{ $qty > 0 ? 'border-indigo-500 bg-indigo-50/30' : 'border-gray-100' }}">
                                <div class="flex justify-between items-center">
                                    <div class="cursor-pointer flex-grow" wire:click="incrementRide({{ $ride->id }})">
                                        <h3 class="font-black text-gray-800 group-hover:text-indigo-600 transition-colors uppercase text-sm">{{ $ride->name }}</h3>
                                        <p class="text-xs font-bold text-gray-400 mt-0.5 italic">BDT {{ number_format($ride->price) }} / Ticket</p>
                                    </div>
                                    <div class="flex items-center bg-white rounded-2xl border border-gray-100 p-1.5 shadow-sm">
                                        <button wire:click="decrementRide({{ $ride->id }})" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-xl text-gray-400 font-bold transition-colors">-</button>
                                        <span class="w-10 text-center font-black text-sm text-gray-800">{{ $qty }}</span>
                                        <button wire:click="incrementRide({{ $ride->id }})" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-xl text-indigo-600 font-bold transition-all active:scale-95">+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <hr class="border-gray-100">

                <!-- Room Selection -->
                <section>
                    <div class="flex items-center gap-5 mb-8">
                        <div class="w-12 h-12 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-rose-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Luxury Rooms</h2>
                    </div>

                    <div class="p-8 bg-white border border-gray-100 rounded-[3rem] shadow-sm mb-10 grid grid-cols-1 md:grid-cols-2 gap-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-rose-50 rounded-bl-full -z-0 opacity-50"></div>
                        <div class="relative z-10">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Check-in Date & Time</label>
                            <input type="datetime-local" wire:model.live="checkInDate" class="w-full bg-gray-50 border-0 rounded-2xl p-4 font-bold text-gray-800 focus:ring-2 focus:ring-rose-500 transition-all">
                        </div>
                        <div class="relative z-10">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Check-out Date & Time</label>
                            <input type="datetime-local" wire:model.live="checkOutDate" class="w-full bg-gray-50 border-0 rounded-2xl p-4 font-bold text-gray-800 focus:ring-2 focus:ring-rose-500 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($this->rooms as $room)
                            @php 
                                $isBooked = in_array($room->id, $bookedRoomIds);
                                $isSelected = in_array($room->id, $selectedRoomIds);
                                $isMaintenance = $room->status !== 'available';
                                $isDisabled = $isBooked || $isMaintenance;
                            @endphp
                            <div wire:key="room-{{ $room->id }}" wire:click="selectRoom({{ $room->id }})"
                                class="relative group cursor-pointer p-6 rounded-[2.5rem] border-2 transition-all duration-300 overflow-hidden
                                {{ $isSelected ? 'border-rose-500 bg-rose-50/30' : ($isDisabled ? 'bg-gray-100 border-gray-200 opacity-60 grayscale' : 'bg-white border-gray-100 hover:border-rose-200') }}">
                                
                                @if($isSelected)
                                    <div class="absolute top-0 right-0 p-3 bg-rose-500 text-white rounded-bl-2xl shadow-lg animate-fade-in">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @elseif($isDisabled)
                                    <div class="absolute top-0 right-0 px-3 py-1 bg-gray-800 text-white text-[9px] font-black uppercase tracking-widest rounded-bl-xl shadow-lg">
                                        {{ $isMaintenance ? 'Repair' : 'Reserved' }}
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <h3 class="font-black text-gray-800 text-lg uppercase tracking-tight">Room {{ $room->room_number }}</h3>
                                    <span class="text-[10px] font-bold text-rose-500 uppercase tracking-widest bg-rose-50 px-2 py-0.5 rounded-full">{{ $room->type }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between text-xs mt-6 pt-4 border-t border-gray-100">
                                    <span class="text-gray-400 font-bold uppercase tracking-widest">Rate / 12h</span>
                                    <span class="font-black text-gray-800">BDT {{ number_format($room->price_per_12h) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>Section -->
            <aside class="lg:col-span-4 lg:sticky lg:top-32">
                <div class="bg-navy-900 rounded-[3rem] p-10 text-white shadow-3xl shadow-navy-200/50 relative overflow-hidden group">
                    <!-- Decorative element -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl transition-all duration-1000 group-hover:scale-150"></div>
                    
                    <h2 class="text-2xl font-black uppercase tracking-tight mb-8 relative z-10 flex items-center justify-between">
                        Booking Slip
                        <span class="text-[10px] bg-black/20 px-3 py-1 rounded-full text-navy-200">ADMIN-{{ date('His') }}</span>
                    </h2>

                    <div class="space-y-6 mb-10 min-h-[150px] relative z-10">
                        @php 
                            $hasRides = count(array_filter($selectedRides)) > 0;
                            $hasRooms = count($selectedRoomIds) > 0;
                        @endphp

                        @if(!$hasRides && !$hasRooms)
                            <div class="flex flex-col items-center justify-center py-12 opacity-30">
                                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <p class="text-xs font-bold uppercase tracking-widest">Your slip is empty</p>
                            </div>
                        @endif

                        @foreach($selectedRides as $id => $qty)
                            @if($qty > 0)
                                @php $ride = $this->rides->firstWhere('id', $id); @endphp
                                <div class="flex justify-between items-center group/item">
                                    <div class="space-y-1">
                                        <p class="text-sm font-black uppercase tracking-tight">{{ $ride->name }}</p>
                                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-widest">Tickets x {{ $qty }}</p>
                                    </div>
                                    <span class="text-sm font-black text-navy-100">BDT {{ number_format($ride->price * $qty) }}</span>
                                </div>
                            @endif
                        @endforeach

                        @foreach($selectedRoomIds as $id)
                            @php 
                                $room = $this->rooms->firstWhere('id', $id); 
                                $checkIn = \Carbon\Carbon::parse($checkInDate);
                                $checkOut = \Carbon\Carbon::parse($checkOutDate);
                                $hours = max(1, $checkIn->diffInHours($checkOut));
                                $multiplier = ceil($hours / 12);
                            @endphp
                            <div class="flex justify-between items-center pt-4 border-t border-white/10 group/item">
                                <div class="space-y-1">
                                    <p class="text-sm font-black uppercase tracking-tight">Room {{ $room->room_number }}</p>
                                    <p class="text-[10px] text-rose-300 font-bold uppercase tracking-widest">{{ $hours }}h stay ({{ $multiplier }} units)</p>
                                </div>
                                <span class="text-sm font-black text-navy-100">BDT {{ number_format($room->price_per_12h * $multiplier) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t-2 border-dashed border-white/20 pt-8 relative z-10">
                        <div class="flex justify-between items-end mb-8">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-navy-400">Net Payable Amount</span>
                            <span class="text-4xl font-black text-white">BDT {{ number_format($this->totalPrice) }}</span>
                        </div>

                        <button wire:click="confirmBooking" 
                            @if(!$hasRides && !$hasRooms) disabled @endif
                            class="w-full bg-white hover:bg-navy-50 text-navy-900 py-6 rounded-[2rem] font-black uppercase tracking-widest transition-all shadow-2xl active:scale-95 disabled:opacity-30 disabled:grayscale disabled:active:scale-100 flex items-center justify-center gap-4">
                            <span>Confirm Order</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Centered Success/Error Toasts -->
    <div class="fixed inset-x-0 bottom-10 flex justify-center z-[100] pointer-events-none px-4">
        <div class="space-y-4 w-full max-w-md pointer-events-none">
            @if (session()->has('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.scale.90
                    class="bg-emerald-500 text-white p-6 rounded-[2rem] shadow-[0_20px_60px_rgba(16,185,129,0.5)] border border-emerald-400/50 pointer-events-auto flex items-center gap-5 backdrop-blur-3xl animate-slide-up">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-0.5">Notification</p>
                        <p class="text-sm font-black uppercase tracking-tight">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.scale.90
                    class="bg-rose-600 text-white p-6 rounded-[2rem] shadow-[0_20px_60px_rgba(225,29,72,0.5)] border border-rose-500/50 pointer-events-auto flex items-center gap-5 backdrop-blur-3xl animate-slide-up">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest opacity-70 mb-0.5">System Error</p>
                        <p class="text-sm font-black uppercase tracking-tight">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .shadow-3xl { shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.3); }
        @keyframes slide-up { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-slide-up { animation: slide-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-fade-in { animation: fade-in 0.3s ease-out; }
        @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    </style>
</div>
