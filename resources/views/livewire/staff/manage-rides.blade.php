<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Sticky Back Button -->
    <div class="sticky top-20 z-10 mb-6">
        <a href="{{ route('staff.dashboard') }}"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-full shadow-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all active:scale-95 group">
            <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="bg-navy-800 p-8 text-white flex justify-between items-center whitespace-nowrap overflow-hidden">
            <div>
                <h2 class="text-3xl font-black tracking-tight mb-2">Park Rides Management</h2>
                <p class="text-navy-200 text-sm opacity-80 uppercase tracking-widest font-bold">Monitor and Report Ride
                    Status</p>
            </div>
            <button wire:click="openAddModal"
                class="bg-white text-navy-800 px-6 py-3 rounded-2xl font-black uppercase tracking-widest hover:bg-navy-50 transition-all active:scale-95 shadow-xl text-xs">
                Add New Ride
            </button>
        </div>

        <div class="p-8">
            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 font-bold rounded-r-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($rides as $ride)
                    <div
                        class="bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all group">
                        <div class="h-48 relative overflow-hidden">
                            <img src="{{ Str::startsWith($ride->image_path, 'http') ? $ride->image_path : ($ride->image_path ? asset('storage/' . $ride->image_path) : 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070') }}"
                                alt="{{ $ride->name }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute top-4 right-4">
                                @if($ride->is_active)
                                    <span
                                        class="bg-emerald-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">Active</span>
                                @else
                                    <span
                                        class="bg-red-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">Maintenance</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-navy-800 mb-2">{{ $ride->name }}</h3>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $ride->description }}</p>

                            <div class="flex items-center justify-between mt-4 border-t border-gray-200 pt-4">
                                <span class="text-lg font-black text-navy-600">BDT {{ number_format($ride->price) }}</span>
                                <button wire:click="openReportModal({{ $ride->id }}, '{{ $ride->name }}')"
                                    class="bg-navy-800 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-navy-900 transition-all active:scale-95 shadow-md">
                                    Report Damage
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Maintenance Report Modal -->
    @if($showReportModal)
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="closeReportModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-navy-800 px-6 py-4">
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight">Report Damage:
                            {{ $selectedRideName }}
                        </h3>
                    </div>
                    <form wire:submit.prevent="submitReport" class="p-6 space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Ride Name (Read Only) -->
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Ride</label>
                                <input type="text" value="{{ $selectedRideName }}" disabled
                                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-xl font-bold text-gray-500">
                            </div>

                            <!-- Time of Damage -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Time of
                                    Damage</label>
                                <input type="datetime-local" wire:model="damageTime"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold">
                                @error('damageTime') <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Reason -->
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Reason</label>
                                <select wire:model="reason"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold">
                                    <option value="technical">Technical Fault</option>
                                    <option value="staff_fault">Staff Fault</option>
                                    <option value="customer_fault">Customer Fault</option>
                                </select>
                                @error('reason') <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Repair Price -->
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Estimated
                                    Repair Price (BDT)</label>
                                <input type="number" wire:model="repairPrice"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                    placeholder="0.00">
                                @error('repairPrice') <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-end space-x-3">
                            <button type="button" wire:click="closeReportModal"
                                class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700 transition-colors">Cancel</button>
                            <button type="submit"
                                class="px-8 py-3 bg-navy-800 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg hover:bg-navy-900 transition-all active:scale-95">Submit
                                Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Ride Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="$set('showAddModal', false)"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                    <div class="bg-navy-800 px-6 py-4">
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight">Add New Park Ride</h3>
                    </div>
                    <form wire:submit.prevent="addRide" class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Ride
                                    Name</label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                    placeholder="e.g. Roller Coaster">
                                @error('name') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Price
                                    (BDT)</label>
                                <input type="number" wire:model="price"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                    placeholder="0.00">
                                @error('price') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Min Height -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Min
                                    Height (cm)</label>
                                <input type="number" wire:model="min_height"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                    placeholder="e.g. 120">
                                @error('min_height') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Thrill Level -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Thrill
                                    Level (1-5)</label>
                                <select wire:model="thrill_level"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold">
                                    <option value="1">1 - Gentle</option>
                                    <option value="2">2 - Easy</option>
                                    <option value="3">3 - Moderate</option>
                                    <option value="4">4 - High</option>
                                    <option value="5">5 - Extreme</option>
                                </select>
                                @error('thrill_level') <span
                                class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Description</label>
                                <textarea wire:model="description"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                    rows="3" placeholder="Describe the ride adventure..."></textarea>
                                @error('description') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-end space-x-3">
                            <button type="button" wire:click="$set('showAddModal', false)"
                                class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700 transition-colors">Cancel</button>
                            <button type="submit"
                                class="px-8 py-3 bg-navy-800 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg hover:bg-navy-900 transition-all active:scale-95">Add
                                Ride</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        .bg-navy-800 {
            background-color: #1a365d;
        }

        .text-navy-200 {
            color: #bee3f8;
        }

        .text-navy-600 {
            color: #2b6cb0;
        }
    </style>
</div>