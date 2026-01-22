<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Requests - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- Navbar -->
    <nav
        class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm fixed w-full z-10 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}"
                        class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
                <div class="font-medium text-lg text-gray-800 dark:text-gray-200 uppercase tracking-widest">
                    Booking Requests
                </div>
                <div class="w-24"></div> <!-- Spacer -->
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-12">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Ride Requests -->
            <section>
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-1 w-12 bg-blue-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">Ride Booking
                        Requests ({{ $rideRequests->count() }})</h2>
                </div>

                @if($rideRequests->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm">
                        <p class="text-gray-500">No pending ride requests.</p>
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
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col md:flex-row">
                                <div class="p-6 flex-grow">
                                    <div class="flex items-center gap-4 mb-6">
                                        <img class="h-12 w-12 rounded-full object-cover border-2 border-blue-500"
                                            src="{{ $first->user->profile_photo_url }}" alt="">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white">{{ $first->user->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $first->user->email }}</p>
                                        </div>
                                        @if(str_contains($groupId, 'RIDE-'))
                                            <span class="ml-auto px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-black uppercase rounded-full tracking-widest">Cluster Booking</span>
                                        @endif
                                    </div>

                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                            <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Booked Items</p>
                                                <ul class="space-y-2">
                                                    @foreach($groupBookings as $item)
                                                        <li class="flex justify-between items-center text-sm">
                                                            <span class="font-bold text-blue-600">
                                                                {{ $item->ride ? $item->ride->name : ($item->ticketType ? $item->ticketType->name : 'N/A') }}
                                                            </span>
                                                            <span class="text-gray-500 font-medium">x{{ $item->quantity }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Cluster Price</p>
                                                    <p class="text-lg font-black text-gray-800 dark:text-gray-200">TK. {{ number_format($clusterTotal, 0) }}</p>
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
                                    <form action="{{ route('admin.accept_booking', ['type' => 'ride', 'id' => $groupId]) }}"
                                        method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-green-500 hover:bg-green-600 hover:scale-105 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-sm shadow-green-200 active:scale-95 text-sm">Accept Cluster</button>
                                    </form>
                                    <form action="{{ route('admin.reject_booking', ['type' => 'ride', 'id' => $groupId]) }}"
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
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">Resort Booking
                        Requests ({{ $roomRequests->count() }})</h2>
                </div>

                @if($roomRequests->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm">
                        <p class="text-gray-500">No pending resort requests.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($roomRequests as $request)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col md:flex-row">
                                <div class="p-6 flex-grow">
                                    <div class="flex items-center gap-4 mb-4">
                                        <img class="h-12 w-12 rounded-full object-cover border-2 border-amber-500"
                                            src="{{ $request->user->profile_photo_url }}" alt="">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white">{{ $request->user->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $request->user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Room #</p>
                                            <p class="font-bold text-amber-600">Room {{ $request->room->room_number }}
                                                ({{ $request->room->type }})</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Check In/Out
                                            </p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200 text-xs">
                                                {{ $request->check_in_time->format('d M, H:i') }} -
                                                {{ $request->check_out_time->format('d M, H:i') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Price
                                            </p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200">TK.
                                                {{ number_format($request->total_price, 0) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Requested At
                                            </p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200">
                                                {{ $request->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-gray-50 dark:bg-gray-700/50 p-6 flex flex-row md:flex-col justify-center gap-3 border-t md:border-t-0 md:border-l border-gray-100 dark:border-gray-700">
                                    <form action="{{ route('admin.accept_booking', ['type' => 'room', 'id' => $request->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-xl transition shadow-sm active:scale-95">Accept</button>
                                    </form>
                                    <form action="{{ route('admin.reject_booking', ['type' => 'room', 'id' => $request->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-xl transition shadow-sm active:scale-95">Reject</button>
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
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">Parking Booking
                        Requests ({{ $parkingRequests->count() }})</h2>
                </div>

                @if($parkingRequests->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm">
                        <p class="text-gray-500">No pending parking requests.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($parkingRequests as $request)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col md:flex-row">
                                <div class="p-6 flex-grow">
                                    <div class="flex items-center gap-4 mb-4">
                                        <img class="h-12 w-12 rounded-full object-cover border-2 border-purple-500"
                                            src="{{ $request->user->profile_photo_url }}" alt="">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white">{{ $request->user->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $request->user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Slot #</p>
                                            <p class="font-bold text-purple-600">Slot {{ $request->slot->slot_number }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Date & Time
                                            </p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200 text-xs">
                                                {{ $request->date }} <br> {{ $request->time_slot }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Price
                                            </p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200">TK.
                                                {{ number_format($request->price, 0) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Requested At
                                            </p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200">
                                                {{ $request->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-gray-50 dark:bg-gray-700/50 p-6 flex flex-row md:flex-col justify-center gap-3 border-t md:border-t-0 md:border-l border-gray-100 dark:border-gray-700">
                                    <form action="{{ route('admin.accept_booking', ['type' => 'parking', 'id' => $request->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-xl transition shadow-sm active:scale-95">Accept</button>
                                    </form>
                                    <form action="{{ route('admin.reject_booking', ['type' => 'parking', 'id' => $request->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-xl transition shadow-sm active:scale-95">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Admin's Own Ride Bookings (Processed by Staff) -->
            @if($myRideBookings->isNotEmpty())
            <section>
                <form action="{{ route('admin.bulk_delete_my_bookings') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="ride">
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-1 w-12 bg-green-600 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">My Ride Bookings ({{ $myRideBookings->count() }})</h2>
                        <div class="ml-auto flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-600 dark:text-gray-400 cursor-pointer bg-white dark:bg-gray-800 px-3 py-2 rounded-lg shadow-sm">
                                <input type="checkbox" onclick="toggleAll('ride', this)" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                Select All
                            </label>
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded-lg transition shadow-sm" onclick="return confirm('Are you sure you want to delete selected bookings?')">
                                Delete Selected
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        @foreach($myRideBookings as $groupId => $groupBookings)
                            @php 
                                $first = $groupBookings->first();
                                $totalItems = $groupBookings->sum('quantity');
                                $clusterTotal = $groupBookings->sum('total_price');
                                $status = $first->status;
                            @endphp
                            <div class="flex items-stretch gap-3">
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 w-12 flex items-center justify-center shrink-0">
                                    <input type="checkbox" name="ids[]" value="{{ $groupId }}" class="ride-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transform scale-125">
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col md:flex-row flex-grow">
                                    <div class="p-6 flex-grow">
                                        <div class="flex items-center gap-4 mb-6">
                                            <div>
                                                <h3 class="font-bold text-gray-800 dark:text-white">Your Personal Booking</h3>
                                                <p class="text-xs text-gray-500">Processed by Staff</p>
                                            </div>
                                            @if($status == 'accepted')
                                                <span class="ml-auto px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-full tracking-widest">✓ Accepted</span>
                                            @else
                                                <span class="ml-auto px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase rounded-full tracking-widest">✗ Rejected</span>
                                            @endif
                                            
                                            <button type="button" onclick="deleteSingle('{{ route('admin.delete_my_booking', ['type' => 'ride', 'id' => $groupId]) }}')" class="ml-2 text-red-500 hover:text-red-700 p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="space-y-4">
                                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                                <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2">Booked Items</p>
                                                    <ul class="space-y-2">
                                                        @foreach($groupBookings as $item)
                                                            <li class="flex justify-between items-center text-sm">
                                                                <span class="font-bold text-blue-600">
                                                                    {{ $item->ride ? $item->ride->name : ($item->ticketType ? $item->ticketType->name : 'N/A') }}
                                                                </span>
                                                                <span class="text-gray-500 font-medium">x{{ $item->quantity }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Price</p>
                                                        <p class="text-lg font-black text-gray-800 dark:text-gray-200">TK. {{ number_format($clusterTotal, 0) }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Processed At</p>
                                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $first->updated_at->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </section>
            @endif

            <!-- Admin's Own Room Bookings (Processed by Staff) -->
            @if($myRoomBookings->isNotEmpty())
            <section>
                <form action="{{ route('admin.bulk_delete_my_bookings') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="room">

                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-1 w-12 bg-amber-600 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">My Resort Bookings ({{ $myRoomBookings->count() }})</h2>
                        <div class="ml-auto flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-600 dark:text-gray-400 cursor-pointer bg-white dark:bg-gray-800 px-3 py-2 rounded-lg shadow-sm">
                                <input type="checkbox" onclick="toggleAll('room', this)" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                Select All
                            </label>
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded-lg transition shadow-sm" onclick="return confirm('Are you sure you want to delete selected bookings?')">
                                Delete Selected
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        @foreach($myRoomBookings as $request)
                            <div class="flex items-stretch gap-3">
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 w-12 flex items-center justify-center shrink-0">
                                    <input type="checkbox" name="ids[]" value="{{ $request->id }}" class="room-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transform scale-125">
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col md:flex-row flex-grow">
                                    <div class="p-6 flex-grow">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div>
                                                <h3 class="font-bold text-gray-800 dark:text-white">Your Personal Booking</h3>
                                                <p class="text-xs text-gray-500">Processed by Staff</p>
                                            </div>
                                            @if($request->status == 'accepted')
                                                <span class="ml-auto px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-full tracking-widest">✓ Accepted</span>
                                            @else
                                                <span class="ml-auto px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase rounded-full tracking-widest">✗ Rejected</span>
                                            @endif

                                            <button type="button" onclick="deleteSingle('{{ route('admin.delete_my_booking', ['type' => 'room', 'id' => $request->id]) }}')" class="ml-2 text-red-500 hover:text-red-700 p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Room #</p>
                                                <p class="font-bold text-amber-600">Room {{ $request->room->room_number }} ({{ $request->room->type }})</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Check In/Out</p>
                                                <p class="font-bold text-gray-800 dark:text-gray-200 text-xs">
                                                    {{ $request->check_in_time->format('d M, H:i') }} - {{ $request->check_out_time->format('d M, H:i') }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Price</p>
                                                <p class="font-bold text-gray-800 dark:text-gray-200">TK. {{ number_format($request->total_price, 0) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Processed At</p>
                                                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $request->updated_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </section>
            @endif

            <!-- Admin's Own Parking Bookings (Processed by Staff) -->
            @if($myParkingBookings->isNotEmpty())
            <section>
                <form action="{{ route('admin.bulk_delete_my_bookings') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="parking">

                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-1 w-12 bg-purple-600 rounded-full"></div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">My Parking Bookings ({{ $myParkingBookings->count() }})</h2>
                        <div class="ml-auto flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-600 dark:text-gray-400 cursor-pointer bg-white dark:bg-gray-800 px-3 py-2 rounded-lg shadow-sm">
                                <input type="checkbox" onclick="toggleAll('parking', this)" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                Select All
                            </label>
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-4 rounded-lg transition shadow-sm" onclick="return confirm('Are you sure you want to delete selected bookings?')">
                                Delete Selected
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        @foreach($myParkingBookings as $request)
                            <div class="flex items-stretch gap-3">
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 w-12 flex items-center justify-center shrink-0">
                                    <input type="checkbox" name="ids[]" value="{{ $request->id }}" class="parking-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transform scale-125">
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col md:flex-row flex-grow">
                                    <div class="p-6 flex-grow">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div>
                                                <h3 class="font-bold text-gray-800 dark:text-white">Your Personal Booking</h3>
                                                <p class="text-xs text-gray-500">Processed by Staff</p>
                                            </div>
                                            @if($request->status == 'accepted')
                                                <span class="ml-auto px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-full tracking-widest">✓ Accepted</span>
                                            @else
                                                <span class="ml-auto px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black uppercase rounded-full tracking-widest">✗ Rejected</span>
                                            @endif

                                            <button type="button" onclick="deleteSingle('{{ route('admin.delete_my_booking', ['type' => 'parking', 'id' => $request->id]) }}')" class="ml-2 text-red-500 hover:text-red-700 p-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Slot #</p>
                                                <p class="font-bold text-purple-600">Slot {{ $request->slot->slot_number }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Date & Time</p>
                                                <p class="font-bold text-gray-800 dark:text-gray-200 text-xs">
                                                    {{ $request->date }} <br> {{ $request->time_slot }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Price</p>
                                                <p class="font-bold text-gray-800 dark:text-gray-200">TK. {{ number_format($request->price, 0) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Processed At</p>
                                                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $request->updated_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </section>
            @endif

        </div>
    </div>

    <!-- Hidden Form for Single Deletion -->
    <form id="single-delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function toggleAll(type, source) {
            const checkboxes = document.querySelectorAll('.' + type + '-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = source.checked;
            });
        }

        function deleteSingle(url) {
            if(confirm('Are you sure you want to delete this notification?')) {
                const form = document.getElementById('single-delete-form');
                form.action = url;
                form.submit();
            }
        }
    </script>
</body>

</html>