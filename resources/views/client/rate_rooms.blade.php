<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rate Resort Rooms - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .star-rating {
            display: inline-flex;
            gap: 0.25rem;
            cursor: pointer;
        }
        .star {
            font-size: 1.5rem;
            color: #d1d5db;
            transition: color 0.2s;
        }
        .star.filled {
            color: #fbbf24;
        }
        .star:hover,
        .star:hover ~ .star {
            color: #fbbf24;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm fixed w-full z-10 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
                <div class="font-medium text-lg text-gray-800 dark:text-gray-200 uppercase tracking-widest">
                    Rate Resort Rooms
                </div>
                <div class="w-24"></div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="flex items-center gap-4 mb-6">
                <div class="h-1 w-12 bg-amber-600 rounded-full"></div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">Nawab Palace Rooms ({{ $rooms->count() }})</h2>
            </div>

            @if($rooms->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm">
                    <p class="text-gray-500">No rooms available to rate.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($rooms as $room)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden {{ in_array($room->room_number, [202, 203]) ? 'ring-2 ring-yellow-400' : '' }}">
                            <form action="{{ route('client.submit-room-rating') }}" method="POST">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                
                                @if($room->image_path)
                                    <img src="{{ asset('storage/' . $room->image_path) }}" alt="Room {{ $room->room_number }}" class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 {{ in_array($room->room_number, [202, 203]) ? 'bg-gradient-to-br from-yellow-400 to-amber-600' : 'bg-gradient-to-br from-amber-400 to-orange-500' }} flex items-center justify-center">
                                        <span class="text-white text-3xl font-black">{{ $room->room_number }}</span>
                                    </div>
                                @endif

                                <div class="p-5">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Room {{ $room->room_number }}</h3>
                                            <p class="text-sm text-amber-600 font-semibold">{{ ucfirst($room->type) }}</p>
                                            @if(in_array($room->room_number, [202, 203]))
                                                <span class="inline-block mt-1 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded">VIP</span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center gap-1">
                                                <span class="text-lg font-black text-yellow-500">{{ number_format($room->averageRating(), 1) }}</span>
                                                <span class="text-yellow-500">★</span>
                                            </div>
                                            <p class="text-xs text-gray-400">{{ $room->roomRatings->count() }} ratings</p>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg mb-3">
                                        <p class="text-xs text-gray-400 font-bold uppercase">Price per 12h</p>
                                        <p class="text-sm font-bold text-amber-600">TK. {{ number_format($room->price_per_12h, 0) }}</p>
                                    </div>

                                    @if($room->features)
                                        <div class="mb-3">
                                            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Features</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $room->features }}</p>
                                        </div>
                                    @endif

                                    <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl border border-gray-100 dark:border-gray-700 mb-3">
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Your Rating</p>
                                        <div class="star-rating" data-rating="{{ $myRatings[$room->id] ?? 0 }}">
                                            @for($i = 5; $i >= 1; $i--)
                                                <span class="star {{ ($myRatings[$room->id] ?? 0) >= $i ? 'filled' : '' }}" data-value="{{ $i }}">★</span>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="rating" value="{{ $myRatings[$room->id] ?? 0 }}" required>
                                        @if(isset($myRatings[$room->id]))
                                            <p class="text-xs text-green-600 mt-2">✓ You rated this room</p>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-1">Comment (Optional)</label>
                                        <textarea name="comment" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 text-xs" placeholder="Share your experience..."></textarea>
                                    </div>

                                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 hover:scale-105 text-white font-bold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-sm shadow-amber-200 active:scale-95 text-sm">
                                        Submit Rating
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <script>
        document.querySelectorAll('.star-rating').forEach(container => {
            const stars = container.querySelectorAll('.star');
            const input = container.parentElement.querySelector('input[name="rating"]');
            
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    input.value = value;
                    
                    stars.forEach(s => {
                        if (parseInt(s.getAttribute('data-value')) <= parseInt(value)) {
                            s.classList.add('filled');
                        } else {
                            s.classList.remove('filled');
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>
