<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rate Rides - {{ config('app.name') }}</title>
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
                    Rate Our Rides
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
                <div class="h-1 w-12 bg-purple-600 rounded-full"></div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-wider">Available Rides ({{ $rides->count() }})</h2>
            </div>

            @if($rides->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center shadow-sm">
                    <p class="text-gray-500">No rides available to rate.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($rides as $ride)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <form action="{{ route('client.submit-ride-rating') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ride_id" value="{{ $ride->id }}">
                                
                                @if($ride->image_path)
                                    @if(Str::startsWith($ride->image_path, ['http://', 'https://']))
                                        <img src="{{ $ride->image_path }}" alt="{{ $ride->name }}" class="w-full h-48 object-cover">
                                    @else
                                        <img src="{{ asset('storage/' . $ride->image_path) }}" alt="{{ $ride->name }}" class="w-full h-48 object-cover">
                                    @endif
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                        <span class="text-white text-4xl font-black">{{ substr($ride->name, 0, 1) }}</span>
                                    </div>
                                @endif

                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="font-bold text-gray-800 dark:text-white text-lg">{{ $ride->name }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ $ride->description }}</p>
                                        </div>
                                        <div class="text-right ml-4">
                                            <div class="flex items-center gap-1">
                                                <span class="text-xl font-black text-yellow-500">{{ number_format($ride->averageRating(), 1) }}</span>
                                                <span class="text-yellow-500">★</span>
                                            </div>
                                            <p class="text-xs text-gray-400">{{ $ride->rideRatings->count() }} ratings</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-400 font-bold uppercase">Price</p>
                                            <p class="text-sm font-bold text-purple-600">TK. {{ number_format($ride->price, 0) }}</p>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg">
                                            <p class="text-xs text-gray-400 font-bold uppercase">Thrill Level</p>
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ ucfirst($ride->thrill_level) }}</p>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700 mb-4">
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Your Rating</p>
                                        <div class="star-rating" data-rating="{{ $myRatings[$ride->id] ?? 0 }}">
                                            @for($i = 5; $i >= 1; $i--)
                                                <span class="star {{ ($myRatings[$ride->id] ?? 0) >= $i ? 'filled' : '' }}" data-value="{{ $i }}">★</span>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="rating" value="{{ $myRatings[$ride->id] ?? 0 }}" required>
                                        @if(isset($myRatings[$ride->id]))
                                            <p class="text-xs text-green-600 mt-2">✓ You rated this ride</p>
                                        @endif
                                    </div>

                                    <div class="mb-4">
                                        <label class="text-xs text-gray-400 font-bold uppercase tracking-wider block mb-2">Comment (Optional)</label>
                                        <textarea name="comment" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 text-sm" placeholder="Share your experience..."></textarea>
                                    </div>

                                    <button type="submit" class="w-full bg-purple-500 hover:bg-purple-600 hover:scale-105 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-sm shadow-purple-200 active:scale-95">
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
