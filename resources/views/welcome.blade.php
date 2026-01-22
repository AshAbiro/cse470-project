<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans text-gray-900 bg-white">

    <!-- Navbar -->
    <nav class="bg-navy-600 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold tracking-wide uppercase">Amusement Park</a>
                </div>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#attractions" class="hover:text-navy-100 transition duration-300">Attractions</a>
                    <a href="#tickets" class="hover:text-navy-100 transition duration-300">Tickets</a>
                    <a href="#info" class="hover:text-navy-100 transition duration-300">Info</a>

                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4 border-l border-navy-500 pl-6">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="font-semibold hover:text-navy-100">Dashboard</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="role" value="{{ Auth::user()->role }}">
                                    <button type="submit" class="font-semibold hover:text-red-300 transition ml-4">
                                        Log out
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="font-semibold hover:text-navy-100">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="bg-white text-navy-600 px-4 py-2 rounded-full font-semibold hover:bg-navy-50 transition duration-300">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-navy-700 h-[70vh] flex items-center justify-center overflow-hidden">
        <!-- Abstract Background Shape -->
        <div class="absolute inset-0 opacity-20">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>

        <div class="relative z-10 text-center px-4">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 drop-shadow-lg animate-fade-in-up">
                Experience the Thrill
            </h1>
            <p class="text-xl md:text-2xl text-navy-100 mb-10 max-w-2xl mx-auto drop-shadow-md">
                Unforgettable moments await at the world's premier amusement park.
            </p>
            @if($dailyQuote)
                <div
                    class="mt-12 w-full overflow-hidden relative py-4 bg-white/5 backdrop-blur-sm border-y border-white/10 shadow-2xl">
                    <div class="whitespace-nowrap animate-marquee flex items-center">
                        <span
                            class="text-3xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-500 uppercase tracking-[0.2em] drop-shadow-2xl">
                            &nbsp; &nbsp; &nbsp; &nbsp; "{{ $dailyQuote->content }}" — <span
                                class="italic text-white opacity-80">{{ $dailyQuote->author ?? 'Anonymous' }}</span> &nbsp;
                            &nbsp; &nbsp; &nbsp;
                        </span>
                        <!-- Repeat for seamless loop -->
                        <span
                            class="text-3xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-500 uppercase tracking-[0.2em] drop-shadow-2xl">
                            &nbsp; &nbsp; &nbsp; &nbsp; "{{ $dailyQuote->content }}" — <span
                                class="italic text-white opacity-80">{{ $dailyQuote->author ?? 'Anonymous' }}</span> &nbsp;
                            &nbsp; &nbsp; &nbsp;
                        </span>
                    </div>
                </div>
            @endif

            <style>
                @keyframes marquee {
                    0% {
                        transform: translateX(0);
                    }

                    100% {
                        transform: translateX(-50%);
                    }
                }

                .animate-marquee {
                    display: flex;
                    width: max-content;
                    animation: marquee 30s linear infinite;
                }

                .animate-marquee:hover {
                    animation-play-state: paused;
                }
            </style>
        </div>
    </div>

    <!-- Featured Attractions -->
    <section id="attractions" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-navy-800 mb-4">Featured Attractions</h2>
                <div class="h-1 w-24 bg-navy-500 mx-auto rounded"></div>
                <p class="mt-4 text-gray-600 text-lg">Discover our most popular rides and experiences.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($rides as $ride)
                    <!-- Ride Card -->
                    <div
                        class="group bg-gray-50 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300">
                        <div class="h-64 bg-navy-200 flex items-center justify-center relative overflow-hidden">
                            @if($ride->image_path && (str_starts_with($ride->image_path, 'http') || Storage::disk('public')->exists($ride->image_path)))
                                <img src="{{ str_starts_with($ride->image_path, 'http') ? $ride->image_path : Storage::url($ride->image_path) }}"
                                    alt="{{ $ride->name }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                            @else
                                <span
                                    class="text-navy-500 font-bold text-xl relative z-10 group-hover:scale-110 transition duration-500">{{ $ride->name }}</span>
                                <div class="absolute inset-0 bg-gradient-to-t from-navy-900/50 to-transparent"></div>
                            @endif
                        </div>
                        <div class="p-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-2xl font-bold text-navy-700">{{ $ride->name }}</h3>
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded uppercase">
                                    {{ $ride->thrill_level > 7 ? 'Extreme' : ($ride->thrill_level > 4 ? 'Moderate' : 'Family') }}
                                </span>
                            </div>
                            <p class="text-gray-600 mb-6 line-clamp-3">{{ $ride->description }}</p>
                            <a href="{{ route('login') }}"
                                class="text-navy-600 font-semibold hover:text-navy-800 flex items-center">
                                Learn More
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 text-center">
                <a href="#"
                    class="inline-block border-2 border-navy-600 text-navy-600 font-bold text-lg px-8 py-3 rounded-full hover:bg-navy-600 hover:text-white transition duration-300">
                    View All Attractions
                </a>
            </div>
        </div>
    </section>

    <!-- Tickets Section -->
    <section id="tickets" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-navy-800 mb-4">Get Your Tickets</h2>
                <div class="h-1 w-24 bg-navy-500 mx-auto rounded"></div>
                <p class="mt-4 text-gray-600 text-lg">Choose the perfect pass for your adventure.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($tickets as $ticket)
                    @php
                        $isEntryTicket = $ticket->name === 'Park Entry Ticket';
                    @endphp
                    <div
                        class="bg-white rounded-2xl shadow-xl overflow-hidden hover:scale-105 transition duration-300 flex flex-col h-full transform {{ $isEntryTicket ? 'scale-110 ring-4 ring-amber-400 z-10 relative' : ($loop->iteration == 2 ? 'scale-105 border-4 border-navy-400 relative' : '') }}">

                        @if($isEntryTicket)
                            <div
                                class="absolute top-0 right-0 bg-amber-400 text-amber-900 text-xs font-bold px-3 py-1 rounded-bl-lg uppercase tracking-tight">
                                Mandatory</div>
                        @elseif($loop->iteration == 2)
                            <div
                                class="absolute top-0 right-0 bg-navy-800 text-white text-xs font-bold px-3 py-1 rounded-bl-lg uppercase tracking-tight">
                                POPULAR</div>
                        @endif

                        <div
                            class="{{ $isEntryTicket ? 'bg-gradient-to-r from-amber-500 to-amber-600' : ($loop->iteration == 2 ? 'bg-navy-800' : 'bg-navy-600') }} py-5 text-center px-4">
                            <h3
                                class="{{ $isEntryTicket ? 'text-white' : ($loop->iteration == 2 ? 'text-gray-100' : 'text-white') }} font-black text-xl uppercase tracking-wider mb-1">
                                {{ $ticket->name }}
                            </h3>
                            @if($isEntryTicket)
                                <p class="text-amber-100 text-[10px] font-bold uppercase tracking-widest">Entry to All
                                    Facilities</p>
                            @endif
                        </div>

                        <div class="p-8 text-center flex-grow flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-center gap-1 mb-2">
                                    <span class="text-2xl font-bold text-navy-600 border-b-2 border-navy-100">BDT</span>
                                    <span
                                        class="text-5xl font-black text-navy-800 tracking-tighter">{{ number_format($ticket->price, 0) }}</span>
                                </div>
                                <p class="text-gray-500 text-sm font-medium mb-6">Per Person</p>
                                <p class="text-gray-600 mb-8 min-h-[3rem] italic text-sm leading-relaxed px-2">
                                    "{{ $ticket->description }}"</p>

                                <ul class="text-left space-y-4 mb-8 text-sm">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 {{ $isEntryTicket ? 'text-amber-500' : 'text-green-500' }} mr-3 shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700 font-medium">
                                            {{ isset($ticket->valid_duration_days) ? "Valid for $ticket->valid_duration_days full day" : "Single Entry Access" }}
                                        </span>
                                    </li>
                                    @if($isEntryTicket)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-amber-500 mr-3 shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-gray-700 font-medium">Access to all park zones</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <a href="{{ route('login') }}"
                                class="block w-full {{ $isEntryTicket ? 'bg-amber-500 hover:bg-amber-600 text-white' : ($loop->iteration == 2 ? 'bg-navy-800 hover:bg-navy-900 text-white' : 'bg-navy-600 hover:bg-navy-700 text-white') }} font-black py-4 rounded-2xl hover:shadow-2xl transition-all duration-300 transform active:scale-95 uppercase tracking-wide">
                                Book Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Info / Stats Section -->
    <section id="info" class="bg-navy-800 py-20 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-navy-100 mb-2">{{ $totalRides }}</div>
                    <div class="text-gray-400 uppercase tracking-widest text-sm">Rides</div>
                </div>

                <div>
                    <div class="text-4xl font-bold text-navy-100 mb-2">365</div>
                    <div class="text-gray-400 uppercase tracking-widest text-sm">Days Open</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-navy-100 mb-2">100%</div>
                    <div class="text-gray-400 uppercase tracking-widest text-sm">Fun</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-navy-900 text-white py-12 border-t border-navy-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-6 md:mb-0">
                    <h3 class="text-2xl font-bold uppercase tracking-wide">Amusement Park</h3>
                    <p class="text-navy-300 mt-2">Making memories since 2025.</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white transition">Contact Us</a>
                </div>
            </div>
            <div class="mt-8 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Amusement Park Management System. All rights reserved.
            </div>
        </div>
    </footer>
</body>

</html>