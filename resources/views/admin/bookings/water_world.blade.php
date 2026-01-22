<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Water World - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="pt-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('admin.personal_bookings') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 mb-6">
                &larr; Back to Personal Bookings
            </a>

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Water World Bookings</h1>
                <p class="text-gray-600 dark:text-gray-400">Coming soon...</p>
            </div>
        </div>
    </div>
</body>

</html>