<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-500 leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ __('Park Maintenance Command Center') }}
            </h2>
            <a href="{{ route('home') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center gap-3 animate-fade-in"
                    role="alert">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Staff Reports Queue -->
            <div class="mb-8 space-y-6">
                <div class="flex items-center gap-3 px-2">
                    <div class="h-8 w-2 bg-indigo-600 rounded-full"></div>
                    <h2 class="text-2xl font-black text-gray-800 dark:text-gray-500 uppercase tracking-tight">Staff
                        Reports Queue</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Ride Damage Reports -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="bg-red-600 px-6 py-4 flex items-center justify-between">
                            <h3 class="font-bold text-white uppercase tracking-widest text-sm flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                Ride Damage Reports
                            </h3>
                            <span
                                class="bg-white/20 text-white text-[10px] font-black px-2 py-1 rounded-full">{{ $rideReports->count() }}
                                PENDING</span>
                        </div>
                        <div class="p-4 space-y-4 max-h-[400px] overflow-y-auto">
                            @forelse($rideReports as $report)
                                <div
                                    class="p-4 bg-red-50 dark:bg-red-900/10 rounded-2xl border border-red-100 dark:border-red-900/30 flex justify-between items-center group hover:shadow-md transition-all">
                                    <div>
                                        <h4 class="font-black text-red-900 dark:text-red-400 uppercase tracking-tight">
                                            {{ $report->ride->name }}
                                        </h4>
                                        <p class="text-xs text-red-700 dark:text-red-500 font-bold mt-1">Reason:
                                            {{ str_replace('_', ' ', $report->reason) }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase">
                                            {{ $report->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <button
                                        onclick="openResolveModal('repair_ride', '{{ $report->ride->name }}', '{{ $report->id }}')"
                                        class="bg-red-600 text-white text-[10px] font-black px-4 py-2 rounded-xl hover:bg-red-700 transition">RESOLVE</button>
                                </div>
                            @empty
                                <div class="py-8 text-center">
                                    <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">No pending ride
                                        reports</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Room Repair Reports -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="bg-amber-600 px-6 py-4 flex items-center justify-between">
                            <h3 class="font-bold text-white uppercase tracking-widest text-sm flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Room Repair Reports
                            </h3>
                            <span
                                class="bg-white/20 text-white text-[10px] font-black px-2 py-1 rounded-full">{{ $roomReports->count() }}
                                PENDING</span>
                        </div>
                        <div class="p-4 space-y-4 max-h-[400px] overflow-y-auto">
                            @forelse($roomReports as $report)
                                <div
                                    class="p-4 bg-amber-50 dark:bg-amber-900/10 rounded-2xl border border-amber-100 dark:border-amber-900/30 flex justify-between items-center group hover:shadow-md transition-all">
                                    <div>
                                        <h4 class="font-black text-amber-900 dark:text-amber-400 uppercase tracking-tight">
                                            Room #{{ $report->room->room_number }}</h4>
                                        <p class="text-xs text-amber-700 dark:text-amber-500 font-bold mt-1">Damaged:
                                            {{ $report->damaged_items }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase">
                                            {{ $report->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <button
                                        onclick="openResolveModal('repair_room', '{{ $report->room->room_number }}', '{{ $report->id }}')"
                                        class="bg-amber-600 text-white text-[10px] font-black px-4 py-2 rounded-xl hover:bg-amber-700 transition">RESOLVE</button>
                                </div>
                            @empty
                                <div class="py-8 text-center">
                                    <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">No pending room
                                        reports</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Food Availability Reports -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700 lg:col-span-2">
                        <div class="bg-emerald-600 px-6 py-4 flex items-center justify-between">
                            <h3 class="font-bold text-white uppercase tracking-widest text-sm flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                Food Availability Reports
                            </h3>
                            <span
                                class="bg-white/20 text-white text-[10px] font-black px-2 py-1 rounded-full">{{ $foodReports->count() }}
                                OUT OF STOCK</span>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[300px] overflow-y-auto">
                            @forelse($foodReports as $report)
                                <div
                                    class="p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 flex justify-between items-center group hover:shadow-md transition-all">
                                    <div>
                                        <h4
                                            class="font-black text-emerald-900 dark:text-emerald-400 uppercase tracking-tight">
                                            {{ $report->dish->name }}
                                        </h4>
                                        <p class="text-xs text-emerald-700 dark:text-emerald-500 font-bold mt-1">Status: Out
                                            of Stock</p>
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase">
                                            {{ $report->created_at->diffForHumans() }}</p>
                                    </div>
                                    <button
                                        onclick="openResolveModal('restore_food', '{{ $report->dish->name }}', '{{ $report->id }}')"
                                        class="bg-emerald-600 text-white text-[10px] font-black px-4 py-2 rounded-xl hover:bg-emerald-700 transition">RESTORE</button>
                                </div>
                            @empty
                                <div class="py-12 text-center col-span-2">
                                    <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">Everything is in
                                        stock</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Task History -->
            <div class="mb-12 space-y-6">
                <div class="flex items-center gap-3 px-2">
                    <div class="h-8 w-2 bg-emerald-600 rounded-full"></div>
                    <h2 class="text-2xl font-black text-gray-800 dark:text-gray-500 uppercase tracking-tight">Staff Task
                        History</h2>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-gray-700/30">
                                    <th class="p-6 text-[10px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest">
                                        Assigned Staff</th>
                                    <th class="p-6 text-[10px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest">Task
                                         Details</th>
                                    <th class="p-6 text-[10px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest">
                                         Status</th>
                                    <th class="p-6 text-[10px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest">Staff
                                         Response</th>
                                    <th class="p-6 text-[10px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest">
                                         Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($staffTasks as $task)
                                                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                                                <td class="p-6">
                                                                    <div class="flex items-center gap-3">
                                                                        <div
                                                                            class="h-10 w-10 rounded-full bg-navy-100 flex items-center justify-center font-black text-navy-600 uppercase">
                                                                            {{ substr($task->staff->name, 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <p class="font-bold text-gray-900 dark:text-white">
                                                                                {{ $task->staff->name }}</p>
                                                                            <p class="text-[10px] text-gray-400 font-bold uppercase">
                                                                                {{ $task->staff->designation }}</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="p-6">
                                                                    <h4
                                                                        class="font-black text-gray-800 dark:text-gray-200 uppercase tracking-tight text-sm">
                                                                        {{ $task->item_name }}</h4>
                                                                    <p class="text-xs text-gray-500 font-medium">{{ $task->description }}</p>
                                                                </td>
                                                                <td class="p-6">
                                                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                                                            {{ $task->status === 'completed' ? 'bg-emerald-100 text-emerald-700' :
                                    ($task->status === 'in_progress' ? 'bg-amber-100 text-amber-700' :
                                        'bg-navy-100 text-navy-700') }}">
                                                                        {{ str_replace('_', ' ', $task->status) }}
                                                                    </span>
                                                                    @if($task->completed_at)
                                                                        <p class="text-[9px] text-gray-400 font-bold mt-1 tracking-tighter">
                                                                            {{ $task->completed_at->diffForHumans() }}</p>
                                                                    @endif
                                                                </td>
                                                                <td class="p-6 max-w-xs">
                                                                    @if($task->staff_response)
                                                                        <div
                                                                            class="bg-gray-50 dark:bg-gray-900/40 p-3 rounded-xl border border-gray-100 dark:border-gray-800 italic text-xs text-gray-600 dark:text-gray-400">
                                                                            "{{ $task->staff_response }}"
                                                                        </div>
                                                                    @else
                                                                        <span class="text-gray-300 dark:text-gray-600 italic text-xs">Waiting for
                                                                            response...</span>
                                                                    @endif
                                                                </td>
                                                                <td class="p-6">
                                                                    <form action="{{ route('admin.delete_staff_task', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this task from history?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Delete Task">
                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                            </svg>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="p-12 text-center text-gray-400 font-bold uppercase text-xs tracking-widest">
                                            No recent staff tasks</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 uppercase tracking-wider">
                        Maintenance Actions</h1>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700/50">
                                    <th
                                        class="p-4 border-b border-gray-200 dark:border-gray-600 text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                        Task Category</th>
                                    <th
                                        class="p-4 border-b border-gray-200 dark:border-gray-600 text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                        Description</th>
                                    <th
                                        class="p-4 border-b border-gray-200 dark:border-gray-600 text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest text-center">
                                        Execute Command</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Repair Ride Row -->
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="p-4 border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span class="font-bold text-gray-800 dark:text-gray-200">Repair Ride</span>
                                        </div>
                                    </td>
                                    <td
                                        class="p-4 border-b border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-400">
                                        Assign a specific ride to a staff member for immediate maintenance or repair.
                                    </td>
                                    <td class="p-4 border-b border-gray-100 dark:border-gray-700 text-center">
                                        <button onclick="toggleModal('repairRideModal')"
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full transition transform hover:scale-105 shadow-md uppercase text-xs tracking-widest">
                                            Repair Ride
                                        </button>
                                    </td>
                                </tr>

                                <!-- Clean Park Row -->
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="p-4 border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span class="font-bold text-gray-800 dark:text-gray-200">Clean Park</span>
                                        </div>
                                    </td>
                                    <td
                                        class="p-4 border-b border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-400">
                                        Issue a general cleaning order for the park grounds to a selected staff member.
                                    </td>
                                    <td class="p-4 border-b border-gray-100 dark:border-gray-700 text-center">
                                        <button onclick="toggleModal('cleanParkModal')"
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-full transition transform hover:scale-105 shadow-md uppercase text-xs tracking-widest">
                                            Clean Park
                                        </button>
                                    </td>
                                </tr>

                                <!-- Clean Resort Room Row -->
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="p-4 border-b border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                                    </path>
                                                </svg>
                                            </div>
                                            <span class="font-bold text-gray-800 dark:text-gray-200">Clean Resort
                                                Room</span>
                                        </div>
                                    </td>
                                    <td
                                        class="p-4 border-b border-gray-100 dark:border-gray-700 text-gray-600 dark:text-gray-400">
                                        Order a specific resort room to be cleaned by a selected staff member.
                                    </td>
                                    <td class="p-4 border-b border-gray-100 dark:border-gray-700 text-center">
                                        <button onclick="toggleModal('cleanRoomModal')"
                                            class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-6 rounded-full transition transform hover:scale-105 shadow-md uppercase text-xs tracking-widest">
                                            Clean Room
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->

    <!-- Repair Ride Modal -->
    <div id="repairRideModal" class="fixed inset-0 z-[3000] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm"
                onclick="toggleModal('repairRideModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 flex justify-between items-center bg-red-600">
                    <h3 class="text-xl font-bold text-white uppercase tracking-widest">Assign Ride Repair</h3>
                    <button onclick="toggleModal('repairRideModal')" class="text-white hover:text-gray-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.send_maintenance_command') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="repair_ride">

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">Select
                                    Ride</label>
                                <select name="item_id" required
                                    class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-red-500 focus:border-red-500 dark:text-white">
                                    @foreach($rides as $ride)
                                        <option value="{{ $ride->name }}">{{ $ride->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="report_id" id="ride_report_id">

                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">Assign
                                    to Staff</label>
                                <select name="staff_id" required
                                    class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-red-500 focus:border-red-500 dark:text-white">
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->designation }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-2xl transition transform hover:-translate-y-1 shadow-lg shadow-red-100 uppercase tracking-widest">
                                Send Repair Command
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean Park Modal -->
    <div id="cleanParkModal" class="fixed inset-0 z-[3000] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm"
                onclick="toggleModal('cleanParkModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 flex justify-between items-center bg-green-600">
                    <h3 class="text-xl font-bold text-white uppercase tracking-widest">Assign Park Cleaning</h3>
                    <button onclick="toggleModal('cleanParkModal')" class="text-white hover:text-gray-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.send_maintenance_command') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="clean_park">

                        <div class="space-y-6">
                            <div
                                class="p-4 bg-green-50 dark:bg-green-900/10 rounded-xl border border-green-100 dark:border-green-900/30">
                                <p class="text-green-700 dark:text-green-400 text-sm font-medium">This will issue a
                                    command to clean all shared park areas, walkways, and seating zones.</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">Assign
                                    to Staff</label>
                                <select name="staff_id" required
                                    class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-green-500 focus:border-green-500 dark:text-white">
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->designation }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-2xl transition transform hover:-translate-y-1 shadow-lg shadow-green-100 uppercase tracking-widest">
                                Send Cleaning Command
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Clean Room Modal -->
    <div id="cleanRoomModal" class="fixed inset-0 z-[3000] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm"
                onclick="toggleModal('cleanRoomModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 flex justify-between items-center bg-amber-600">
                    <h3 class="text-xl font-bold text-white uppercase tracking-widest">Assign Room Cleaning</h3>
                    <button onclick="toggleModal('cleanRoomModal')" class="text-white hover:text-gray-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.send_maintenance_command') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="clean_room">

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">Select
                                    Resort Room</label>
                                <select name="item_id" required
                                    class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-amber-500 focus:border-amber-500 dark:text-white">
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->room_number }}">Room {{ $room->room_number }} (Floor
                                            {{ $room->floor }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="report_id" id="room_report_id">

                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">Assign
                                    to Staff</label>
                                <select name="staff_id" required
                                    class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-amber-500 focus:border-amber-500 dark:text-white">
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->designation }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-4 rounded-2xl transition transform hover:-translate-y-1 shadow-lg shadow-amber-100 uppercase tracking-widest">
                                Send Room Cleaning Command
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Food Modal -->
    <div id="restoreFoodModal" class="fixed inset-0 z-[3000] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm"
                onclick="toggleModal('restoreFoodModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 flex justify-between items-center bg-emerald-600">
                    <h3 class="text-xl font-bold text-white uppercase tracking-widest">Assign Food Restock</h3>
                    <button onclick="toggleModal('restoreFoodModal')" class="text-white hover:text-gray-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.send_maintenance_command') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="restore_food">

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">Select
                                    Food Item</label>
                                <select name="item_id" required
                                    class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 dark:text-white">
                                    @foreach($dishes as $dish)
                                        <option value="{{ $dish->name }}">{{ $dish->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="report_id" id="food_report_id">

                            <div>
                                <label
                                    class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">Assign
                                    to Staff</label>
                                <select name="staff_id" required
                                    class="w-full bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 dark:text-white">
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->designation }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl transition transform hover:-translate-y-1 shadow-lg shadow-emerald-100 uppercase tracking-widest">
                                Send Restock Command
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === "Escape") {
                const modals = ['repairRideModal', 'cleanParkModal', 'cleanRoomModal'];
                modals.forEach(id => {
                    const modal = document.getElementById(id);
                    if (!modal.classList.contains('hidden')) {
                        toggleModal(id);
                    }
                });
            }
        });
        // Resolve Modal Logic
        function openResolveModal(type, itemId, reportId) {
            if (type === 'repair_ride') {
                const modal = document.getElementById('repairRideModal');
                const select = modal.querySelector('select[name="item_id"]');
                const reportInput = document.getElementById('ride_report_id');

                select.value = itemId;
                reportInput.value = reportId;
                toggleModal('repairRideModal');
            } else if (type === 'repair_room') {
                const modal = document.getElementById('cleanRoomModal');
                const select = modal.querySelector('select[name="item_id"]');
                const typeInput = modal.querySelector('input[name="type"]');
                const reportInput = document.getElementById('room_report_id');
                const title = modal.querySelector('h3');
                const submitBtn = modal.querySelector('button[type="submit"]');

                select.value = itemId;
                reportInput.value = reportId;
                typeInput.value = 'repair_room'; // Switch from clean to repair
                title.innerText = 'Assign Room Repair';
                submitBtn.innerText = 'Send Repair Command';
                submitBtn.classList.remove('bg-amber-600', 'hover:bg-amber-700');
                submitBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');

                toggleModal('cleanRoomModal');
            } else if (type === 'restore_food') {
                const modal = document.getElementById('restoreFoodModal');
                const select = modal.querySelector('select[name="item_id"]');
                const reportInput = document.getElementById('food_report_id');

                select.value = itemId;
                reportInput.value = reportId;
                toggleModal('restoreFoodModal');
            }
        }
    </script>
</x-app-layout>