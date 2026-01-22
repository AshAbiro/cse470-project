<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Ratings - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- Navbar -->
    <nav
        class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm fixed w-full z-10 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('staff.dashboard') }}"
                        class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
                <div class="font-medium text-lg text-gray-800 dark:text-gray-200 uppercase tracking-widest">
                    My Ratings
                </div>
                <div class="w-24"></div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Rating Summary Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex items-center gap-6">
                    <img class="h-24 w-24 rounded-full object-cover border-4 border-blue-500"
                        src="{{ $staff->profile_photo_url }}" alt="">
                    <div class="flex-grow">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $staff->name }}</h2>
                        <p class="text-gray-500">{{ $staff->email }}</p>
                        @if($staff->designation)
                            <p class="text-sm text-gray-400 mt-1">{{ $staff->designation }}</p>
                        @endif
                    </div>
                    <div class="text-center bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-xl">
                        <p class="text-sm text-gray-400 font-bold uppercase tracking-wider mb-2">Average Rating</p>
                        <div class="flex items-center gap-2 justify-center">
                            <span
                                class="text-5xl font-black text-yellow-500">{{ number_format($staff->averageRating(), 1) }}</span>
                            <span class="text-yellow-500 text-3xl">★</span>
                        </div>
                        <p class="text-sm text-gray-400 mt-2">{{ $ratings->count() }} total ratings</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 mb-6">
                <div class="h-1 w-12 bg-blue-600 rounded-full"></div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">All Ratings
                    ({{ $ratings->count() }})</h2>
            </div>

            @if($ratings->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm">
                    <p class="text-gray-500">No ratings received yet.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach($ratings as $rating)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    <img class="h-12 w-12 rounded-full object-cover border-2 border-gray-300"
                                        src="{{ $rating->user->profile_photo_url }}" alt="">
                                    <div>
                                        <h3 class="font-bold text-gray-800 dark:text-white">{{ $rating->user->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $rating->user->email }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $rating->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span
                                                class="text-xl {{ $i <= $rating->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                        @endfor
                                    </div>
                                    <p class="text-sm font-bold text-gray-600 dark:text-gray-400 mt-1">{{ $rating->rating }}/5
                                    </p>
                                </div>
                            </div>
                            @if($rating->comment)
                                <div class="mt-4 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $rating->comment }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

</body>

</html>