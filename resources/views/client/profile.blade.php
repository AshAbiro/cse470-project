<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="bg-navy-600 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-white">My Profile</h2>
            </div>

            <!-- Floating Back Button -->
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

            <div class="p-8">
                @if (session('status') === 'profile-updated')
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition.duration.500ms
                        class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">Your profile has been updated.</span>
                    </div>
                @endif

                @if (session('status') === 'no-changes')
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition.duration.500ms
                        class="mb-6 bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Info:</strong>
                        <span class="block sm:inline">No changes made.</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('client.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Name Field -->
                    <div class="mb-6 pb-6 border-b border-gray-100"
                        x-data="{ editing: false, value: '{{ old('name', $user->name) }}' }">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                        <div class="flex items-center justify-between">
                            <div class="flex-grow">
                                <span x-show="!editing" class="text-gray-900 text-lg" x-text="value"></span>
                                <input x-show="editing" type="text" name="name" x-model="value"
                                    class="w-full shadow-sm border-gray-300 rounded-md focus:ring-navy-500 focus:border-navy-500"
                                    required>
                            </div>
                            <button type="button" @click="editing = !editing"
                                class="ml-4 text-navy-600 hover:text-navy-800 font-semibold text-sm">
                                <span x-show="!editing">Edit</span>
                                <span x-show="editing" class="text-red-500">Cancel</span>
                            </button>
                        </div>
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-6 pb-6 border-b border-gray-100"
                        x-data="{ editing: false, value: '{{ old('email', $user->email) }}' }">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                        <div class="flex items-center justify-between">
                            <div class="flex-grow">
                                <span x-show="!editing" class="text-gray-900 text-lg" x-text="value"></span>
                                <input x-show="editing" type="email" name="email" x-model="value"
                                    class="w-full shadow-sm border-gray-300 rounded-md focus:ring-navy-500 focus:border-navy-500"
                                    required>
                            </div>
                            <button type="button" @click="editing = !editing"
                                class="ml-4 text-navy-600 hover:text-navy-800 font-semibold text-sm">
                                <span x-show="!editing">Edit</span>
                                <span x-show="editing" class="text-red-500">Cancel</span>
                            </button>
                        </div>
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="mb-8" x-data="{ editing: false, value: '{{ old('phone', $user->phone) }}' }">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                        <div class="flex items-center justify-between">
                            <div class="flex-grow">
                                <span x-show="!editing" class="text-gray-900 text-lg"
                                    x-text="value || 'Not set'"></span>
                                <input x-show="editing" type="text" name="phone" x-model="value"
                                    class="w-full shadow-sm border-gray-300 rounded-md focus:ring-navy-500 focus:border-navy-500">
                            </div>
                            <button type="button" @click="editing = !editing"
                                class="ml-4 text-navy-600 hover:text-navy-800 font-semibold text-sm">
                                <span x-show="!editing">Edit</span>
                                <span x-show="editing" class="text-red-500">Cancel</span>
                            </button>
                        </div>
                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Unique ID Field -->
                    <!-- Unique ID Field -->
                    <div class="mb-6 pb-6 border-b border-gray-100">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Unique ID</label>
                        <div class="flex items-center justify-between">
                            <input type="text" value="{{ $user->unique_id }}" readonly disabled
                                class="w-full bg-gray-100 cursor-not-allowed shadow-sm border-gray-300 rounded-md focus:ring-navy-500 focus:border-navy-500 text-gray-500 font-mono">

                            <span
                                class="ml-4 px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-600 whitespace-nowrap">
                                Read-only
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">This ID is unique to your account and cannot be changed.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-navy-600 text-white font-bold py-3 px-6 rounded-full shadow-md hover:bg-navy-700 transition transform hover:scale-105">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>