<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Requests Panel - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- Navbar -->
    <nav class="bg-navy-800 text-white shadow-md fixed w-full z-10 top-0 h-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('staff.dashboard') }}" class="flex items-center gap-2 text-white font-bold group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
            <div class="font-black text-lg uppercase tracking-widest text-white/90">
                Staff Request Panel
            </div>
            <div class="w-24"></div> <!-- Spacer -->
        </div>
    </nav>

    <!-- Page Content -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-12">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-6 shadow-sm" role="alert">
                    <span class="block sm:inline font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-6 shadow-sm" role="alert">
                    <span class="block sm:inline font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Ride Requests -->
            <section>
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-1 w-12 bg-navy-600 rounded-full"></div>
                    <h2 class="text-2xl font-black text-navy-800 uppercase tracking-wider">Ride Booking Requests ({{ $rideRequests->count() }})</h2>
                </div>

                @if($rideRequests->isEmpty())
                    <div class="bg-white rounded-3xl p-12 text-center shadow-lg border border-gray-50">
                        <svg class="mx-auto h-16 w-16 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-400 font-bold uppercase tracking-widest">No pending ride requests.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($rideRequests as $groupId => $groupBookings)
                            @php 
                                $first = $groupBookings->first();
                                $totalItems = $groupBookings->sum('quantity');
                                $clusterTotal = $groupBookings->sum('total_price');
                            @endphp
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col md:flex-row {{ $first->user->role === 'admin' ? 'ring-2 ring-navy-800 ring-offset-2' : '' }}">
                                <div class="p-6 flex-grow">
                                    @if($first->user->role === 'admin')
                                        <div class="mb-4 bg-navy-800 p-3 rounded-xl flex items-center justify-between">
                                            <span class="text-[10px] font-black text-white uppercase tracking-widest flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Admin Personal Booking Slip
                                            </span>
                                            <span class="text-[10px] font-bold text-navy-200 uppercase tracking-widest">{{ $groupId }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-4 mb-6">
                                        <img class="h-12 w-12 rounded-full object-cover border-2 border-emerald-500"
                                            src="{{ $first->user->profile_photo_url }}" alt="">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white">{{ $first->user->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $first->user->email }}</p>
                                        </div>
                                        @if(str_contains($groupId, 'RIDE-'))
                                            <span class="ml-auto px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase rounded-full tracking-widest">Cluster Booking</span>
                                        @endif
                                    </div>

                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                            <div class="space-y-4">
                                                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Booked Rides & Tickets</p>
                                                    <ul class="space-y-2">
                                                        @foreach($groupBookings as $item)
                                                            <li class="flex justify-between items-center text-sm">
                                                                <span class="font-bold text-emerald-600">
                                                                    {{ $item->ride ? $item->ride->name : ($item->ticketType ? $item->ticketType->name : 'N/A') }}
                                                                </span>
                                                                <span class="text-gray-500 font-medium">x{{ $item->quantity }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                @if($roomRequests->has($groupId))
                                                    @php $associatedRooms = $roomRequests->get($groupId); @endphp
                                                    <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-xl border border-amber-100 dark:border-amber-800/50">
                                                        <p class="text-[10px] text-amber-600 font-black uppercase tracking-widest mb-2">Booked Rooms (Resort)</p>
                                                        <ul class="space-y-3">
                                                            @foreach($associatedRooms as $roomBooking)
                                                                <li class="p-2 bg-white/50 dark:bg-gray-800/50 rounded-lg border border-amber-100/50">
                                                                    <div class="flex justify-between items-center text-xs mb-1">
                                                                        <span class="font-black text-amber-800">Room {{ $roomBooking->room->room_number }}</span>
                                                                        <span class="text-amber-600 font-bold">BDT {{ number_format($roomBooking->total_price) }}</span>
                                                                    </div>
                                                                    <p class="text-[9px] text-gray-400 font-medium">
                                                                        {{ $roomBooking->check_in_time->format('d M, H:i') }} - {{ $roomBooking->check_out_time->format('d M, H:i') }}
                                                                    </p>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Cluster Price</p>
                                                    @php 
                                                        $totalPrice = $clusterTotal;
                                                        if($roomRequests->has($groupId)) {
                                                            $totalPrice += $roomRequests->get($groupId)->sum('total_price');
                                                        }
                                                    @endphp
                                                    <p class="text-lg font-black text-gray-800 dark:text-gray-200">BDT {{ number_format($totalPrice, 0) }}</p>
                                                </div>
                                                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Requested At</p>
                                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $first->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-gray-100/50 dark:bg-gray-700/50 p-6 flex flex-row md:flex-col justify-center gap-3 border-t md:border-t-0 md:border-l border-gray-100 dark:border-gray-700 w-full md:w-48">
                                    <form action="{{ route('staff.accept_booking', ['type' => 'ride', 'id' => $groupId]) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-green-500 hover:bg-green-600 hover:scale-105 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-sm shadow-green-200 active:scale-95 text-sm">Accept Cluster</button>
                                    </form>
                                    <form action="{{ route('staff.reject_booking', ['type' => 'ride', 'id' => $groupId]) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-sm active:scale-95 text-sm">Reject Cluster</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Room Requests -->
            <section>
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-1 w-12 bg-amber-600 rounded-full"></div>
                    <h2 class="text-2xl font-black text-amber-900 uppercase tracking-wider">Resort Booking Requests ({{ $roomRequests->count() }})</h2>
                </div>

                @if($roomRequests->isEmpty())
                    <div class="bg-white rounded-3xl p-12 text-center shadow-lg border border-gray-50">
                        <svg class="mx-auto h-16 w-16 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <p class="text-gray-400 font-bold uppercase tracking-widest">No pending resort requests.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-12">
                        @foreach($roomRequests as $groupId => $groupBookings)
                            {{-- Skip if already displayed as part of a joint cluster --}}
                            @if($rideRequests->has($groupId)) @continue @endif

                            @php 
                                $first = $groupBookings->first();
                                $totalPrice = $groupBookings->sum('total_price');
                            @endphp

                            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden flex flex-col md:flex-row transform transition hover:scale-[1.01] {{ $first->user->role === 'admin' ? 'ring-2 ring-amber-500 ring-offset-2' : '' }}">
                                <div class="p-8 flex-grow">
                                    @if($first->user->role === 'admin')
                                        <div class="mb-6 bg-amber-600 p-4 rounded-2xl flex items-center justify-between">
                                            <span class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                Admin Resort Booking Slip (Room Cluster)
                                            </span>
                                            <span class="text-xs font-bold text-amber-100 uppercase tracking-widest">{{ $groupId }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-4 mb-8">
                                        <div class="h-14 w-14 rounded-full border-4 border-amber-50 overflow-hidden bg-gray-100 shadow-inner">
                                            <img class="h-full w-full object-cover" src="{{ $first->user->profile_photo_url }}" alt="">
                                        </div>
                                        <div>
                                            <h3 class="font-extrabold text-amber-900 text-xl leading-tight">{{ $first->user->name }}</h3>
                                            <p class="text-xs text-gray-400 font-semibold tracking-wide uppercase">{{ $first->user->email }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <div class="space-y-4">
                                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Booked Rooms</p>
                                            @foreach($groupBookings as $request)
                                                <div class="bg-amber-50/50 p-4 rounded-2xl border border-amber-100/50 flex justify-between items-center group/item hover:bg-amber-50 transition-colors">
                                                    <div>
                                                        <p class="font-black text-amber-800">Room {{ $request->room->room_number }}</p>
                                                        <p class="text-[9px] text-amber-600 font-bold uppercase">{{ $request->room->type }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-black text-gray-800 text-xs">{{ $request->check_in_time->format('d M, H:i') }} - {{ $request->check_out_time->format('d M, H:i') }}</p>
                                                        <p class="text-[9px] text-gray-400 font-bold">BDT {{ number_format($request->total_price) }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="flex flex-col justify-end">
                                            <div class="bg-amber-100/30 p-6 rounded-3xl border border-amber-100 flex justify-between items-center">
                                                <div>
                                                    <p class="text-[10px] text-amber-700 font-black uppercase tracking-widest mb-1">Total Cluster price</p>
                                                    <p class="text-2xl font-black text-amber-900">BDT {{ number_format($totalPrice) }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Requested At</p>
                                                    <p class="font-black text-gray-500 text-sm">{{ $first->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-amber-50/20 p-8 flex flex-row md:flex-col justify-center gap-4 border-t md:border-t-0 md:border-l border-amber-100 w-full md:w-56">
                                    <form action="{{ route('staff.accept_booking', ['type' => 'room', 'id' => $groupId]) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-4 px-8 rounded-2xl transition shadow-lg shadow-green-200 active:scale-95 uppercase tracking-widest text-xs">Accept Cluster</button>
                                    </form>
                                    <form action="{{ route('staff.reject_booking', ['type' => 'room', 'id' => $groupId]) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-black py-4 px-8 rounded-2xl transition shadow-lg shadow-red-200 active:scale-95 uppercase tracking-widest text-xs">Reject Cluster</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Parking Requests -->
            <section>
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-1 w-12 bg-purple-600 rounded-full"></div>
                    <h2 class="text-2xl font-black text-purple-900 uppercase tracking-wider">Parking Booking Requests ({{ $parkingRequests->count() }})</h2>
                </div>

                @if($parkingRequests->isEmpty())
                    <div class="bg-white rounded-3xl p-12 text-center shadow-lg border border-gray-50">
                        <svg class="mx-auto h-16 w-16 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <p class="text-gray-400 font-bold uppercase tracking-widest">No pending parking requests.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($parkingRequests as $request)
                            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden flex flex-col md:flex-row transform transition hover:scale-[1.01]">
                                <div class="p-8 flex-grow">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="h-14 w-14 rounded-full border-4 border-purple-50 overflow-hidden bg-gray-100">
                                            <img class="h-full w-full object-cover" src="{{ $request->user->profile_photo_url }}" alt="">
                                        </div>
                                        <div>
                                            <h3 class="font-extrabold text-purple-900 text-xl leading-tight">{{ $request->user->name }}</h3>
                                            <p class="text-xs text-gray-400 font-semibold tracking-wide uppercase">{{ $request->user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                                        <div class="bg-purple-50/50 p-4 rounded-2xl border border-purple-100/50">
                                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Slot #</p>
                                            <p class="font-black text-purple-700">Slot {{ $request->slot->slot_number }}</p>
                                        </div>
                                        <div class="bg-purple-50/50 p-4 rounded-2xl border border-purple-100/50">
                                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Date & Time</p>
                                            <p class="font-black text-gray-800 text-xs">{{ $request->date }} <br> {{ $request->time_slot }}</p>
                                        </div>
                                        <div class="bg-purple-50/50 p-4 rounded-2xl border border-purple-100/50">
                                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Price</p>
                                            <p class="font-black text-purple-600">BDT {{ number_format($request->price, 0) }}</p>
                                        </div>
                                        <div class="bg-purple-50/50 p-4 rounded-2xl border border-purple-100/50">
                                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Requested At</p>
                                            <p class="font-black text-gray-500">{{ $request->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-purple-50/20 p-8 flex flex-row md:flex-col justify-center gap-4 border-t md:border-t-0 md:border-l border-purple-100">
                                    <form action="{{ route('staff.accept_booking', ['type' => 'parking', 'id' => $request->id]) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-black py-4 px-8 rounded-2xl transition shadow-lg shadow-green-200 active:scale-95 uppercase tracking-widest text-xs">Accept</button>
                                    </form>
                                    <form action="{{ route('staff.reject_booking', ['type' => 'parking', 'id' => $request->id]) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-black py-4 px-8 rounded-2xl transition shadow-lg shadow-red-200 active:scale-95 uppercase tracking-widest text-xs">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>
    </div>

    <style>
        .bg-navy-800 { background-color: #1a365d; }
        .text-navy-800 { color: #1a365d; }
        .text-navy-900 { color: #1a365d; }
        .text-navy-700 { color: #2b6cb0; }
        .bg-navy-50 { background-color: #ebf1f7; }
        .bg-amber-50 { background-color: #fef3c7; }
        .text-amber-900 { color: #78350f; }
        .text-amber-700 { color: #b45309; }
        .text-amber-800 { color: #92400e; }
    </style>
</body>

</html>
