<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Dashboard') }} - {{ config('app.name') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">

    <!-- Navbar: Fixed Top, Navy Blue -->
    <nav
        class="fixed top-0 left-0 right-0 bg-navy-600 text-white shadow-md z-50 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">

        <!-- Left: User Image & Name -->
        <div class="flex items-center space-x-3">
            <!-- Round shaped box containing user image -->
            <div class="h-10 w-10 rounded-full border-2 border-white overflow-hidden shrink-0">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
                    class="h-full w-full object-cover">
            </div>
            <!-- User Name -->
            <span class="font-semibold text-lg truncate max-w-[150px] sm:max-w-xs">
                {{ Auth::user()->name }}
            </span>
        </div>

        <!-- Right: Logout Button -->
        <div>
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit"
                    class="bg-navy-700 hover:bg-navy-800 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </nav>

    <!-- Secondary Navbar: Scrollable Row of Buttons -->
    <div
        class="fixed top-16 left-0 right-0 bg-white shadow-sm z-40 h-14 flex items-center px-4 sm:px-6 lg:px-8 overflow-x-auto">
        <div class="flex space-x-4 min-w-max">
            <a href="{{ route('client.profile') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">My
                Profile</a>
            <a href="{{ route('client.book-rides') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">Book
                Rides</a>
            <a href="{{ route('client.nawab-palace') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">Nawab
                Palace</a>
            <a href="{{ route('client.map') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">Map</a>
            <a href="{{ route('client.contact') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">Contact</a>
            <a href="{{ route('client.booking-history') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">Booking
                History</a>
            <a href="{{ route('client.parking') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">Parking</a>
            <a href="{{ route('client.rate-staff') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">⭐
                Rate Staff</a>
            <a href="{{ route('client.rate-rides') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">⭐
                Rate Rides</a>
            <a href="{{ route('client.rate-rooms') }}"
                class="text-navy-700 hover:text-navy-900 hover:bg-gray-100 px-3 py-2 rounded-md text-sm font-medium transition">⭐
                Rate Rooms</a>
            @livewire('client-notifications')
        </div>
    </div>

    <!-- Page Content (Padding for fixed navbars: 16 + 14 = 30 spacing units approx) -->
    <div class="pt-32 pb-10 px-4 sm:px-6 lg:px-8 min-h-screen">
        <!-- Content to make it potentially scrollable -->
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold text-navy-800 mb-4">Welcome to your Dashboard!</h3>
                <p class="text-gray-600 mb-4">Select an option from the menu above to get started.</p>
                <!-- Adding height to demonstrate scrollability as requested -->
                <div class="h-[1000px] border-l-4 border-navy-100 pl-4 text-gray-400 italic">
                    Scroll down to see the page response...
                </div>
            </div>
        </div>
    </div>

</body>

</html>