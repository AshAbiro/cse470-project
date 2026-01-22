<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Audit Logs') }}
            </h2>
            <a href="{{ route('home') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 transition shadow-md">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 border border-gray-100 dark:border-gray-700">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">Action</label>
                        <input name="action" value="{{ request('action') }}" placeholder="booking.accepted"
                            class="mt-1 w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">User</label>
                        <input name="user" value="{{ request('user') }}" placeholder="name or email"
                            class="mt-1 w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">From</label>
                        <input type="date" name="from" value="{{ request('from') }}"
                            class="mt-1 w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-gray-500">To</label>
                        <input type="date" name="to" value="{{ request('to') }}"
                            class="mt-1 w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200" />
                    </div>
                    <div class="md:col-span-4 flex items-center gap-3">
                        <button type="submit"
                            class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 transition">
                            Filter
                        </button>
                        <a href="{{ route('admin.audit_logs') }}"
                            class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs uppercase tracking-widest hover:bg-gray-200 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/40 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest">When</th>
                                <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest">User</th>
                                <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest">Action</th>
                                <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest">Entity</th>
                                <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest">IP</th>
                                <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest">Metadata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-800 dark:text-gray-200">
                                            {{ $log->created_at->format('M d, Y H:i') }}
                                        </div>
                                        <div class="text-[11px] text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $log->user?->name ?? 'System' }}
                                        </div>
                                        <div class="text-[11px] text-gray-400">{{ $log->user?->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase tracking-widest">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-xs text-gray-600 dark:text-gray-300 font-semibold">
                                            {{ $log->entity_type ?? '-' }}
                                        </div>
                                        <div class="text-[11px] text-gray-400">ID: {{ $log->entity_id ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $log->ip_address ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($log->metadata)
                                            <pre class="text-[11px] text-gray-500 dark:text-gray-300 whitespace-pre-wrap">{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            <span class="text-gray-300 dark:text-gray-600 italic text-xs">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">
                                        No audit logs found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
