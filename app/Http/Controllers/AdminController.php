<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Services\AuditLogger;
use App\Services\AnalyticsService;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            if ($role == 'client') {
                return redirect()->route('dashboard');
            } else if ($role == 'admin') {
                return view('admin.dashboard');
            } else if ($role == 'staff') {
                return redirect()->route('staff.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }
        return redirect()->route('login');
    }

    public function my_profile()
    {
        return view('admin.my_profile');
    }

    public function view_ratings()
    {
        $staffRatings = \App\Models\StaffRating::with(['user', 'staff'])
            ->orderBy('created_at', 'desc')
            ->get();

        $rideRatings = \App\Models\RideRating::with(['user', 'ride'])
            ->orderBy('created_at', 'desc')
            ->get();

        $roomRatings = \App\Models\RoomRating::with(['user', 'room'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.view_ratings', compact('staffRatings', 'rideRatings', 'roomRatings'));
    }
    public function manage_rides()
    {
        return view('admin.manage_rides');
    }
    public function nawab_palace()
    {
        return view('admin.nawab_palace');
    }
    public function manage_tickets()
    {
        return view('admin.manage_tickets');
    }
    public function analytics()
    {
        $summary = Cache::remember('analytics.summary', 60, function () {
            $service = new AnalyticsService();
            return $service->getDashboardStats();
        });

        return view('admin.analytics', compact('summary'));
    }



    public function entry_tickets()
    {
        $clients = User::where('role', 'client')->get();
        $ticketTypes = \App\Models\TicketType::all();
        return view('admin.bookings.entry_tickets', compact('clients', 'ticketTypes'));
    }

    public function water_world()
    {
        return view('admin.bookings.water_world');
    }
    public function nawab_palace_bookings()
    {
        return view('admin.bookings.nawab_palace');
    }
    public function ride_tickets()
    {
        return view('admin.bookings.ride_tickets');
    }

    public function booking_requests()
    {
        $rideRequests = \App\Models\Booking::with(['user', 'ticketType', 'ride'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->booking_group_id ?? 'SINGLE-' . $item->id;
            });

        $roomRequests = \App\Models\RoomBooking::with(['user', 'room'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $parkingRequests = \App\Models\ParkingBooking::with(['user', 'slot'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch admin's own personal bookings that have been processed by staff
        $myRideBookings = \App\Models\Booking::with(['user', 'ticketType', 'ride'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['accepted', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->booking_group_id ?? 'SINGLE-' . $item->id;
            });

        $myRoomBookings = \App\Models\RoomBooking::with(['user', 'room'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['accepted', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        $myParkingBookings = \App\Models\ParkingBooking::with(['user', 'slot'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['accepted', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark admin notifications as read
        \App\Models\Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.requests', compact('rideRequests', 'roomRequests', 'parkingRequests', 'myRideBookings', 'myRoomBookings', 'myParkingBookings'));
    }

    public function delete_my_booking(Request $request, $type, $id)
    {
        if ($type == 'ride') {
            // Delete all bookings in the group
            $bookings = \App\Models\Booking::where('booking_group_id', $id)
                ->orWhere('id', str_replace('SINGLE-', '', $id))
                ->where('user_id', Auth::id())
                ->get();

            foreach ($bookings as $booking) {
                $booking->delete();
            }
        } elseif ($type == 'room') {
            $booking = \App\Models\RoomBooking::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();
            if ($booking) {
                $booking->delete();
            }
        } elseif ($type == 'parking') {
            $booking = \App\Models\ParkingBooking::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();
            if ($booking) {
                $booking->delete();
            }
        }

        AuditLogger::log('booking.deleted_by_user', [
            'type' => $type,
            'booking_group_id' => $id,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Booking deleted successfully!');
    }

    public function bulk_delete_my_bookings(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:ride,room,parking',
            'ids' => 'required|array',
            'ids.*' => 'required|string',
        ]);

        $ids = $request->ids;
        $type = $request->type;
        $userId = Auth::id();

        if ($type == 'ride') {
            foreach ($ids as $id) {
                // Determine if it's a group or single ID
                $bookings = \App\Models\Booking::where('booking_group_id', $id)
                    ->orWhere('id', str_replace('SINGLE-', '', $id))
                    ->where('user_id', $userId)
                    ->get();

                foreach ($bookings as $booking) {
                    $booking->delete();
                }
            }
        } elseif ($type == 'room') {
            \App\Models\RoomBooking::whereIn('id', $ids)
                ->where('user_id', $userId)
                ->delete();
        } elseif ($type == 'parking') {
            \App\Models\ParkingBooking::whereIn('id', $ids)
                ->where('user_id', $userId)
                ->delete();
        }

        AuditLogger::log('booking.bulk_deleted_by_user', [
            'type' => $type,
            'count' => count($ids),
            'user_id' => $userId,
        ]);

        return back()->with('success', 'Selected bookings deleted successfully!');
    }

    public function accept_booking(Request $request, $type, $id)
    {
        if ($type == 'ride') {
            // Check if $id is a group ID or a single ID
            $bookings = \App\Models\Booking::where('booking_group_id', $id)
                ->orWhere('id', str_replace('SINGLE-', '', $id))
                ->get();

            if ($bookings->isEmpty()) {
                return back()->with('error', 'Booking not found.');
            }

            foreach ($bookings as $booking) {
                $booking->update(['status' => 'accepted']);
            }

            $firstBooking = $bookings->first();
            $userId = $firstBooking->user_id;

            if ($bookings->count() > 1) {
                $msg = "Your ride cluster booking (" . $bookings->count() . " items) has been accepted!";
            } else {
                $name = $firstBooking->ride ? $firstBooking->ride->name : ($firstBooking->ticketType ? $firstBooking->ticketType->name : 'Ticket');
                $msg = "Your ride booking for " . $name . " has been accepted!";
            }
        } elseif ($type == 'room') {
            $booking = \App\Models\RoomBooking::findOrFail($id);
            $booking->update(['status' => 'accepted']);
            $userId = $booking->user_id;
            $msg = "Your room booking for " . $booking->room->room_number . " has been accepted!";
        } elseif ($type == 'parking') {
            $booking = \App\Models\ParkingBooking::findOrFail($id);
            $booking->update(['status' => 'accepted']);
            $userId = $booking->user_id;
            $msg = "Your parking booking for Slot " . $booking->slot->slot_number . " has been accepted!";
        }

        // Notify Client
        \App\Models\Notification::create([
            'user_id' => $userId,
            'title' => 'Booking Accepted',
            'message' => $msg,
            'type' => 'success',
            'link' => route('client.booking-history'),
        ]);

        AuditLogger::log('booking.accepted', [
            'type' => $type,
            'booking_group_id' => $id,
            'user_id' => $userId,
        ]);

        return back()->with('success', 'Booking accepted and client notified!');
    }

    public function reject_booking(Request $request, $type, $id)
    {
        if ($type == 'ride') {
            // Check if $id is a group ID or a single ID
            $bookings = \App\Models\Booking::where('booking_group_id', $id)
                ->orWhere('id', str_replace('SINGLE-', '', $id))
                ->get();

            if ($bookings->isEmpty()) {
                return back()->with('error', 'Booking not found.');
            }

            foreach ($bookings as $booking) {
                $booking->update(['status' => 'rejected']);
            }

            $firstBooking = $bookings->first();
            $userId = $firstBooking->user_id;

            if ($bookings->count() > 1) {
                $msg = "Your ride cluster booking (" . $bookings->count() . " items) has been rejected.";
            } else {
                $name = $firstBooking->ride ? $firstBooking->ride->name : ($firstBooking->ticketType ? $firstBooking->ticketType->name : 'Ticket');
                $msg = "Your ride booking for " . $name . " has been rejected.";
            }
        } elseif ($type == 'room') {
            $booking = \App\Models\RoomBooking::findOrFail($id);
            $booking->update(['status' => 'rejected']);
            $userId = $booking->user_id;
            $msg = "Your room booking for " . $booking->room->room_number . " has been rejected.";
        } elseif ($type == 'parking') {
            $booking = \App\Models\ParkingBooking::findOrFail($id);
            $booking->update(['status' => 'rejected']);
            $userId = $booking->user_id;
            $msg = "Your parking booking for Slot " . $booking->slot->slot_number . " has been rejected.";
        }

        // Notify Client
        \App\Models\Notification::create([
            'user_id' => $userId,
            'title' => 'Booking Rejected',
            'message' => $msg,
            'type' => 'danger',
            'link' => route('client.booking-history'),
        ]);

        AuditLogger::log('booking.rejected', [
            'type' => $type,
            'booking_group_id' => $id,
            'user_id' => $userId,
        ]);

        return back()->with('error', 'Booking rejected and client notified.');
    }

    public function map()
    {
        return view('admin.map');
    }
    public function park_maintenance()
    {
        $rides = \App\Models\Ride::all();
        $rooms = \App\Models\Room::all();
        $dishes = \App\Models\Dish::all();
        $staff = \App\Models\User::where('role', 'staff')->get();

        // Fetch pending reports
        $rideReports = \App\Models\MaintenanceReport::with('ride')->where('status', 'pending')->latest()->get();
        $roomReports = \App\Models\RoomMaintenanceReport::with('room')->where('status', 'pending')->latest()->get();
        $foodReports = \App\Models\FoodMaintenanceReport::with('dish')->where('status', 'pending')->latest()->get();
        $staffTasks = \App\Models\StaffTask::with('staff')->latest()->limit(10)->get();

        return view('admin.park_maintenance', compact('rides', 'rooms', 'dishes', 'staff', 'rideReports', 'roomReports', 'foodReports', 'staffTasks'));
    }

    public function send_maintenance_command(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'item_id' => 'nullable', // Ride name, Room number, or Dish name
            'report_id' => 'nullable', // ID of the report being resolved
            'priority' => 'nullable|in:low,normal,high,urgent',
            'due_at' => 'nullable|date',
        ]);

        $staff = \App\Models\User::find($request->staff_id);
        if (! $staff || $staff->role !== 'staff') {
            return redirect()->back()->with('error', 'Selected user is not a staff member.');
        }
        $title = "Maintenance Task: " . ucwords(str_replace('_', ' ', $request->type));
        $message = "";

        // Auto-restore logic and report marking
        if ($request->type === 'repair_ride') {
            $ride = \App\Models\Ride::where('name', $request->item_id)->first();
            if ($ride) {
                $ride->update(['is_active' => true]);
                $message = "Please repair the ride: " . $request->item_id;

                // Mark report as fixed if applicable
                if ($request->report_id) {
                    \App\Models\MaintenanceReport::where('id', $request->report_id)->update(['status' => 'fixed']);
                }
            }
        } elseif ($request->type === 'clean_room' || $request->type === 'repair_room') {
            $room = \App\Models\Room::where('room_number', $request->item_id)->first();
            if ($room) {
                $room->update(['status' => 'available']);
                $message = ($request->type === 'clean_room')
                    ? "Please clean resort room: " . $request->item_id
                    : "Please repair resort room: " . $request->item_id;

                // Mark report as fixed if applicable
                if ($request->report_id) {
                    \App\Models\RoomMaintenanceReport::where('id', $request->report_id)->update(['status' => 'fixed']);
                }
            }
        } elseif ($request->type === 'restore_food') {
            $dish = \App\Models\Dish::where('name', $request->item_id)->first();
            if ($dish) {
                $dish->update(['is_available' => true]);
                $message = "Please restock/prepare: " . $request->item_id;

                // Mark report as fixed if applicable
                if ($request->report_id) {
                    \App\Models\FoodMaintenanceReport::where('id', $request->report_id)->update(['status' => 'fixed']);
                }
            }
        } elseif ($request->type === 'clean_park') {
            $message = "Please clean the park grounds.";
        }

        // Create Staff Task
        \App\Models\StaffTask::create([
            'staff_id' => $request->staff_id,
            'admin_id' => auth()->id(),
            'task_type' => $request->type,
            'item_name' => $request->item_id,
            'description' => $message,
            'status' => 'pending',
            'priority' => $request->priority ?? 'normal',
            'due_at' => $request->due_at,
        ]);

        \App\Models\Notification::create([
            'user_id' => $request->staff_id,
            'title' => $title,
            'message' => $message,
            'type' => 'info',
            'link' => route('staff.tasks'),
        ]);

        AuditLogger::log('maintenance.assigned', [
            'type' => $request->type,
            'item_id' => $request->item_id,
            'staff_id' => $request->staff_id,
            'priority' => $request->priority ?? 'normal',
            'due_at' => $request->due_at,
            'report_id' => $request->report_id,
        ]);

        return redirect()->back()->with('success', 'Maintenance command sent and task assigned to ' . $staff->name);
    }

    public function apiBookings(Request $request)
    {
        $user = $request->user();

        $rideQuery = \App\Models\Booking::with(['ride', 'ticketType']);
        $roomQuery = \App\Models\RoomBooking::with(['room']);
        $dishQuery = \App\Models\DishBooking::with(['dish']);
        $parkingQuery = \App\Models\ParkingBooking::with(['slot']);

        if ($user && $user->role === 'client') {
            $rideQuery->where('user_id', $user->id);
            $roomQuery->where('user_id', $user->id);
            $dishQuery->where('user_id', $user->id);
            $parkingQuery->where('user_id', $user->id);
        }

        return response()->json([
            'rides' => $rideQuery->latest()->get(),
            'rooms' => $roomQuery->latest()->get(),
            'dishes' => $dishQuery->latest()->get(),
            'parking' => $parkingQuery->latest()->get(),
        ]);
    }

    public function apiStoreBooking(Request $request)
    {
        $request->validate([
            'type' => 'required|in:ride,ticket,room,dish,parking',
            'quantity' => 'nullable|integer|min:1',
            'ride_id' => 'nullable|exists:rides,id',
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
            'room_id' => 'nullable|exists:rooms,id',
            'dish_id' => 'nullable|exists:dishes,id',
            'parking_slot_id' => 'nullable|exists:parking_slots,id',
            'check_in_time' => 'nullable|date',
            'check_out_time' => 'nullable|date|after_or_equal:check_in_time',
            'time_slot' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
        ]);

        $userId = $request->user()->id;
        $type = $request->type;

        if ($type === 'ride' && $request->ride_id) {
            $ride = \App\Models\Ride::findOrFail($request->ride_id);
            $quantity = $request->quantity ?? 1;

            $booking = \App\Models\Booking::create([
                'user_id' => $userId,
                'ride_id' => $ride->id,
                'quantity' => $quantity,
                'total_price' => $ride->price * $quantity,
                'booking_date' => now(),
                'status' => 'pending',
            ]);

            return response()->json(['booking' => $booking], 201);
        }

        if ($type === 'ticket' && $request->ticket_type_id) {
            $ticket = \App\Models\TicketType::findOrFail($request->ticket_type_id);
            $quantity = $request->quantity ?? 1;

            $booking = \App\Models\Booking::create([
                'user_id' => $userId,
                'ticket_type_id' => $ticket->id,
                'quantity' => $quantity,
                'total_price' => $ticket->price * $quantity,
                'booking_date' => now(),
                'status' => 'pending',
            ]);

            return response()->json(['booking' => $booking], 201);
        }

        if ($type === 'room' && $request->room_id) {
            $room = \App\Models\Room::findOrFail($request->room_id);
            $checkIn = $request->check_in_time ?? now();
            $checkOut = $request->check_out_time ?? now()->addHours(12);

            $booking = \App\Models\RoomBooking::create([
                'user_id' => $userId,
                'room_id' => $room->id,
                'check_in_time' => $checkIn,
                'check_out_time' => $checkOut,
                'total_price' => $room->price_per_12h,
                'status' => 'pending',
            ]);

            return response()->json(['booking' => $booking], 201);
        }

        if ($type === 'dish' && $request->dish_id) {
            $dish = \App\Models\Dish::findOrFail($request->dish_id);
            $quantity = $request->quantity ?? 1;

            $booking = \App\Models\DishBooking::create([
                'user_id' => $userId,
                'dish_id' => $dish->id,
                'quantity' => $quantity,
                'total_price' => $dish->price * $quantity,
                'booking_date' => now(),
                'status' => 'confirmed',
            ]);

            return response()->json(['booking' => $booking], 201);
        }

        if ($type === 'parking' && $request->parking_slot_id) {
            $booking = \App\Models\ParkingBooking::create([
                'user_id' => $userId,
                'parking_slot_id' => $request->parking_slot_id,
                'date' => now()->toDateString(),
                'time_slot' => $request->time_slot ?? 'default',
                'status' => 'pending',
                'price' => $request->price ?? 0,
            ]);

            return response()->json(['booking' => $booking], 201);
        }

        return response()->json(['error' => 'Invalid booking request.'], 422);
    }

    public function get_stats(\Illuminate\Http\Request $request)
    {
        $category = $request->category ?? 'best_ride';
        $filter = $request->filter ?? 'month';
        $cacheKey = "analytics.stats.$category.$filter";
        $payload = Cache::remember($cacheKey, 60, function () use ($category, $filter) {
            $labels = [];
            $data = [];
            $title = "";
            $highlight = "";
            $confirmed = ['confirmed', 'accepted', 'booked'];
            $countable = ['pending', 'confirmed', 'accepted', 'booked'];

            // Determine date range
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();

            if ($filter == 'year') {
                $start = now()->startOfYear();
                $end = now()->endOfYear();
            } elseif ($filter == 'day') {
                $start = now()->startOfDay();
                $end = now()->endOfDay();
            }

            switch ($category) {
                case 'best_ride':
                    $stats = \App\Models\Booking::whereNotNull('ride_id')
                        ->whereIn('status', $countable)
                        ->whereBetween('booking_date', [$start, $end])
                        ->with('ride')
                        ->get()
                        ->groupBy('ride.name')
                        ->map(fn($group) => $group->sum('quantity'))
                        ->sortByDesc(fn($sum) => $sum);

                    $labels = $stats->keys()->toArray();
                    $data = $stats->values()->toArray();
                    $title = "Best Rides (" . ucfirst($filter) . ")";
                    $highlight = $stats->count() > 0 ? $stats->keys()->first() . " is the most popular ride!" : "No data available.";
                    break;

                case 'best_room':
                    $stats = \App\Models\RoomBooking::whereBetween('created_at', [$start, $end])
                        ->whereIn('status', $countable)
                        ->with('room')
                        ->get()
                        ->groupBy('room.room_number')
                        ->map(fn($group) => $group->count())
                        ->sortByDesc(fn($count) => $count);

                    $labels = $stats->keys()->map(fn($n) => "Room $n")->toArray();
                    $data = $stats->values()->toArray();
                    $title = "Best Rooms (" . ucfirst($filter) . ")";
                    $highlight = $stats->count() > 0 ? "Room " . $stats->keys()->first() . " is the most booked room!" : "No data available.";
                    break;

                case 'biggest_customer':
                    // Sum spending across all booking types
                    $entrySpending = \App\Models\Booking::whereBetween('booking_date', [$start, $end])
                        ->whereIn('status', $confirmed)
                        ->with('user')
                        ->get()
                        ->groupBy('user.name')
                        ->map->sum('total_price');
                    $roomSpending = \App\Models\RoomBooking::whereBetween('created_at', [$start, $end])
                        ->whereIn('status', $confirmed)
                        ->with('user')
                        ->get()
                        ->groupBy('user.name')
                        ->map->sum('total_price');
                    $dishSpending = \App\Models\DishBooking::whereBetween('booking_date', [$start, $end])
                        ->whereIn('status', $confirmed)
                        ->with('user')
                        ->get()
                        ->groupBy('user.name')
                        ->map->sum('total_price');

                    $stats = collect();
                    foreach ([$entrySpending, $roomSpending, $dishSpending] as $s) {
                        foreach ($s as $name => $total) {
                            $stats[$name] = ($stats[$name] ?? 0) + $total;
                        }
                    }
                    $stats = $stats->sortByDesc(fn($v) => $v)->take(10);

                    $labels = $stats->keys()->toArray();
                    $data = $stats->values()->toArray();
                    $title = "Top Customers (" . ucfirst($filter) . ")";
                    $highlight = $stats->count() > 0 ? $stats->keys()->first() . " is our biggest spender!" : "No data available.";
                    break;

                case 'best_dish':
                    $stats = \App\Models\DishBooking::whereBetween('booking_date', [$start, $end])
                        ->whereIn('status', $countable)
                        ->with('dish')
                        ->get()
                        ->groupBy('dish.name')
                        ->map(fn($group) => $group->sum('quantity'))
                        ->sortByDesc(fn($sum) => $sum);

                    $labels = $stats->keys()->toArray();
                    $data = $stats->values()->toArray();
                    $title = "Best Dishes (" . ucfirst($filter) . ")";
                    $highlight = $stats->count() > 0 ? $stats->keys()->first() . " is the most ordered dish!" : "No data available.";
                    break;

                case 'crowdiest_day':
                    $rideCounts = \App\Models\Booking::whereBetween('booking_date', [$start, $end])
                        ->whereIn('status', $countable)
                        ->get()
                        ->groupBy(fn($item) => \Carbon\Carbon::parse($item->booking_date)->format('Y-m-d'))
                        ->map(fn($group) => $group->sum('quantity'));

                    $roomCounts = \App\Models\RoomBooking::whereBetween('created_at', [$start, $end])
                        ->whereIn('status', $countable)
                        ->get()
                        ->groupBy(fn($item) => $item->created_at->format('Y-m-d'))
                        ->map(fn($group) => $group->count());

                    $dishCounts = \App\Models\DishBooking::whereBetween('booking_date', [$start, $end])
                        ->whereIn('status', $countable)
                        ->get()
                        ->groupBy(fn($item) => \Carbon\Carbon::parse($item->booking_date)->format('Y-m-d'))
                        ->map(fn($group) => $group->sum('quantity'));

                    $stats = collect();
                    foreach ([$rideCounts, $roomCounts, $dishCounts] as $series) {
                        foreach ($series as $date => $total) {
                            $stats[$date] = ($stats[$date] ?? 0) + $total;
                        }
                    }

                    $stats = $stats->sortKeys();
                    $labels = $stats->keys()->toArray();
                    $data = $stats->values()->toArray();
                    $title = "Visitor Traffic (" . ucfirst($filter) . ")";
                    $highest = $stats->sortByDesc(fn($v) => $v);
                    $highlight = $stats->count() > 0 ? \Carbon\Carbon::parse($highest->keys()->first())->format('M d') . " was the crowdiest day with " . $highest->first() . " visitors!" : "No data available.";
                    break;
            }

            return [
                'labels' => $labels,
                'data' => $data,
                'title' => $title,
                'highlight' => $highlight,
            ];
        });

        return response()->json($payload);
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'designation' => $request->designation,
        ]);

        return redirect()->route('admin.my_profile')->with('success', 'Profile updated successfully!');
    }

    public function gift_entry_ticket(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1',
            'expiry_date' => 'required|date|after:today',
        ]);

        $ticketType = \App\Models\TicketType::find($request->ticket_type_id);
        $totalPrice = $ticketType->price * $request->quantity;

        // Create booking record
        \App\Models\Booking::create([
            'user_id' => $request->client_id,
            'ticket_type_id' => $request->ticket_type_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'booking_date' => now(),
            'expiry_date' => $request->expiry_date,
            'status' => 'gifted',
        ]);

        return redirect()->route('admin.bookings.entry_tickets')->with('success', 'Entry ticket gifted successfully!');
    }
    public function delete_staff_task($id)
    {
        $task = \App\Models\StaffTask::findOrFail($id);
        $task->delete();
        return back()->with('success', 'Task removed from history successfully.');
    }
}
