<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View All Ratings - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm fixed w-full z-10 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
                <div class="font-medium text-lg text-gray-800 dark:text-gray-200 uppercase tracking-widest">
                    All Ratings
                </div>
                <div class="w-24"></div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-12">

            <!-- Staff Ratings -->
            <section>
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-1 w-12 bg-blue-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">Staff Ratings ({{ $staffRatings->count() }})</h2>
                </div>

                @if($staffRatings->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm">
                        <p class="text-gray-500">No staff ratings yet.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($staffRatings as $rating)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col md:flex-row">
                                <div class="p-6 flex-grow">
                                    <div class="flex items-center gap-4 mb-4">
                                        <img class="h-12 w-12 rounded-full object-cover border-2 border-blue-500" src="{{ $rating->user->profile_photo_url }}" alt="">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white">{{ $rating->user->name }}</h3>
                                            <p class="text-xs text-gray-500">rated</p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                        <img class="h-12 w-12 rounded-full object-cover border-2 border-green-500" src="{{ $rating->staff->profile_photo_url }}" alt="">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white">{{ $rating->staff->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $rating->staff->designation ?? 'Staff' }}</p>
                                        </div>
                                    </div>
                                    @if($rating->comment)
                                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $rating->comment }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-6 flex flex-col justify-center items-center gap-2 border-t md:border-t-0 md:border-l border-gray-100 dark:border-gray-700 w-full md:w-40">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-2xl {{ $i <= $rating->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                        @endfor
                                    </div>
                                    <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $rating->rating }}/5</p>
                                    <p class="text-xs text-gray-400">{{ $rating->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Ride Ratings -->
            <section>
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-1 w-12 bg-purple-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">Ride Ratings ({{ $rideRatings->count() }})</h2>
                </div>

                @if($rideRatings->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm">
                        <p class="text-gray-500">No ride ratings yet.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($rideRatings as $rating)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-purple-500" src="{{ $rating->user->profile_photo_url }}" alt="">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white text-sm">{{ $rating->user->name }}</h3>
                                            <p class="text-xs text-gray-500">rated {{ $rating->ride->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-lg {{ $i <= $rating->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                            @endfor
                                        </div>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $rating->rating }}/5</p>
                                    </div>
                                </div>
                                @if($rating->comment)
                                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg">
                                        <p class="text-xs text-gray-700 dark:text-gray-300">{{ $rating->comment }}</p>
                                    </div>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">{{ $rating->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Room Ratings -->
            <section>
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-1 w-12 bg-amber-600 rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">Room Ratings ({{ $roomRatings->count() }})</h2>
                </div>

                @if($roomRatings->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm">
                        <p class="text-gray-500">No room ratings yet.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($roomRatings as $rating)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-amber-500" src="{{ $rating->user->profile_photo_url }}" alt="">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white text-sm">{{ $rating->user->name }}</h3>
                                            <p class="text-xs text-gray-500">rated Room {{ $rating->room->room_number }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="text-lg {{ $i <= $rating->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                            @endfor
                                        </div>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $rating->rating }}/5</p>
                                    </div>
                                </div>
                                @if($rating->comment)
                                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg">
                                        <p class="text-xs text-gray-700 dark:text-gray-300">{{ $rating->comment }}</p>
                                    </div>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">{{ $rating->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>
    </div>

</body>

</html>
