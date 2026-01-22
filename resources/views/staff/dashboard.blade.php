<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Dashboard - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">

    <!-- Staff Navbar -->
    <nav class="bg-navy-800 text-white shadow-lg fixed w-full z-50 top-0 h-16">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center">
            <!-- Left: User Profile & Name -->
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-full border-2 border-white overflow-hidden shadow-sm">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                        class="h-full w-full object-cover">
                </div>
                <span class="font-bold text-lg tracking-wide uppercase">{{ Auth::user()->name }} (Staff)</span>
            </div>

            <!-- Right: Logout Button -->
            <div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <input type="hidden" name="role" value="staff">
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-150 ease-in-out shadow-md active:scale-95 text-xs uppercase tracking-widest">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Secondary Navbar (Actions and Notification) -->
    <div
        class="fixed top-16 left-0 right-0 bg-white border-b border-gray-200 shadow-sm z-40 h-14 flex items-center justify-between px-4 sm:px-6 lg:px-8">
        <!-- New Action Buttons -->
        <div class="flex items-center space-x-2 sm:space-x-4">
            <a href="{{ route('staff.book-for-guest') }}"
                class="flex items-center space-x-2 px-4 py-2 bg-navy-800 text-white rounded-xl hover:opacity-90 transition duration-150 text-xs font-bold uppercase tracking-wider shadow-md hover:-translate-y-0.5 transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Book for Guest</span>
            </a>
            <a href="{{ route('staff.food') }}"
                class="flex items-center space-x-2 px-4 py-2 bg-navy-800 text-white rounded-xl hover:opacity-90 transition duration-150 text-xs font-bold uppercase tracking-wider shadow-md hover:-translate-y-0.5 transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                <span>Food</span>
            </a>
            <a href="{{ route('staff.rooms') }}"
                class="flex items-center space-x-2 px-4 py-2 bg-navy-800 text-white rounded-xl hover:opacity-90 transition duration-150 text-xs font-bold uppercase tracking-wider shadow-md hover:-translate-y-0.5 transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span>Rooms</span>
            </a>
            <a href="{{ route('staff.rides') }}"
                class="flex items-center space-x-2 px-4 py-2 bg-navy-800 text-white rounded-xl hover:opacity-90 transition duration-150 text-xs font-bold uppercase tracking-wider shadow-md hover:-translate-y-0.5 transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span>Rides</span>
            </a>
            <a href="{{ route('staff.tasks') }}"
                class="flex items-center space-x-2 px-4 py-2 bg-emerald-600 text-white rounded-xl hover:opacity-90 transition duration-150 text-xs font-bold uppercase tracking-wider shadow-md hover:-translate-y-0.5 transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <span>My Tasks</span>
            </a>
            <a href="{{ route('manage-quotes') }}"
                class="flex items-center space-x-2 px-4 py-2 bg-navy-800 text-white rounded-xl hover:opacity-90 transition duration-150 text-xs font-bold uppercase tracking-wider shadow-md hover:-translate-y-0.5 transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z">
                    </path>
                </svg>
                <span>Quotes</span>
            </a>
            <a href="{{ route('staff.chat') }}"
                class="flex items-center space-x-2 px-4 py-2 bg-indigo-600 text-white rounded-xl hover:opacity-90 transition duration-150 text-xs font-bold uppercase tracking-wider shadow-md hover:-translate-y-0.5 transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
                <span>Messenger</span>
            </a>
            <a href="{{ route('staff.my_ratings') }}"
                class="flex items-center space-x-2 px-4 py-2 bg-yellow-500 text-white rounded-xl hover:opacity-90 transition duration-150 text-xs font-bold uppercase tracking-wider shadow-md hover:-translate-y-0.5 transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                    </path>
                </svg>
                <span>⭐ My Ratings</span>
            </a>
        </div>

        <!-- Notification Icon & Text -->
        <div class="flex flex-col items-center gap-1">
            <a href="{{ route('staff.requests') }}"
                class="relative p-2 text-navy-700 hover:bg-gray-100 rounded-full transition block"
                title="Booking Requests">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                @php
                    $unreadChatCount = \App\Models\ChatMessage::where('created_at', '>', Auth::user()->last_chat_read_at ?? '2000-01-01')
                        ->where('user_id', '!=', Auth::id())
                        ->count();
                    $pendingRideCount = \App\Models\Booking::where('status', 'pending')->get()->groupBy(function ($item) {
                        return $item->booking_group_id ?? 'SINGLE-' . $item->id;
                    })->count();
                    $pendingRoomCount = \App\Models\RoomBooking::where('status', 'pending')->count();
                    $pendingParkingCount = \App\Models\ParkingBooking::where('status', 'pending')->count();
                    $totalPending = $pendingRideCount + $pendingRoomCount + $pendingParkingCount + $unreadChatCount;
                @endphp
                @if($totalPending > 0)
                    <span
                        class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] sm:text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full select-none shadow-sm">
                        {{ $totalPending }}
                    </span>
                @endif
            </a>
            @if($unreadChatCount > 0)
                <span
                    class="text-[9px] font-black text-orange-600 uppercase tracking-tighter animate-pulse text-center leading-none">
                    {{ $unreadChatCount }} unseen messages
                </span>
            @endif
        </div>
    </div>


    <style>
        .bg-navy-800 {
            background-color: #1a365d;
        }

        .text-navy-900 {
            color: #1a365d;
        }

        .text-navy-700 {
            color: #2b6cb0;
        }

        .text-navy-600 {
            color: #2c5282;
        }

        .bg-navy-50 {
            background-color: #f0f4f8;
        }

        .text-navy-600-alt {
            color: #2b6cb0;
        }

        .border-navy-100 {
            border-color: #ebf1f7;
        }
    </style>
</body>

</html>