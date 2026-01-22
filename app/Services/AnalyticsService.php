<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\DishBooking;
use App\Models\User;
use App\Models\Ride;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
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
        return Booking::where('status', 'pending')->count() +
               RoomBooking::where('status', 'pending')->count() +
               DishBooking::where('status', 'pending')->count();
    }

    /**
     * Get confirmed bookings
     */
    public function getConfirmedBookings()
    {
        return Booking::where('status', 'confirmed')->count() +
               RoomBooking::where('status', 'confirmed')->count() +
               DishBooking::where('status', 'confirmed')->count();
    }

    /**
     * Get total revenue
     */
    public function getTotalRevenue()
    {
        return Booking::where('status', 'confirmed')->sum('total_price') +
               RoomBooking::where('status', 'confirmed')->sum('total_price') +
               DishBooking::where('status', 'confirmed')->sum('total_price');
    }

    /**
     * Get monthly revenue
     */
    public function getMonthlyRevenue($month = null)
    {
        $month = $month ?? now()->month;
        
        return Booking::whereMonth('created_at', $month)
                ->where('status', 'confirmed')
                ->sum('total_price') +
               RoomBooking::whereMonth('created_at', $month)
                ->where('status', 'confirmed')
                ->sum('total_price') +
               DishBooking::whereMonth('created_at', $month)
                ->where('status', 'confirmed')
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
            
            $revenue = Booking::whereDate('created_at', $date)
                        ->where('status', 'confirmed')
                        ->sum('total_price') +
                       RoomBooking::whereDate('created_at', $date)
                        ->where('status', 'confirmed')
                        ->sum('total_price') +
                       DishBooking::whereDate('created_at', $date)
                        ->where('status', 'confirmed')
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

        $bookedRooms = RoomBooking::where('status', 'confirmed')
            ->where('check_in_date', '<=', now())
            ->where('check_out_date', '>=', now())
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
        foreach (['pending', 'confirmed', 'cancelled', 'completed'] as $status) {
            $results[$status] = ($rideBookings[$status] ?? 0) + ($roomBookings[$status] ?? 0);
        }

        return $results;
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
