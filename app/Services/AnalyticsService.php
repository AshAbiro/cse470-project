<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\DishBooking;
use App\Models\User;
use App\Models\Ride;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        return Cache::remember('analytics.dashboard_stats', 60, function () {
            return [
                'total_users' => User::count(),
                'total_staff' => User::where('role', 'staff')->count(),
                'total_clients' => User::where('role', 'client')->count(),
                'total_rides' => Ride::count(),
                'active_rides' => Ride::where('is_active', true)->count(),
                'total_rooms' => Room::count(),
                'total_bookings' => $this->getTotalBookings(),
                'pending_bookings' => $this->getPendingBookings(),
                'confirmed_bookings' => $this->getConfirmedBookings(),
                'total_revenue' => $this->getTotalRevenue(),
                'monthly_revenue' => $this->getMonthlyRevenue(),
                'occupancy_rate' => $this->getOccupancyRate(),
            ];
        });
    }

    /**
     * Get total bookings
     */
    public function getTotalBookings()
    {
        return Booking::count() + RoomBooking::count() + DishBooking::count();
    }

    /**
     * Get pending bookings
     */
    public function getPendingBookings()
    {
        $pending = $this->pendingStatuses();

        return Booking::whereIn('status', $pending)->count() +
               RoomBooking::whereIn('status', $pending)->count() +
               DishBooking::whereIn('status', $pending)->count();
    }

    /**
     * Get confirmed bookings
     */
    public function getConfirmedBookings()
    {
        $confirmed = $this->confirmedStatuses();

        return Booking::whereIn('status', $confirmed)->count() +
               RoomBooking::whereIn('status', $confirmed)->count() +
               DishBooking::whereIn('status', $confirmed)->count();
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue()
    {
        $confirmed = $this->confirmedStatuses();

        return Booking::whereIn('status', $confirmed)->sum('total_price') +
               RoomBooking::whereIn('status', $confirmed)->sum('total_price') +
               DishBooking::whereIn('status', $confirmed)->sum('total_price');
    }

    /**
     * Get monthly revenue
     */
    public function getMonthlyRevenue($month = null)
    {
        $month = $month ?? now()->month;
        $confirmed = $this->confirmedStatuses();
        
        return Booking::whereMonth('created_at', $month)
                ->whereIn('status', $confirmed)
                ->sum('total_price') +
               RoomBooking::whereMonth('created_at', $month)
                ->whereIn('status', $confirmed)
                ->sum('total_price') +
               DishBooking::whereMonth('created_at', $month)
                ->whereIn('status', $confirmed)
                ->sum('total_price');
    }

    /**
     * Get revenue by day for chart
     */
    public function getRevenueByDay($days = 30)
    {
        $dates = [];
        $revenues = [];
        
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('M d');
            
            $confirmed = $this->confirmedStatuses();

            $revenue = Booking::whereDate('created_at', $date)
                        ->whereIn('status', $confirmed)
                        ->sum('total_price') +
                       RoomBooking::whereDate('created_at', $date)
                        ->whereIn('status', $confirmed)
                        ->sum('total_price') +
                       DishBooking::whereDate('created_at', $date)
                        ->whereIn('status', $confirmed)
                        ->sum('total_price');
            
            $revenues[] = $revenue;
        }
        
        return [
            'labels' => $dates,
            'data' => $revenues
        ];
    }

    /**
     * Get occupancy rate
     */
    public function getOccupancyRate()
    {
        $totalRooms = Room::count();
        if ($totalRooms == 0) {
            return 0;
        }

        $confirmed = $this->confirmedStatuses();

        $bookedRooms = RoomBooking::whereIn('status', $confirmed)
            ->where('check_in_time', '<=', now())
            ->where('check_out_time', '>=', now())
            ->distinct('room_id')
            ->count('room_id');

        return round(($bookedRooms / $totalRooms) * 100, 2);
    }

    /**
     * Get most popular rides
     */
    public function getMostPopularRides($limit = 5)
    {
        return Ride::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Get bookings by status
     */
    public function getBookingsByStatus()
    {
        $rideBookings = Booking::groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status');

        $roomBookings = RoomBooking::groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status');

        $results = [];
        foreach (['pending', 'accepted', 'confirmed', 'booked', 'rejected', 'cancelled', 'completed'] as $status) {
            $results[$status] = ($rideBookings[$status] ?? 0) + ($roomBookings[$status] ?? 0);
        }

        return $results;
    }

    private function pendingStatuses(): array
    {
        return ['pending'];
    }

    private function confirmedStatuses(): array
    {
        return ['confirmed', 'accepted', 'booked'];
    }

    /**
     * Get user growth data
     */
    public function getUserGrowthData($days = 30)
    {
        $dates = [];
        $counts = [];
        
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('M d');
            
            $count = User::whereDate('created_at', '<=', $date)->count();
            $counts[] = $count;
        }
        
        return [
            'labels' => $dates,
            'data' => $counts
        ];
    }

    /**
     * Get top spenders
     */
    public function getTopSpenders($limit = 10)
    {
        return User::selectRaw('users.*, 
                    COALESCE(SUM(CASE WHEN bookings.status = "confirmed" THEN bookings.total_price ELSE 0 END), 0) +
                    COALESCE(SUM(CASE WHEN room_bookings.status = "confirmed" THEN room_bookings.total_price ELSE 0 END), 0) +
                    COALESCE(SUM(CASE WHEN dish_bookings.status = "confirmed" THEN dish_bookings.total_price ELSE 0 END), 0) as total_spent')
            ->leftJoin('bookings', 'users.id', '=', 'bookings.user_id')
            ->leftJoin('room_bookings', 'users.id', '=', 'room_bookings.user_id')
            ->leftJoin('dish_bookings', 'users.id', '=', 'dish_bookings.user_id')
            ->where('users.role', 'client')
            ->groupBy('users.id')
            ->orderByDesc('total_spent')
            ->limit($limit)
            ->get();
    }
}
