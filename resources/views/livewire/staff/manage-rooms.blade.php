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
                <h2 class="text-3xl font-black tracking-tight mb-2">Resort Room Management</h2>
                <p class="text-navy-200 text-sm opacity-80 uppercase tracking-widest font-bold">Nawab Palace Inventory
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <button wire:click="openAddModal"
                    class="bg-white text-navy-800 px-6 py-3 rounded-2xl font-black uppercase tracking-widest hover:bg-navy-50 transition-all active:scale-95 shadow-xl text-xs">
                    Add New Room
                </button>
                <div class="bg-navy-700 px-4 py-2 rounded-2xl border border-navy-600">
                    <span class="text-xs font-bold text-navy-300 block uppercase tracking-widest">Total Rooms</span>
                    <span class="text-2xl font-black text-white">{{ $rooms->count() }}</span>
                </div>
            </div>
        </div>

        <div class="p-8">
            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 font-bold rounded-r-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                            <th class="px-6 py-4">Room Number</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Features</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($rooms as $room)
                            <tr class="group hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-6">
                                    <span class="text-lg font-black text-navy-800">#{{ $room->room_number }}</span>
                                    <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-widest">Floor
                                        {{ $room->floor }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm {{ $room->type == 'vip' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $room->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-6">
                                    <p class="text-sm text-gray-500 max-w-xs truncate">{{ $room->features }}</p>
                                </td>
                                <td class="px-6 py-6">
                                    @php
                                        $statusClasses = [
                                            'available' => 'bg-emerald-100 text-emerald-700',
                                            'maintenance' => 'bg-amber-100 text-amber-700',
                                            'out_of_order' => 'bg-red-100 text-red-700',
                                            'cleaned' => 'bg-emerald-500 text-white',
                                            'unclean' => 'bg-amber-500 text-white',
                                            'repair_required' => 'bg-rose-600 text-white'
                                        ][$room->status ?? 'available'];
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm {{ $statusClasses }}">
                                        {{ str_replace('_', ' ', $room->status ?? 'available') }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-right space-x-2">
                                    <button wire:click="openStatusModal({{ $room->id }})"
                                        class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                        title="Report Status">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="editRoom({{ $room->id }})"
                                        class="p-2 text-navy-600 hover:bg-navy-50 rounded-lg transition-colors"
                                        title="Edit Room">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $room->id }})"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Delete Room">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Form Overlay -->
                            @if($editingRoomId == $room->id)
                                <tr class="bg-navy-50">
                                    <td colspan="5" class="px-8 py-8 border-l-4 border-navy-800">
                                        <div
                                            class="max-w-4xl mx-auto bg-white p-8 rounded-3xl shadow-lg border border-navy-100">
                                            <h3 class="text-xl font-black text-navy-800 uppercase tracking-tight mb-6">Edit Room
                                                #{{ $room->room_number }}</h3>
                                            <form wire:submit.prevent="updateRoom"
                                                class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Room
                                                        Number</label>
                                                    <input type="number" wire:model="roomNumber"
                                                        class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold">
                                                    @error('roomNumber') <span
                                                        class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Status</label>
                                                    <select wire:model="status"
                                                        class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold">
                                                        <option value="available">Available</option>
                                                        <option value="maintenance">Maintenance</option>
                                                        <option value="out_of_order">Out of Order</option>
                                                    </select>
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Features</label>
                                                    <textarea wire:model="features"
                                                        class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                                        rows="3"></textarea>
                                                    @error('features') <span
                                                        class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div
                                                    class="md:col-span-2 flex justify-end space-x-3 pt-4 border-t border-gray-100">
                                                    <button type="button" wire:click="cancelEdit"
                                                        class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700 transition-colors">Cancel</button>
                                                    <button type="submit"
                                                        class="px-8 py-3 bg-navy-800 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg hover:bg-navy-900 transition-all active:scale-95">Update
                                                        Room</button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            <!-- Delete Confirmation Overlay -->
                            @if($confirmingDeletionId == $room->id)
                                <tr class="bg-red-50">
                                    <td colspan="5" class="px-8 py-8 border-l-4 border-red-500 text-center">
                                        <div class="max-w-md mx-auto">
                                            <h3 class="text-lg font-black text-red-800 uppercase tracking-tight mb-2">Delete
                                                Room #{{ $room->room_number }}?</h3>
                                            <p class="text-sm text-red-600 mb-6 font-bold">This action cannot be undone. All
                                                related bookings will also be handled by the database cascade.</p>
                                            <div class="flex justify-center space-x-4">
                                                <button wire:click="$set('confirmingDeletionId', null)"
                                                    class="px-6 py-2 text-sm font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700">Cancel</button>
                                                <button wire:click="deleteRoom({{ $room->id }})"
                                                    class="px-8 py-2 bg-red-600 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg hover:bg-red-700">Yes,
                                                    Delete</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Room Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="$set('showAddModal', false)"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                    <div class="bg-navy-800 px-6 py-4">
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight">Add New Resort Room</h3>
                    </div>
                    <form wire:submit.prevent="addRoom" class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Room Number -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Room
                                    Number</label>
                                <input type="number" wire:model="newRoomNumber"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                    placeholder="e.g. 301">
                                @error('newRoomNumber') <span
                                class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                            </div>

                            <!-- Floor -->
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Floor</label>
                                <input type="number" wire:model="newFloor"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                    placeholder="e.g. 3">
                                @error('newFloor') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Room
                                    Type</label>
                                <select wire:model="newType"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold">
                                    <option value="standard">Standard</option>
                                    <option value="vip">VIP</option>
                                </select>
                                @error('newType') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Price
                                    per 12h (BDT)</label>
                                <input type="number" wire:model="newPrice"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                    placeholder="0.00">
                                @error('newPrice') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Features -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Features
                                    (Comma separated)</label>
                                <textarea wire:model="newFeatures"
                                    class="w-full px-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-navy-500 focus:border-navy-500 font-bold"
                                    rows="3" placeholder="AC, Wi-Fi, Balcony, King Bed..."></textarea>
                                @error('newFeatures') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-end space-x-3">
                            <button type="button" wire:click="$set('showAddModal', false)"
                                class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700 transition-colors">Cancel</button>
                            <button type="submit"
                                class="px-8 py-3 bg-navy-800 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg hover:bg-navy-900 transition-all active:scale-95">Add
                                Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Reporting Modal -->
    @if($showStatusModal)
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="closeStatusModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                    <div class="bg-navy-800 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white uppercase tracking-tight">Report Status: Room #{{ $reportRoomNumber }}</h3>
                        <button wire:click="closeStatusModal" class="text-white opacity-50 hover:opacity-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="submitStatusReport" class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Select Current Status</label>
                            <div class="grid grid-cols-1 gap-3">
                                <label class="flex items-center p-4 bg-emerald-50 rounded-2xl border-2 border-emerald-100 cursor-pointer hover:border-emerald-300 transition-all group">
                                    <input type="radio" wire:model.live="reportStatus" value="cleaned" class="w-5 h-5 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <span class="ml-3 font-bold text-emerald-800 uppercase tracking-wide">Cleaned</span>
                                </label>
                                <label class="flex items-center p-4 bg-amber-50 rounded-2xl border-2 border-amber-100 cursor-pointer hover:border-amber-300 transition-all">
                                    <input type="radio" wire:model.live="reportStatus" value="unclean" class="w-5 h-5 text-amber-600 focus:ring-amber-500 border-gray-300">
                                    <span class="ml-3 font-bold text-amber-800 uppercase tracking-wide">Unclean Room</span>
                                </label>
                                <label class="flex items-center p-4 bg-rose-50 rounded-2xl border-2 border-rose-100 cursor-pointer hover:border-rose-300 transition-all">
                                    <input type="radio" wire:model.live="reportStatus" value="repair_required" class="w-5 h-5 text-rose-600 focus:ring-rose-500 border-gray-300">
                                    <span class="ml-3 font-bold text-rose-800 uppercase tracking-wide">Repair Required</span>
                                </label>
                            </div>
                            @error('reportStatus') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Damage Selection (Only if Repair Required is selected) -->
                        @if($reportStatus === 'repair_required')
                            <div class="animate-fadeIn">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Select Damaged Items (Features)</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($availableFeatures as $feature)
                                        <label class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-white transition-all">
                                            <input type="checkbox" wire:model="selectedDamagedItems" value="{{ $feature }}" class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-gray-300 rounded">
                                            <span class="ml-2 text-sm font-bold text-gray-700">{{ $feature }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('selectedDamagedItems') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100">
                            <button type="button" wire:click="closeStatusModal"
                                class="px-6 py-3 text-sm font-bold text-gray-500 uppercase tracking-widest hover:text-gray-700 transition-colors">Cancel</button>
                            <button type="submit"
                                class="px-8 py-3 bg-navy-800 text-white rounded-xl font-bold uppercase tracking-widest shadow-lg hover:bg-navy-900 transition-all active:scale-95">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
    </style>
        .bg-navy-800 {
            background-color: #1a365d;
        }

        .bg-navy-700 {
            background-color: #2c5282;
        }

        .text-navy-200 {
            color: #bee3f8;
        }

        .text-navy-300 {
            color: #ebf8ff;
        }

        .text-navy-600 {
            color: #2b6cb0;
        }

        .bg-navy-50 {
            background-color: #f0f4f8;
        }

        .border-navy-100 {
            border-color: #ebf1f7;
        }
    </style>
</div>