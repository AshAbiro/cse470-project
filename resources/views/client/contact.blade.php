<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Page Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-navy-900 mb-4 uppercase tracking-widest"
                    style="font-family: 'Playfair Display', serif;">
                    Contact Us
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">We are here to help ensuring your experience at
                    Amusement Park is magical. Meet our team below.</p>
                <div class="h-1 w-24 bg-gold-500 mx-auto mt-6"></div>
            </div>

            <!-- Admin Section -->
            <div class="mb-20">
                <h2 class="text-3xl font-bold text-navy-800 mb-10 text-center border-b border-gray-200 pb-4">
                    Administration</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach ($admins as $admin)
                        <div
                            class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:-translate-y-2 transition duration-300 border border-gray-100">
                            <!-- Admin Photo -->
                            <div class="h-64 bg-navy-100 relative overflow-hidden group">
                                @if($admin->profile_photo_path)
                                    <img src="{{ $admin->profile_photo_path }}" alt="{{ $admin->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <!-- Dynamic Avatar if no photo -->
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&background=001F3F&color=fff&size=512"
                                        alt="{{ $admin->name }}" class="w-full h-full object-cover">
                                @endif
                                <div
                                    class="absolute inset-0 bg-navy-900 bg-opacity-0 group-hover:bg-opacity-20 transition duration-300">
                                </div>
                            </div>

                            <div class="p-8 text-center">
                                <h3 class="text-xl font-bold text-navy-900 mb-1">{{ $admin->name }}</h3>
                                <p class="text-gold-600 font-semibold uppercase text-xs tracking-wider mb-4">
                                    {{ $admin->designation ?? 'Administrator' }}</p>

                                <div
                                    class="flex items-center justify-center space-x-2 text-gray-600 bg-gray-50 py-2 px-4 rounded-full mx-auto inline-flex">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm shadow-sm">{{ $admin->email }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Staff Section -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-navy-800 mb-10 text-center border-b border-gray-200 pb-4">Our Staff
                </h2>

                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-navy-50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-navy-700 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-navy-700 uppercase tracking-wider">
                                        Designation</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-navy-700 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-navy-700 uppercase tracking-wider">
                                        Phone</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($staffMembers as $staff)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-navy-100"
                                                        src="https://ui-avatars.com/api/?name={{ urlencode($staff->name) }}&background=random&color=fff"
                                                        alt="{{ $staff->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-navy-900">{{ $staff->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $staff->designation ?? 'Staff Member' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $staff->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $staff->phone ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Social Media Footer for Staff Section -->
                    <div class="bg-navy-900 py-8 text-center mt-0">
                        <p class="text-white mb-4 text-sm uppercase tracking-wide opacity-80">Connect with us on Social
                            Media</p>
                        <div class="flex justify-center space-x-8">
                            <!-- Facebook -->
                            <a href="https://facebook.com" target="_blank"
                                class="group transition transform hover:scale-110">
                                <div
                                    class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center shadow-lg group-hover:bg-blue-500 text-white">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </div>
                                <span
                                    class="text-white text-xs mt-2 block opacity-0 group-hover:opacity-100 transition">Facebook</span>
                            </a>

                            <!-- Instagram -->
                            <a href="https://instagram.com" target="_blank"
                                class="group transition transform hover:scale-110">
                                <div
                                    class="w-12 h-12 bg-gradient-to-tr from-yellow-400 via-red-500 to-purple-500 rounded-full flex items-center justify-center shadow-lg text-white">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069-3.204 0-3.584-.012-4.849-.069-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                </div>
                                <span
                                    class="text-white text-xs mt-2 block opacity-0 group-hover:opacity-100 transition">Instagram</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Back Button -->
            <div class="fixed bottom-6 right-6 z-[999]">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center justify-center bg-navy-600 hover:bg-navy-700 text-white font-bold rounded-full h-16 px-6 shadow-2xl transition transform hover:scale-110 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-navy-300 gap-2"
                    title="Back to Dashboard">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="text-lg">Back</span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>