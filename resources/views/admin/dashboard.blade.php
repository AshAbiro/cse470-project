<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- Navbar -->
    <nav
        class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm fixed w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left Side: User Image & Name -->
                <div class="flex items-center">
                    <div class="shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                            alt="{{ Auth::user()->name }}" />
                    </div>
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                        {{ Auth::user()->name }}
                    </div>
                </div>

                <!-- Right Side: Logout Button -->
                <div class="flex items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <input type="hidden" name="role" value="admin">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="pt-32 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            @php
                $notificationCount = \App\Models\Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                $unreadChatCount = \App\Models\ChatMessage::where('created_at', '>', Auth::user()->last_chat_read_at ?? '2000-01-01')
                    ->where('user_id', '!=', Auth::id())
                    ->count();
            @endphp
            <!-- Notification Bar -->
            <div class="mb-8">
                <a href="{{ route('admin.booking_requests') }}"
                    class="flex items-center justify-between bg-white dark:bg-gray-800 border-l-4 border-blue-500 p-4 rounded-r-xl shadow-sm hover:shadow-md transition group">
                    <div class="flex items-center gap-4">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Alert for Notifications</p>
                            <div class="flex flex-col gap-0.5">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total client booking requests:
                                    <span class="font-bold text-blue-500">{{ $notificationCount }}</span>
                                </p>
                                @if($unreadChatCount > 0)
                                    <p class="text-xs text-orange-500 font-bold animate-pulse">
                                        New unread chat messages: {{ $unreadChatCount }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-blue-500">
                        <span class="text-xs font-bold uppercase tracking-wider">View All</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4"> <!-- Changed to 3 columns per row as requested -->

                @php
                    $items = [
                        ['route' => 'admin.my_profile', 'title' => 'My Profile', 'image' => 'profile.png', 'color' => 'bg-blue-500', 'icon' => null],
                        ['route' => 'admin.manage_rides', 'title' => 'Manage Rides', 'image' => 'rides.png', 'color' => 'bg-red-500', 'icon' => null],
                        ['route' => 'admin.nawab_palace', 'title' => 'Nawab Palace', 'image' => 'palace.png', 'color' => 'bg-amber-500', 'icon' => null],
                        ['route' => 'admin.manage_tickets', 'title' => 'Manage Tickets', 'image' => 'tickets.png', 'color' => 'bg-green-500', 'icon' => null],
                        ['route' => 'admin.analytics', 'title' => 'Analytics', 'image' => 'analytics.png', 'color' => 'bg-indigo-500', 'icon' => null],
                        ['route' => 'admin.personal_bookings', 'title' => 'Personal Bookings', 'image' => 'bookings.png', 'color' => 'bg-purple-500', 'icon' => null],
                        ['route' => 'admin.booking_requests', 'title' => 'Requests', 'image' => null, 'color' => 'bg-orange-500', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>'],
                        ['route' => 'admin.map', 'title' => 'Map', 'image' => null, 'color' => 'bg-gradient-to-br from-teal-400 to-teal-600', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" /></svg>'],
                        ['route' => 'admin.park_maintenance', 'title' => 'Park Maintenance', 'image' => null, 'color' => 'bg-gradient-to-br from-gray-500 to-gray-700', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" /></svg>'],
                        ['route' => 'manage-quotes', 'title' => 'Quotes', 'image' => null, 'color' => 'bg-pink-500', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>'],
                        ['route' => 'admin.chat', 'title' => 'Messenger', 'image' => null, 'color' => 'bg-indigo-600', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>'],
                        ['route' => 'admin.view_ratings', 'title' => '⭐ View Ratings', 'image' => null, 'color' => 'bg-yellow-500', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>'],
                    ];
                @endphp

                @foreach($items as $item)
                    <!-- Ticket Style Card - Size Reduced Further -->
                    <a href="{{ route($item['route']) }}"
                        class="group relative flex flex-col bg-white dark:bg-gray-800 shadow-md hover:shadow-2xl rounded-2xl overflow-hidden transform hover:-translate-y-2 transition duration-300">

                        <!-- Top Section: Image or Icon - Height Reduced (h-32 -> h-24) -->
                        <div class="h-24 w-full overflow-hidden relative">
                            @if($item['image'])
                                @if(isset($item['external']) && $item['external'])
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                @else
                                    <img src="{{ asset('images/admin/' . $item['image']) }}" alt="{{ $item['title'] }}"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                @endif
                            @elseif($item['icon'])
                                <!-- SVG Icon Fallback -->
                                <div class="w-full h-full {{ $item['color'] }} flex items-center justify-center">
                                    {!! $item['icon'] !!}
                                </div>
                            @else
                                <!-- Text Fallback -->
                                <div class="w-full h-full {{ $item['color'] }} flex items-center justify-center">
                                    <span class="text-white font-bold text-xl opacity-50">{{ $item['fallback_text'] }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black bg-opacity-10 group-hover:bg-opacity-0 transition"></div>
                        </div>

                        <!-- Perforation Line -->
                        <div class="relative h-3 bg-gray-100 dark:bg-gray-900">
                            <!-- Left Notch -->
                            <div
                                class="absolute -left-2 top-1/2 -mt-2 h-4 w-4 rounded-full bg-gray-100 dark:bg-gray-900 z-10">
                            </div>
                            <!-- Dashed Line -->
                            <div
                                class="absolute top-1/2 left-2 right-2 border-t-2 border-dashed border-gray-300 dark:border-gray-600">
                            </div>
                            <!-- Right Notch -->
                            <div
                                class="absolute -right-2 top-1/2 -mt-2 h-4 w-4 rounded-full bg-gray-100 dark:bg-gray-900 z-10">
                            </div>
                        </div>

                        <!-- Bottom Section: Title - Padding Reduced -->
                        <div
                            class="p-2 flex flex-col items-center justify-center text-center bg-white dark:bg-gray-800 flex-grow relative">
                            <!-- Pseudo-notches for bottom corners to enhance ticket look -->
                            <div class="absolute -left-2 top-0 h-3 w-3 bg-gray-100 dark:bg-gray-900 rounded-full"></div>
                            <div class="absolute -right-2 top-0 h-3 w-3 bg-gray-100 dark:bg-gray-900 rounded-full"></div>

                            <!-- Font Size Reduced (text-lg -> text-base) -->
                            <h3 class="text-base font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                                {{ $item['title'] }}
                            </h3>
                            <p class="text-[9px] text-gray-500 dark:text-gray-400 mt-0.5">Click to View</p>
                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </div>

</body>

</html>