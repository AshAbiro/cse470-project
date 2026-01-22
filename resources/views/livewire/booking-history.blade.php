<div class="py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-extrabold text-navy-800 mb-10 text-center uppercase tracking-widest">Your Booking History</h2>

        @if($rideBookings->isEmpty() && $roomBookings->isEmpty() && $parkingBookings->isEmpty())
            <div class="bg-white rounded-3xl shadow-xl p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-2xl font-bold text-gray-700">No bookings yet!</p>
                <p class="text-gray-500 mt-2 italic">Your adventure is waiting to be booked.</p>
                <div class="mt-8 flex justify-center gap-4">
                    <a href="{{ route('client.book-rides') }}" class="bg-navy-600 text-white px-8 py-3 rounded-full font-bold hover:bg-navy-700 transition transform hover:scale-105">Book Rides</a>
                    <a href="{{ route('client.nawab-palace') }}" class="bg-amber-600 text-white px-8 py-3 rounded-full font-bold hover:bg-amber-700 transition transform hover:scale-105">Nawab Palace</a>
                </div>
            </div>
        @else
            <!-- Ride Bookings Section -->
            @if(!$rideBookings->isEmpty())
                <div class="mb-16">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-1 w-12 bg-navy-600 rounded-full"></div>
                        <h3 class="text-2xl font-bold text-navy-800 uppercase">Ride Bookings</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                        @foreach($rideBookings as $groupId => $group)
                            @php
                                $booking = $group->first();
                                $isCluster = $group->count() > 1;
                                $totalPrice = $group->sum('total_price');
                                $totalItems = $group->count(); // Count of rows (unique rides in cluster usually)
                            @endphp
                            <div class="relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden group hover:shadow-2xl transition duration-300">
                                <!-- Status Badge -->
                                <div class="absolute top-4 right-4 z-10">
                                    @if($booking->status == 'pending')
                                        <span class="px-4 py-1.5 rounded-full bg-red-100 text-red-600 text-xs font-bold uppercase tracking-wider border border-red-200 shadow-sm">Pending</span>
                                    @elseif($booking->status == 'accepted' || $booking->status == 'confirmed')
                                        <span class="px-4 py-1.5 rounded-full bg-green-100 text-green-600 text-xs font-bold uppercase tracking-wider border border-green-200 shadow-sm">Accepted</span>
                                    @else
                                        <span class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-bold uppercase tracking-wider border border-gray-200 shadow-sm">{{ ucfirst($booking->status) }}</span>
                                    @endif
                                </div>

                                <!-- Ticket Header -->
                                <div class="h-24 bg-navy-800 p-6 flex items-center gap-4">
                                    <div class="bg-white/10 p-3 rounded-xl backdrop-blur-md">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($isCluster)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-lg leading-tight">
                                            @if($isCluster)
                                                Ride Bundle
                                            @else
                                                {{ $booking->ride ? $booking->ride->name : ($booking->ticketType ? $booking->ticketType->name : 'Ticket') }}
                                            @endif
                                        </h4>
                                        <p class="text-navy-300 text-xs font-semibold">
                                            @if($isCluster)
                                                {{ $group->count() }} Rides Included
                                            @elseif($booking->ride)
                                                RIDE UID: #{{ $booking->ride->ticket_uid }}
                                            @else
                                                TICKET ID: #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Perforation Line -->
                                <div class="relative h-4 bg-white flex items-center px-4 overflow-hidden">
                                     <div class="absolute -left-2 w-4 h-4 rounded-full bg-gray-50 border border-gray-100"></div>
                                     <div class="w-full border-t-2 border-dashed border-gray-200"></div>
                                     <div class="absolute -right-2 w-4 h-4 rounded-full bg-gray-50 border border-gray-100"></div>
                                </div>

                                <!-- Ticket Body -->
                                <div class="p-6">
                                    @if($isCluster)
                                        <div class="mb-4 max-h-40 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                            @foreach($group as $item)
                                                <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                                                    <span class="text-gray-700 font-bold truncate w-3/4">{{ $item->ride->name ?? 'Ride' }}</span>
                                                    <span class="text-gray-400 font-mono text-xs">x{{ $item->quantity }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="grid grid-cols-2 gap-4 mb-6">
                                            <div>
                                                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Quantity</label>
                                                <p class="text-gray-800 font-bold text-lg">{{ $booking->quantity }} Tickets</p>
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Booking Date</label>
                                                <p class="text-gray-800 font-bold">{{ $booking->booking_date->format('d M, Y') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                        <span class="text-gray-400 text-xs font-bold uppercase">Total Paid</span>
                                        <span class="text-2xl font-black text-navy-800">TK. {{ number_format($totalPrice, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Room Bookings Section -->
            @if(!$roomBookings->isEmpty())
                <div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-1 w-12 bg-amber-600 rounded-full"></div>
                        <h3 class="text-2xl font-bold text-amber-800 uppercase">Resort Bookings</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                        @foreach($roomBookings as $groupId => $group)
                            @php
                                $booking = $group->first();
                                $isCluster = $group->count() > 1;
                                $totalPrice = $group->sum('total_price');
                            @endphp
                            <div class="relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden group hover:shadow-2xl transition duration-300">
                                <!-- Status Badge -->
                                <div class="absolute top-4 right-4 z-10">
                                    @if($booking->status == 'pending')
                                        <span class="px-4 py-1.5 rounded-full bg-red-100 text-red-600 text-xs font-bold uppercase tracking-wider border border-red-200 shadow-sm">Pending</span>
                                    @elseif($booking->status == 'accepted' || $booking->status == 'booked' || $booking->status == 'confirmed')
                                        <span class="px-4 py-1.5 rounded-full bg-green-100 text-green-600 text-xs font-bold uppercase tracking-wider border border-green-200 shadow-sm">Accepted</span>
                                    @else
                                        <span class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-600 text-xs font-bold uppercase tracking-wider border border-gray-200 shadow-sm">{{ ucfirst($booking->status) }}</span>
                                    @endif
                                </div>

                                <!-- Ticket Header -->
                                <div class="h-24 bg-amber-800 p-6 flex items-center gap-4">
                                    <div class="bg-white/10 p-3 rounded-xl backdrop-blur-md">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($isCluster)
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-lg leading-tight">
                                            @if($isCluster)
                                                Resort Bundle
                                            @else
                                                Room #{{ $booking->room->room_number }}
                                            @endif
                                        </h4>
                                        <p class="text-amber-300 text-xs font-semibold">
                                            @if($isCluster)
                                                {{ $group->count() }} Rooms
                                            @else
                                                {{ $booking->room->type }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Perforation Line -->
                                <div class="relative h-4 bg-white flex items-center px-4 overflow-hidden">
                                     <div class="absolute -left-2 w-4 h-4 rounded-full bg-gray-50 border border-gray-100"></div>
                                     <div class="w-full border-t-2 border-dashed border-gray-200"></div>
                                     <div class="absolute -right-2 w-4 h-4 rounded-full bg-gray-50 border border-gray-100"></div>
                                </div>

                                <!-- Ticket Body -->
                                <div class="p-6">
                                    @if($isCluster)
                                        <div class="mb-4 max-h-40 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                            @foreach($group as $item)
                                                <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                                                    <span class="text-gray-700 font-bold">Room #{{ $item->room->room_number }}</span>
                                                    <span class="text-gray-400 font-mono text-xs">{{ $item->check_in_time->format('d M') }} - {{ $item->check_out_time->format('d M') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Check In</label>
                                                <p class="text-gray-800 font-bold text-sm">{{ $booking->check_in_time->format('d M, H:i') }}</p>
                                            </div>
                                            <div>
                                                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Check Out</label>
                                                <p class="text-gray-800 font-bold text-sm">{{ $booking->check_out_time->format('d M, H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                                        <span class="text-gray-400 text-xs font-bold uppercase">Total Paid</span>
                                        <span class="text-2xl font-black text-amber-800">TK. {{ number_format($totalPrice, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Parking Bookings Section -->
            @if(!$parkingBookings->isEmpty())
                <div class="mt-16 pb-12">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-1.5 w-16 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full"></div>
                        <h3 class="text-3xl font-black text-purple-900 uppercase tracking-tighter">Parking Reservations</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        @foreach($parkingBookings as $booking)
                            <div class="relative bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden group hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                                <!-- Status Badge -->
                                <div class="absolute top-6 right-6 z-10">
                                    @if($booking->status == 'pending')
                                        <div class="px-4 py-1.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest border border-amber-100 shadow-sm flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                                            Pending
                                        </div>
                                    @elseif($booking->status == 'accepted')
                                        <div class="px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-100 shadow-sm flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                            Accepted
                                        </div>
                                    @elseif($booking->status == 'rejected')
                                        <div class="px-4 py-1.5 rounded-full bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest border border-rose-100 shadow-sm flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                            Rejected
                                        </div>
                                    @else
                                        <div class="px-4 py-1.5 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100 shadow-sm">{{ $booking->status }}</div>
                                    @endif
                                </div>

                                <!-- Parking Header -->
                                <div class="h-28 bg-gradient-to-br from-purple-600 to-indigo-700 p-8 flex items-center gap-4">
                                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-xl border border-white/20">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-purple-200 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Parking Slot</p>
                                        <h4 class="text-white font-black text-2xl tracking-tighterleading-none italic">#{{ $booking->slot->slot_number }}</h4>
                                    </div>
                                </div>

                                <!-- Ticket Body -->
                                <div class="p-8">
                                    <div class="grid grid-cols-1 gap-6 mb-8">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <label class="text-[10px] text-gray-400 font-black uppercase tracking-widest block mb-1">Reserved Date</label>
                                                <p class="text-gray-900 font-black text-lg">{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</p>
                                            </div>
                                            <div class="text-right">
                                                <label class="text-[10px] text-gray-400 font-black uppercase tracking-widest block mb-1">Time Window</label>
                                                <p class="text-gray-900 font-black text-lg">{{ $booking->time_slot }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Amount Paid</span>
                                            <span class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">TK. {{ number_format($booking->price, 0) }}</span>
                                        </div>
                                        <div class="h-12 w-12 rounded-2xl bg-gray-50 flex items-center justify-center opacity-30 group-hover:opacity-100 transition-opacity">
                                             <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                             </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Floating Dashboard Button -->
    <div class="fixed bottom-6 right-6 z-[999]">
         <a href="{{ route('dashboard') }}" class="flex items-center justify-center bg-navy-600 hover:bg-navy-700 text-white font-bold rounded-full h-16 px-6 shadow-2xl transition transform hover:scale-110 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-navy-300 gap-2" title="Back to Dashboard">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="text-lg">Dashboard</span>
        </a>
    </div>

    <style>
        .text-navy-800 { color: #1a365d; }
        .bg-navy-800 { background-color: #1a365d; }
        .bg-navy-600 { background-color: #2b6cb0; }
        .text-navy-300 { color: #a3bffa; }
    </style>
</div>
