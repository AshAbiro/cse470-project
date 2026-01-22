<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gift Entry Tickets - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="pt-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('admin.personal_bookings') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 mb-6">
                &larr; Back to Personal Bookings
            </a>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Gift Entry Tickets</h1>

                <form method="POST" action="{{ route('admin.bookings.gift_entry_ticket') }}" class="space-y-6"
                    x-data="ticketForm()">
                    @csrf

                    <!-- Client Selection -->
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name
                            of Client</label>
                        <select name="client_id" id="client_id" required x-model="selectedClient"
                            @change="updateClientUID()"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Select a client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" data-uid="{{ $client->id }}">{{ $client->name }}
                                    ({{ $client->email }})</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Client UID (Auto-filled) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client UID</label>
                        <input type="text" x-model="clientUID" disabled
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-400">
                    </div>

                    <!-- Ticket Selection -->
                    <div>
                        <label for="ticket_type_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ticket Type</label>
                        <select name="ticket_type_id" id="ticket_type_id" required x-model="selectedTicket"
                            @change="calculateTotal()"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Select a ticket type</option>
                            @foreach($ticketTypes as $ticket)
                                <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}">
                                    {{ $ticket->name }} - TK {{ number_format($ticket->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('ticket_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                        <input type="number" name="quantity" id="quantity" min="1" value="1" required x-model="quantity"
                            @input="calculateTotal()"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Price (Auto-calculated) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Price</label>
                        <div
                            class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 dark:bg-gray-600 dark:text-white">
                            <span class="text-lg font-bold">TK <span x-text="totalPrice.toFixed(2)">0.00</span></span>
                        </div>
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label for="expiry_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('expiry_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Confirm & Gift Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        function ticketForm() {
            return {
                selectedClient: '',
                clientUID: '',
                selectedTicket: '',
                quantity: 1,
                totalPrice: 0,

                updateClientUID() {
                    if (this.selectedClient) {
                        this.clientUID = 'CLIENT-' + this.selectedClient.toString().padStart(6, '0');
                    } else {
                        this.clientUID = '';
                    }
                },

                calculateTotal() {
                    if (this.selectedTicket && this.quantity > 0) {
                        const select = document.getElementById('ticket_type_id');
                        const price = parseFloat(select.options[select.selectedIndex].dataset.price);
                        this.totalPrice = price * this.quantity;
                    } else {
                        this.totalPrice = 0;
                    }
                }
            }
        }
    </script>
</body>

</html>