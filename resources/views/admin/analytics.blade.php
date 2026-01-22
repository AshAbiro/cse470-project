<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Professional Analytics Command Center') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-12">

            <!-- Back Button -->
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 transition shadow-md">
                    &larr; Back to Dashboard
                </a>
            </div>

            @if(isset($summary))
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700">
                        <p class="text-xs uppercase tracking-widest text-gray-400 font-bold">Total Users</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white mt-2">{{ $summary['total_users'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Staff: {{ $summary['total_staff'] ?? 0 }} · Clients: {{ $summary['total_clients'] ?? 0 }}</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700">
                        <p class="text-xs uppercase tracking-widest text-gray-400 font-bold">Total Revenue</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white mt-2">BDT {{ number_format($summary['total_revenue'] ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">This month: BDT {{ number_format($summary['monthly_revenue'] ?? 0, 2) }}</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700">
                        <p class="text-xs uppercase tracking-widest text-gray-400 font-bold">Bookings</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white mt-2">{{ $summary['total_bookings'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Pending: {{ $summary['pending_bookings'] ?? 0 }} · Confirmed: {{ $summary['confirmed_bookings'] ?? 0 }}</p>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700">
                        <p class="text-xs uppercase tracking-widest text-gray-400 font-bold">Occupancy & Rides</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white mt-2">{{ $summary['occupancy_rate'] ?? 0 }}%</p>
                        <p class="text-xs text-gray-500 mt-1">Active rides: {{ $summary['active_rides'] ?? 0 }} / {{ $summary['total_rides'] ?? 0 }}</p>
                    </div>
                </div>
            @endif

            <!-- Analytics Dashboard Section -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl sm:rounded-[2.5rem] border border-gray-100 dark:border-gray-700 animate-fade-in">
                <div class="p-8 lg:p-12">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
                        <div>
                            <h1
                                class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-2">
                                Park Intelligence</h1>
                            <p class="text-gray-500 dark:text-gray-400">Data-driven insights for optimized operations
                            </p>
                        </div>

                        <!-- Time Filters -->
                        <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-2xl shadow-inner">
                            <button onclick="updateFilter('day')" id="filter-day"
                                class="filter-btn px-6 py-2 rounded-xl text-sm font-bold transition">Day</button>
                            <button onclick="updateFilter('month')" id="filter-month"
                                class="filter-btn px-6 py-2 rounded-xl text-sm font-bold bg-white dark:bg-gray-600 shadow-sm transition">Month</button>
                            <button onclick="updateFilter('year')" id="filter-year"
                                class="filter-btn px-6 py-2 rounded-xl text-sm font-bold transition">Year</button>
                        </div>
                    </div>

                    <!-- Category Navbar -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-12">
                        <button onclick="updateCategory('best_ride')" id="cat-best_ride"
                            class="cat-btn flex flex-col items-center p-6 rounded-3xl border-2 border-indigo-50 transition transform hover:scale-105 active:scale-95 bg-indigo-50/50 dark:bg-indigo-900/10 border-indigo-500">
                            <svg class="w-8 h-8 text-indigo-600 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span
                                class="text-xs font-black uppercase tracking-widest text-indigo-900 dark:text-indigo-100">Best
                                Ride</span>
                        </button>
                        <button onclick="updateCategory('best_room')" id="cat-best_room"
                            class="cat-btn flex flex-col items-center p-6 rounded-3xl border-2 border-transparent transition transform hover:scale-105 active:scale-95 bg-gray-50 dark:bg-gray-700/50">
                            <svg class="w-8 h-8 text-amber-500 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            <span
                                class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">Best
                                Room</span>
                        </button>
                        <button onclick="updateCategory('biggest_customer')" id="cat-biggest_customer"
                            class="cat-btn flex flex-col items-center p-6 rounded-3xl border-2 border-transparent transition transform hover:scale-105 active:scale-95 bg-gray-50 dark:bg-gray-700/50">
                            <svg class="w-8 h-8 text-emerald-500 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <span
                                class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">Top
                                Spender</span>
                        </button>
                        <button onclick="updateCategory('best_dish')" id="cat-best_dish"
                            class="cat-btn flex flex-col items-center p-6 rounded-3xl border-2 border-transparent transition transform hover:scale-105 active:scale-95 bg-gray-50 dark:bg-gray-700/50">
                            <svg class="w-8 h-8 text-rose-500 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18z">
                                </path>
                            </svg>
                            <span
                                class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">Best
                                Dish</span>
                        </button>
                        <button onclick="updateCategory('crowdiest_day')" id="cat-crowdiest_day"
                            class="cat-btn flex flex-col items-center p-6 rounded-3xl border-2 border-transparent transition transform hover:scale-105 active:scale-95 bg-gray-50 dark:bg-gray-700/50">
                            <svg class="w-8 h-8 text-blue-500 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span
                                class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">Crowd
                                Day</span>
                        </button>
                    </div>

                    <!-- Chart Container -->
                    <div
                        class="relative bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] p-8 min-h-[400px] border border-gray-100 dark:border-gray-700 shadow-inner">
                        <canvas id="analyticsChart"></canvas>
                    </div>

                    <!-- Highlight Section -->
                    <div id="stats-highlight"
                        class="mt-12 p-8 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-[2rem] text-white shadow-xl shadow-indigo-100 dark:shadow-none flex items-center justify-between animate-pulse">
                        <div class="flex items-center gap-6">
                            <div class="p-4 bg-white/20 rounded-2xl">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 id="highlight-title" class="text-2xl font-black uppercase tracking-widest">
                                    Calculating Data...</h3>
                                <p id="highlight-desc" class="text-indigo-100 opacity-90">Analyzing performance across
                                    all metrics.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let currentCategory = 'best_ride';
        let currentFilter = 'month';
        let mainChart = null;

        const colors = {
            'best_ride': ['rgba(79, 70, 229, 0.8)', 'rgba(79, 70, 229, 0.2)'],
            'best_room': ['rgba(245, 158, 11, 0.8)', 'rgba(245, 158, 11, 0.2)'],
            'biggest_customer': ['rgba(16, 185, 129, 0.8)', 'rgba(16, 185, 129, 0.2)'],
            'best_dish': ['rgba(244, 63, 94, 0.8)', 'rgba(244, 63, 94, 0.2)'],
            'crowdiest_day': ['rgba(59, 130, 246, 0.8)', 'rgba(59, 130, 246, 0.2)']
        };

        const icons = {
            'best_ride': 'indigo',
            'best_room': 'amber',
            'biggest_customer': 'emerald',
            'best_dish': 'rose',
            'crowdiest_day': 'blue'
        };

        async function fetchStats() {
            try {
                const response = await fetch(`/admin/analytics/data?category=${currentCategory}&filter=${currentFilter}`);
                const data = await response.json();
                renderChart(data);
                updateHighlight(data);
            } catch (error) {
                console.error('Error fetching analytics:', error);
            }
        }

        function renderChart(statsData) {
            const ctx = document.getElementById('analyticsChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, colors[currentCategory][0]);
            gradient.addColorStop(1, colors[currentCategory][1]);

            if (mainChart) {
                mainChart.destroy();
            }

            mainChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: statsData.labels,
                    datasets: [{
                        label: statsData.title,
                        data: statsData.data,
                        backgroundColor: gradient,
                        borderRadius: 15,
                        borderSkipped: false,
                        maxBarThickness: 60
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 15,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            cornerRadius: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { display: false },
                            ticks: {
                                font: { weight: 'bold' },
                                color: '#9ca3af'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { weight: 'bold' },
                                color: '#9ca3af'
                            }
                        }
                    }
                }
            });
        }

        function updateHighlight(data) {
            const highlightSection = document.getElementById('stats-highlight');
            const highlightTitle = document.getElementById('highlight-title');
            const highlightDesc = document.getElementById('highlight-desc');

            // Remove old color classes
            highlightSection.classList.remove('from-indigo-600', 'from-amber-600', 'from-emerald-600', 'from-rose-600', 'from-blue-600');
            highlightSection.classList.remove('to-purple-600', 'to-orange-600', 'to-teal-600', 'to-pink-600', 'to-cyan-600');

            // Add new color classes
            const colorMap = {
                'indigo': ['from-indigo-600', 'to-purple-600'],
                'amber': ['from-amber-600', 'to-orange-600'],
                'emerald': ['from-emerald-600', 'to-teal-600'],
                'rose': ['from-rose-600', 'to-pink-600'],
                'blue': ['from-blue-600', 'to-cyan-600']
            };

            const colorClass = colorMap[icons[currentCategory]];
            highlightSection.classList.add(colorClass[0], colorClass[1]);
            highlightSection.classList.remove('animate-pulse');

            highlightTitle.innerText = data.title;
            highlightDesc.innerText = data.highlight;
        }

        function updateCategory(cat) {
            currentCategory = cat;

            // Update UI buttons
            document.querySelectorAll('.cat-btn').forEach(btn => {
                btn.classList.remove('border-indigo-500', 'border-amber-500', 'border-emerald-500', 'border-rose-500', 'border-blue-500', 'bg-indigo-50/50', 'bg-amber-50/50', 'bg-emerald-50/50', 'bg-rose-50/50', 'bg-blue-50/50');
                btn.classList.add('bg-gray-50', 'dark:bg-gray-700/50', 'border-transparent');
                btn.querySelector('span').classList.remove('text-indigo-900', 'text-amber-900', 'text-emerald-900', 'text-rose-900', 'text-blue-900');
                btn.querySelector('span').classList.add('text-gray-500');
            });

            const activeBtn = document.getElementById(`cat-${cat}`);
            const themeColor = icons[cat];
            activeBtn.classList.add(`border-${themeColor}-500`, `bg-${themeColor}-50/50`);
            activeBtn.classList.remove('bg-gray-50', 'border-transparent');
            activeBtn.querySelector('span').classList.add(`text-${themeColor}-900`);
            activeBtn.querySelector('span').classList.remove('text-gray-500');

            fetchStats();
        }

        function updateFilter(filter) {
            currentFilter = filter;

            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'dark:bg-gray-600', 'shadow-sm');
            });
            document.getElementById(`filter-${filter}`).classList.add('bg-white', 'dark:bg-gray-600', 'shadow-sm');

            fetchStats();
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', fetchStats);
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }
    </style>
</x-app-layout>
