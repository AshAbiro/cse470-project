<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Livewire\StaffAdminChat;

use App\Http\Controllers\ClientProfileController;
use App\Http\Controllers\BookRidesController;
use App\Http\Controllers\HealthController;

Route::get('/health', [HealthController::class, 'check'])->name('health');

Route::get('/', function () {
    $rides = \App\Models\Ride::where('is_active', true)->inRandomOrder()->take(3)->get();
    $totalRides = \App\Models\Ride::where('is_active', true)->count();

    // Create a pool of all possible "tickets" (TicketType and individual Rides)
    $entryTicket = \App\Models\TicketType::where('name', 'Park Entry Ticket')->first();
    $otherTicketTypes = \App\Models\TicketType::where('name', '!=', 'Park Entry Ticket')->get();
    $rideTickets = \App\Models\Ride::where('is_active', true)->get();

    $pool = $otherTicketTypes->concat($rideTickets)->shuffle();
    $tickets = collect();
    if ($entryTicket) {
        $tickets->push($entryTicket);
    }
    $tickets = $tickets->concat($pool->take($entryTicket ? 2 : 3));

    $allQuotes = \App\Models\Quote::where('is_active', true)->get();
    $dailyQuote = $allQuotes->count() > 0 ? $allQuotes->get(date('z') % $allQuotes->count()) : null;

    return view('welcome', compact('rides', 'tickets', 'totalRides', 'dailyQuote'));
});

Route::get('/admin/login', function () {
    return view('auth.admin-login');
})->name('admin.login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/profile', [ClientProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ClientProfileController::class, 'update'])->name('profile.update');

        Route::get('/book-rides', \App\Livewire\BookRides::class)->name('book-rides');
        Route::get('/nawab-palace', \App\Livewire\NawabPalace::class)->name('nawab-palace');
        Route::view('/map', 'client.map')->name('map');
        Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact');
        Route::get('/booking-history', \App\Livewire\BookingHistory::class)->name('booking-history');
        Route::view('/parking', 'client.parking')->name('parking');

        // Rating Routes
        Route::get('/rate-staff', [\App\Http\Controllers\ClientController::class, 'rate_staff'])->name('rate-staff');
        Route::post('/rate-staff', [\App\Http\Controllers\ClientController::class, 'submit_staff_rating'])->name('submit-staff-rating');
        Route::get('/rate-rides', [\App\Http\Controllers\ClientController::class, 'rate_rides'])->name('rate-rides');
        Route::post('/rate-rides', [\App\Http\Controllers\ClientController::class, 'submit_ride_rating'])->name('submit-ride-rating');
        Route::get('/rate-rooms', [\App\Http\Controllers\ClientController::class, 'rate_rooms'])->name('rate-rooms');
        Route::post('/rate-rooms', [\App\Http\Controllers\ClientController::class, 'submit_room_rating'])->name('submit-room-rating');
    });
});

