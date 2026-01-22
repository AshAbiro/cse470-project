<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-navy-700 mb-4">Booking History</h1>
                <p class="mb-6">This page is under construction.</p>

                <!-- Floating Back Button -->
                <div class="fixed bottom-6 right-6 z-50">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center justify-center bg-navy-600 hover:bg-navy-700 text-white font-bold rounded-full w-16 h-16 shadow-2xl transition transform hover:scale-110 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-navy-300"
                        title="Back to Dashboard">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>