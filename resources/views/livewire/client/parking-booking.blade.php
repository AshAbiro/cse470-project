<div class="py-12">
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-navy-900 leading-tight flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                </path>
            </svg>
            {{ __('Smart Parking Reservation') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Status Messages -->
        <!-- Status Messages -->
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="fixed top-24 right-8 z-[100] bg-emerald-500 text-white p-6 rounded-2xl shadow-2xl flex items-center gap-4 animate-bounce border-4 border-white/20">
                <div class="h-10 w-10 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-black text-lg tracking-tight leading-tight uppercase">Success</p>
                    <p class="font-bold opacity-90 text-sm italic">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="ml-4 hover:scale-110 transition p-2 bg-white/10 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="fixed top-24 right-8 z-[100] bg-red-500 text-white p-6 rounded-2xl shadow-2xl flex items-center gap-4 animate-shake border-4 border-white/20">
                <div class="h-10 w-10 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-black text-lg tracking-tight leading-tight uppercase">Alert</p>
                    <p class="font-bold opacity-90 text-sm italic">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="ml-4 hover:scale-110 transition p-2 bg-white/10 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Controls Selection -->
        <div
            class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl p-8 mb-12 border border-gray-100 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <label
                        class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 ml-2">Reservation
                        Date</label>
                    <input type="date" wire:model.live="selectedDate" min="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 border-none rounded-2xl p-5 focus:ring-4 focus:ring-blue-500/20 font-bold text-navy-900 transition">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3 ml-2">Time Slot
                        (2 Hours)</label>
                    <select wire:model.live="selectedTimeSlot"
                        class="w-full bg-gray-50 border-none rounded-2xl p-5 focus:ring-4 focus:ring-blue-500/20 font-bold text-navy-900 transition">
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot }}">{{ $slot }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Parking Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-10 gap-4 mb-16">
            @foreach($slots as $slot)
                    <!-- Parking Ticket Card -->
                    <div wire:click="{{ ($slot->is_booked || $slot->is_past) ? '' : 'bookSlot(' . $slot->id . ')' }}"
                        class="relative group cursor-pointer perspective-1000">

                        <div
                            class="h-40 relative rounded-2xl overflow-hidden shadow-md transition-all duration-300 transform 
                                        {{ ($slot->is_booked || $slot->is_past)
                ? 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed grayscale'
                : 'bg-white dark:bg-gray-800 hover:-translate-y-2 hover:shadow-2xl hover:rotate-2 border-b-4 border-blue-500' }}">

                            <div
                                class="h-10 flex items-center justify-center {{ ($slot->is_booked || $slot->is_past) ? 'bg-gray-200 dark:bg-gray-600' : 'bg-blue-600' }}">
                                <span class="text-white font-black text-lg">P</span>
                            </div>

                            <!-- Ticket Content -->
                            <div class="p-3 text-center flex flex-col justify-between h-30">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Slot ID</p>
                                    <p class="text-lg font-black text-navy-900 dark:text-gray-100 leading-none">
                                        #{{ $slot->slot_number }}</p>
                                </div>

                                <div class="mt-4">
                                    @if($slot->is_past)
                                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Expired</span>
                                    @elseif($slot->is_booked)
                                        <span class="text-[9px] font-black uppercase tracking-widest text-red-500">Reserved</span>
                                    @else
                                        <span
                                            class="text-[9px] font-black uppercase tracking-widest text-emerald-500 group-hover:animate-pulse">Available</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Perforation Visual -->
                            <div class="absolute top-1/2 -left-2 w-4 h-4 rounded-full bg-gray-50 dark:bg-gray-900"></div>
                            <div class="absolute top-1/2 -right-2 w-4 h-4 rounded-full bg-gray-50 dark:bg-gray-900"></div>
                        </div>

                        <!-- Availability Overlay on Hover -->
                        @if(!$slot->is_booked && !$slot->is_past)
                            <div
                                class="absolute inset-0 bg-blue-600/90 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4 text-center">
                                <button class="text-white font-black text-xs uppercase tracking-widest">Reserve<br>Now</button>
                            </div>
                        @endif
                    </div>
            @endforeach
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Reserve Parking Slot</h1>
                <a href="{{ route('dashboard') }}"
                    class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">&larr; Back</a>
            </div>

            @if(!$hasEntryTicket)
                <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-6 rounded-r-xl shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-500 rounded-full p-2">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-amber-800 uppercase tracking-tight">Entry Ticket Required</h3>
                            <p class="text-amber-700">You must book a Park Entry Ticket before you can reserve a parking
                                slot. <a href="{{ route('client.book-rides') }}"
                                    class="font-bold underline hover:text-amber-900 transition">Book Entry Ticket Now
                                    &rarr;</a></p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Back Button Floating -->
        <div class="fixed bottom-8 right-8 z-[100]">
            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-center bg-navy-900 text-white w-16 h-16 rounded-full shadow-2xl hover:bg-navy-700 transition transform hover:scale-110 active:scale-95 border-4 border-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </div>

    <style>
        .perspective-1000 {
            perspective: 1000px;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-5px);
            }

            40%,
            80% {
                transform: translateX(5px);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
    </style>
</div>