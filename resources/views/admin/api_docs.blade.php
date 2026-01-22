<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('API Docs') }}
            </h2>
            <a href="{{ route('home') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 transition shadow-md">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">OpenAPI Spec</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Download the spec and load it in Swagger UI, Postman, or any OpenAPI tool.
                </p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ asset('openapi.yaml') }}"
                        class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 transition">
                        Download openapi.yaml
                    </a>
                    <a href="{{ asset('openapi.yaml') }}" target="_blank" rel="noopener"
                        class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition">
                        Open in Browser
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">Quick Endpoints</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Base path: <span class="font-semibold">/api/v1</span></p>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
                    <div class="rounded-xl border border-gray-100 dark:border-gray-700 p-4">
                        <div class="text-xs font-black uppercase tracking-widest text-indigo-600">Rides</div>
                        <div class="mt-2 space-y-1">
                            <div>GET /rides</div>
                            <div>GET /rides/{ride}</div>
                            <div>GET /rides/active (public)</div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-100 dark:border-gray-700 p-4">
                        <div class="text-xs font-black uppercase tracking-widest text-indigo-600">Rooms</div>
                        <div class="mt-2 space-y-1">
                            <div>GET /rooms</div>
                            <div>GET /rooms/{room}</div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-100 dark:border-gray-700 p-4">
                        <div class="text-xs font-black uppercase tracking-widest text-indigo-600">Bookings</div>
                        <div class="mt-2 space-y-1">
                            <div>GET /bookings</div>
                            <div>POST /bookings</div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-100 dark:border-gray-700 p-4">
                        <div class="text-xs font-black uppercase tracking-widest text-indigo-600">Tickets</div>
                        <div class="mt-2 space-y-1">
                            <div>GET /tickets (public)</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                    Auth: Sanctum (cookie or token). Client users only see their own bookings.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