Route::get('/home', [AdminController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/my-profile', [AdminController::class, 'my_profile'])->name('admin.my_profile');
    Route::post('/admin/my-profile', [AdminController::class, 'update_profile'])->name('admin.update_profile');
    Route::get('/admin/manage-rides', [\App\Http\Controllers\RideController::class, 'index'])->name('admin.manage_rides');
    Route::post('/admin/rides', [\App\Http\Controllers\RideController::class, 'store'])->name('admin.rides.store');
    Route::put('/admin/rides/{ride}', [\App\Http\Controllers\RideController::class, 'update'])->name('admin.rides.update');
    Route::delete('/admin/rides/{ride}', [\App\Http\Controllers\RideController::class, 'destroy'])->name('admin.rides.destroy');
    Route::get('/admin/nawab-palace', [\App\Http\Controllers\RoomController::class, 'index'])->name('admin.nawab_palace');
    Route::post('/admin/rooms', [\App\Http\Controllers\RoomController::class, 'store'])->name('admin.rooms.store');
    Route::put('/admin/rooms/{room}', [\App\Http\Controllers\RoomController::class, 'update'])->name('admin.rooms.update');
    Route::delete('/admin/rooms/{room}', [\App\Http\Controllers\RoomController::class, 'destroy'])->name('admin.rooms.destroy');
    Route::get('/admin/manage-tickets', [AdminController::class, 'manage_tickets'])->name('admin.manage_tickets');
    Route::get('/admin/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
    Route::get('/admin/personal-bookings', \App\Livewire\AdminPersonalBooking::class)->name('admin.personal_bookings');
    Route::get('/admin/bookings/entry-tickets', [AdminController::class, 'entry_tickets'])->name('admin.bookings.entry_tickets');
    Route::post('/admin/bookings/gift-entry-ticket', [AdminController::class, 'gift_entry_ticket'])->name('admin.bookings.gift_entry_ticket');
    Route::get('/admin/bookings/water-world', [AdminController::class, 'water_world'])->name('admin.bookings.water_world');
    Route::get('/admin/bookings/nawab-palace', [AdminController::class, 'nawab_palace_bookings'])->name('admin.bookings.nawab_palace');
    Route::get('/admin/bookings/ride-tickets', [AdminController::class, 'ride_tickets'])->name('admin.bookings.ride_tickets');
    Route::get('/admin/booking-requests', [AdminController::class, 'booking_requests'])->name('admin.booking_requests');
    Route::post('/admin/accept-booking/{type}/{id}', [AdminController::class, 'accept_booking'])->name('admin.accept_booking');
    Route::post('/admin/reject-booking/{type}/{id}', [AdminController::class, 'reject_booking'])->name('admin.reject_booking');
    Route::get('/admin/map', [AdminController::class, 'map'])->name('admin.map');
    Route::get('/admin/park-maintenance', [AdminController::class, 'park_maintenance'])->name('admin.park_maintenance');
    Route::post('/admin/send-maintenance-command', [AdminController::class, 'send_maintenance_command'])->name('admin.send_maintenance_command');
    Route::get('/admin/analytics/data', [AdminController::class, 'get_stats'])->name('admin.analytics.data');
    Route::delete('/admin/staff-tasks/{id}', [AdminController::class, 'delete_staff_task'])->name('admin.delete_staff_task');
    Route::delete('/admin/my-booking/{type}/{id}', [AdminController::class, 'delete_my_booking'])->name('admin.delete_my_booking');
    Route::post('/admin/my-bookings/bulk-delete', [AdminController::class, 'bulk_delete_my_bookings'])->name('admin.bulk_delete_my_bookings');
    Route::get('/manage-quotes', \App\Livewire\ManageQuotes::class)->name('manage-quotes');
    Route::get('/admin/chat', \App\Livewire\StaffAdminChat::class)->name('admin.chat');
    Route::get('/admin/view-ratings', [AdminController::class, 'view_ratings'])->name('admin.view_ratings');
});

// Staff Routes
Route::get('/staff/login', function () {
    return view('staff.login');
})->name('staff.login');

Route::middleware(['auth'])->group(function () {
    Route::get('/staff/dashboard', [\App\Http\Controllers\StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::get('/staff/requests', [\App\Http\Controllers\StaffController::class, 'requests'])->name('staff.requests');
    Route::post('/staff/accept-booking/{type}/{id}', [\App\Http\Controllers\StaffController::class, 'accept_booking'])->name('staff.accept_booking');
    Route::post('/staff/reject-booking/{type}/{id}', [\App\Http\Controllers\StaffController::class, 'reject_booking'])->name('staff.reject_booking');

    // New Staff Features
    Route::get('/staff/book-for-guest', \App\Livewire\Staff\BookForGuest::class)->name('staff.book-for-guest');
    Route::get('/staff/food', \App\Livewire\Staff\ManageFood::class)->name('staff.food');
    Route::get('/staff/rooms', \App\Livewire\Staff\ManageRooms::class)->name('staff.rooms');
    Route::get('/staff/rides', \App\Livewire\Staff\ManageRides::class)->name('staff.rides');
    Route::get('/staff/tasks', \App\Livewire\Staff\MyTasks::class)->name('staff.tasks');
    Route::get('/staff/chat', \App\Livewire\StaffAdminChat::class)->name('staff.chat');
    Route::get('/staff/my-ratings', [\App\Http\Controllers\StaffController::class, 'my_ratings'])->name('staff.my_ratings');
});

// Fallback route - handle any undefined routes
Route::fallback(function () {
    return view('welcome');
});