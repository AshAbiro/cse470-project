<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
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

    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-navy-800 p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tight">Assigned Tasks</h1>
                <p class="text-navy-200 text-sm font-bold mt-1 uppercase tracking-widest">Manage your maintenance
                    assignments</p>
            </div>
            <div class="bg-white/10 px-4 py-2 rounded-2xl backdrop-blur-md border border-white/20">
                <span class="text-2xl font-black">{{ $tasks->where('status', '!=', 'completed')->count() }}</span>
                <span class="text-[10px] font-bold uppercase block opacity-70">Active Tasks</span>
            </div>
        </div>

        <div class="p-8">
            @if (session()->has('success'))
                <div
                    class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 font-bold rounded-xl flex items-center shadow-sm animate-fade-in-down">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-6">
                @forelse($tasks as $task)
                            <div
                                class="group bg-gray-50/50 hover:bg-white rounded-3xl p-6 border-2 {{ $task->status === 'completed' ? 'border-emerald-100' : ($task->status === 'in_progress' ? 'border-amber-100' : 'border-gray-100') }} transition-all hover:shadow-xl hover:-translate-y-1 relative">
                                <div class="flex flex-col md:flex-row md::items-center justify-between gap-6">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                                    {{ $task->status === 'completed' ? 'bg-emerald-100 text-emerald-700' :
                    ($task->status === 'in_progress' ? 'bg-amber-100 text-amber-700' :
                        'bg-navy-100 text-navy-700') }}">
                                                {{ str_replace('_', ' ', $task->status) }}
                                            </span>
                                            <span
                                                class="text-xs text-gray-400 font-bold uppercase tracking-tighter">{{ $task->created_at->format('M d, H:i') }}</span>
                                        </div>
                                        <h3 class="text-xl font-black text-navy-900 mb-1 uppercase tracking-tight">
                                            {{ $task->item_name }}</h3>
                                        <p class="text-gray-600 text-sm font-medium leading-relaxed">{{ $task->description }}</p>

                                        @if($task->staff_response)
                                            <div
                                                class="mt-4 p-4 bg-white rounded-2xl border border-gray-100 italic text-sm text-gray-500 flex items-start gap-2 shadow-sm">
                                                <svg class="w-4 h-4 text-navy-400 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                                    </path>
                                                </svg>
                                                <span>"{{ $task->staff_response }}"</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($task->status === 'pending')
                                            <button wire:click="updateStatus({{ $task->id }}, 'in_progress')"
                                                class="px-6 py-3 bg-amber-500 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-amber-600 transition shadow-lg shadow-amber-200 active:scale-95">
                                                Start Work
                                            </button>
                                        @endif

                                        @if($task->status !== 'completed')
                                            <button wire:click="openCompleteModal({{ $task->id }})"
                                                class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 active:scale-95">
                                                Mark Done
                                            </button>
                                        @endif

                                        @if($task->status === 'completed')
                                            <div
                                                class="flex items-center text-emerald-600 font-black text-xs uppercase tracking-widest gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Completed
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                @empty
                    <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                        <div class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-gray-400 font-black uppercase tracking-widest">No assigned tasks yet</h3>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Complete Task Modal -->
    <div x-data="{ open: false }" x-on:open-complete-modal.window="open = true"
        x-on:close-complete-modal.window="open = false" x-show="open" class="fixed inset-0 z-[100] overflow-y-auto"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-navy-900/60 backdrop-blur-sm" @click="open = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100">
                <div class="px-8 py-6 bg-navy-800 text-white flex justify-between items-center">
                    <h3 class="text-xl font-black uppercase tracking-tight">Complete Task</h3>
                    <button @click="open = false" class="text-white/50 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-8">
                    <p class="text-gray-500 font-bold text-xs uppercase tracking-widest mb-4">Provide a response to the
                        admin</p>
                    <textarea wire:model="staffResponse"
                        class="w-full bg-gray-50 border-gray-200 rounded-3xl p-6 focus:ring-navy-500 focus:border-navy-500 font-medium text-sm transition-all"
                        rows="4" placeholder="Describe what was done..."></textarea>
                    @error('staffResponse') <span
                    class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror

                    <div class="flex gap-4 mt-8">
                        <button @click="open = false"
                            class="flex-1 px-6 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition">Cancel</button>
                        <button wire:click="completeTask"
                            class="flex-2 px-10 py-4 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition active:scale-95">Send
                            Response & Finish</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-navy-800 {
            background-color: #1a365d;
        }

        .text-navy-900 {
            color: #1a365d;
        }

        .text-navy-200 {
            color: #bee3f8;
        }

        .bg-navy-900\/60 {
            background-color: rgba(26, 32, 44, 0.6);
        }
    </style>
</div>