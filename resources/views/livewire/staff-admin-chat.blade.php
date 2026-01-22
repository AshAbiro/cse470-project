<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto h-[calc(100vh-120px)] flex flex-col">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col h-full">
        <!-- Chat Header -->
        <div class="bg-navy-800 p-6 text-white flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <svg class="w-7 h-7 text-navy-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black tracking-tight">
                        {{ auth()->user()->role === 'admin' ? 'Admin Messenger' : 'Staff Messenger' }}
                    </h2>
                    <p class="text-navy-200 text-xs opacity-70 uppercase tracking-widest font-bold">Secure Communication
                        Channel</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ auth()->user()->role === 'admin' ? route('home') : route('staff.dashboard') }}"
                    class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition border border-white/20 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back</span>
                </a>
                <div class="flex items-center gap-2">
                    <span class="flex h-3 w-3 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-widest opacity-80">Live</span>
                </div>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="flex-grow overflow-y-auto p-6 space-y-4 bg-gray-50/50 custom-scrollbar" id="chat-messages"
            wire:poll.3s>
            @foreach($messages as $msg)
                <div wire:key="msg-{{ $msg->id }}"
                    class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }} animate-fade-in">
                    <div class="max-w-[75%]">
                        <div class="flex items-center gap-2 mb-1 px-1">
                            <span
                                class="text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest text-red-500 {{ $msg->user->role === 'admin' ? 'bg-amber-500 shadow-sm shadow-amber-200' : 'bg-navy-600 shadow-sm shadow-navy-200' }}">
                                {{ $msg->user->name }}
                                <span class="opacity-70 font-medium">({{ ucfirst($msg->user->role) }})</span>
                            </span>
                            <span
                                class="text-[9px] text-gray-400 font-bold ml-auto">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                        <div
                            class="relative {{ $msg->user_id === auth()->id() ? 'bg-navy-800 text-white rounded-2xl rounded-tr-none shadow-md' : 'bg-white text-gray-700 rounded-2xl rounded-tl-none shadow-sm border border-gray-100' }} p-4">
                            <p class="text-sm leading-relaxed">{{ $msg->message }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            <div id="anchor"></div>
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-white border-t border-gray-100 relative z-30 shrink-0" x-data="{ localMessage: '' }"
            wire:ignore>
            <form @submit.prevent="if(localMessage.trim()) { $wire.sendMessage(localMessage); localMessage = '' }"
                class="flex gap-4">
                <input type="text" x-model="localMessage" placeholder="Type your message here..."
                    @keydown.enter.prevent="if(localMessage.trim()) { $wire.sendMessage(localMessage); localMessage = '' }"
                    class="flex-grow px-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-navy-500 focus:border-navy-500 font-medium text-gray-700 placeholder-gray-400 transition-all outline-none">

                <button type="submit" :disabled="!localMessage.trim()"
                    class="bg-navy-800 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-navy-900 transition-all active:scale-95 shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-3">
                    <span>Send</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }
    </style>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const chatArea = document.getElementById('chat-messages');
            if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;

            window.addEventListener('scroll-chat', () => {
                setTimeout(() => {
                    if (chatArea) chatArea.scrollTop = chatArea.scrollHeight;
                }, 100);
            });
        });
    </script>
</div>