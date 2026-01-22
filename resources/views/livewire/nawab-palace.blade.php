<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-navy-900 mb-2 uppercase tracking-widest"
                style="font-family: 'Playfair Display', serif;">Nawab Palace</h1>
            <div class="h-1 w-24 bg-gold-500 mx-auto"></div>
        </div>

        <!-- Section Header (Left Aligned) -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-10">
            <h2 class="text-2xl font-bold text-navy-700 border-l-4 border-navy-600 pl-4">Book Your Room</h2>

            <!-- Filter Controls -->
            <div
                class="mt-4 md:mt-0 bg-white p-4 rounded-lg shadow-md flex items-center space-x-4 border border-navy-100">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Check-in Time</label>
                    <input type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}" wire:model.live="selectedTime"
                        class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-navy-500 focus:ring-navy-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Duration</label>
                    <select wire:model.live="selectedDuration"
                        class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-navy-500 focus:ring-navy-500">
                        @for ($i = 12; $i <= 168; $i += 12)
                            <option value="{{ $i }}">{{ $i }} Hours</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-8" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Rooms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($rooms as $room)
                @php
                    $isVip = $room->type === 'vip';
                    $isBooked = in_array($room->id, $bookedRooms);
                    $underMaintenance = in_array($room->status, ['maintenance', 'out_of_order', 'repair_required']);
                @endphp

                <div
                    class="relative rounded-2xl overflow-hidden shadow-lg border transition hover:shadow-2xl {{ $underMaintenance ? 'opacity-75 grayscale' : '' }} {{ $isVip ? 'bg-gradient-to-br from-white to-gold-50 border-gold-400 ring-4 ring-gold-100' : 'bg-white border-navy-100' }}">
                    <!-- Image -->
                    <div class="h-56 relative group">
                        <img src="{{ $room->image_path }}" alt="Room {{ $room->room_number }}"
                            class="w-full h-full object-cover transition duration-500 group-hover:scale-105">

                        <!-- Overlay for Booked Status -->
                        @if($isBooked)
                            <div class="absolute inset-0 bg-red-900 bg-opacity-60 flex items-center justify-center">
                                <span
                                    class="text-white text-2xl font-bold uppercase tracking-widest border-2 border-white px-4 py-2 bg-red-800 bg-opacity-75">Booked</span>
                            </div>
                        @elseif($underMaintenance)
                            <div class="absolute inset-0 bg-rose-900 bg-opacity-60 flex items-center justify-center">
                                <span
                                    class="text-white text-lg font-black uppercase tracking-widest border-2 border-white px-4 py-2 bg-rose-800 bg-opacity-75">Under
                                    Maintenance</span>
                            </div>
                        @else
                            <!-- VIP Badge -->
                            @if($isVip)
                                <div
                                    class="absolute top-4 right-4 bg-gradient-to-r from-gold-400 to-gold-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg flex items-center border border-white">
                                    <svg class="w-4 h-4 mr-1 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    VIP SUITE
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6 {{ $isBooked ? 'bg-red-50' : ($underMaintenance ? 'bg-rose-50' : '') }}">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-2xl font-bold text-navy-800">Room {{ $room->room_number }}</h3>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Floor {{ $room->floor }} •
                                    {{ ucfirst($room->type) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="block text-xs text-gray-400">UID: {{ $room->ticket_uid }}</span>
                            </div>
                        </div>

                        <div class="my-4">
                            <span class="text-sm font-semibold text-gray-600">Features:</span>
                            <p class="text-gray-500 text-sm mt-1 h-10 line-clamp-2">{{ $room->features }}</p>
                        </div>

                        <div class="flex justify-between items-end mt-6">
                            <div>
                                <span class="text-2xl font-bold text-navy-700">TK.
                                    {{ number_format($room->price_per_12h, 0) }}</span>
                                <span class="text-xs text-gray-400 block">/ 12 hours</span>
                            </div>

                            @if(!$isBooked && !$underMaintenance)
                                <button wire:click="bookRoom({{ $room->id }})"
                                    class="{{ $isVip ? 'bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-700 text-white shadow-gold-200' : 'bg-navy-600 hover:bg-navy-700 text-white' }} font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:-translate-y-1 active:scale-95">
                                    Book Now
                                </button>
                            @else
                                <button disabled
                                    class="bg-gray-300 text-gray-500 font-bold py-2 px-6 rounded-lg cursor-not-allowed">
                                    Unavailable
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Back Button -->
        <div class="fixed bottom-6 right-6 z-[999]">
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
                            <p class="text-amber-700">You must book a Park Entry Ticket before you can make any resort
                                bookings. <a href="{{ route('client.book-rides') }}"
                                    class="font-bold underline hover:text-amber-900 transition">Book Entry Ticket Now
                                    &rarr;</a></p>
                        </div>
                    </div>
                </div>
            @endif
            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-center bg-navy-600 hover:bg-navy-700 text-white font-bold rounded-full h-16 px-6 shadow-2xl transition transform hover:scale-110 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-navy-300 gap-2"
                title="Back to Dashboard">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="text-lg">Back</span>
            </a>
        </div>

    </div>
</div>