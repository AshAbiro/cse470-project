<?php

/**
 * Application Helper Functions
 * 
 * This file contains utility functions used throughout the application
 */

/**
 * Get the currently authenticated user's role
 */
if (!function_exists('userRole')) {
    function userRole() {
        return auth()->check() ? auth()->user()->role : null;
    }
}

/**
 * Check if the current user is an admin
 */
if (!function_exists('isAdmin')) {
    function isAdmin() {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}

/**
 * Check if the current user is a staff member
 */
if (!function_exists('isStaff')) {
    function isStaff() {
        return auth()->check() && auth()->user()->role === 'staff';
    }
}

/**
 * Check if the current user is a client
 */
if (!function_exists('isClient')) {
    function isClient() {
        return auth()->check() && auth()->user()->role === 'client';
    }
}

/**
 * Format currency
 */
if (!function_exists('formatCurrency')) {
    function formatCurrency($amount) {
        return '₹' . number_format($amount, 2, '.', ',');
    }
}

/**
 * Get the park name
 */
if (!function_exists('parkName')) {
    function parkName() {
        return config('app.park_name', 'Amusement Park');
    }
}

/**
 * Get booking status badge color
 */
if (!function_exists('getStatusColor')) {
    function getStatusColor($status) {
        return match($status) {
            'pending' => 'yellow',
            'confirmed' => 'green',
            'cancelled' => 'red',
            'completed' => 'blue',
            default => 'gray'
        };
    }
}

/**
 * Get booking status badge text
 */
if (!function_exists('getStatusText')) {
    function getStatusText($status) {
        return match($status) {
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
            default => ucfirst($status)
        };
    }
}

/**
 * Calculate total revenue
 */
if (!function_exists('getTotalRevenue')) {
    function getTotalRevenue() {
        $bookings = \App\Models\Booking::where('status', 'confirmed')->sum('total_price');
        $roomBookings = \App\Models\RoomBooking::where('status', 'confirmed')->sum('total_price');
        $dishBookings = \App\Models\DishBooking::where('status', 'confirmed')->sum('total_price');
        return $bookings + $roomBookings + $dishBookings;
    }
}

/**
 * Get total bookings count
 */
if (!function_exists('getTotalBookings')) {
    function getTotalBookings() {
        $bookings = \App\Models\Booking::count();
        $roomBookings = \App\Models\RoomBooking::count();
        $dishBookings = \App\Models\DishBooking::count();
        return $bookings + $roomBookings + $dishBookings;
    }
}

/**
 * Get pending bookings count
 */
if (!function_exists('getPendingBookings')) {
    function getPendingBookings() {
        $bookings = \App\Models\Booking::where('status', 'pending')->count();
        $roomBookings = \App\Models\RoomBooking::where('status', 'pending')->count();
        $dishBookings = \App\Models\DishBooking::where('status', 'pending')->count();
        return $bookings + $roomBookings + $dishBookings;
    }
}

/**
 * Get total active rides
 */
if (!function_exists('getTotalActiveRides')) {
    function getTotalActiveRides() {
        return \App\Models\Ride::where('is_active', true)->count();
    }
}

/**
 * Get total users
 */
if (!function_exists('getTotalUsers')) {
    function getTotalUsers() {
        return \App\Models\User::count();
    }
}

/**
 * Get total staff members
 */
if (!function_exists('getTotalStaff')) {
    function getTotalStaff() {
        return \App\Models\User::where('role', 'staff')->count();
    }
}

/**
 * Get average rating for a model
 */
if (!function_exists('getAverageRating')) {
    function getAverageRating($ratings) {
        if ($ratings->isEmpty()) {
            return 0;
        }
        return round($ratings->avg('rating'), 1);
    }
}

/**
 * Generate a unique booking reference
 */
if (!function_exists('generateBookingReference')) {
    function generateBookingReference() {
        return 'BK' . strtoupper(uniqid());
    }
}

/**
 * Format date in readable format
 */
if (!function_exists('readableDate')) {
    function readableDate($date) {
        if (!$date) {
            return 'N/A';
        }
        return $date->format('M d, Y');
    }
}

/**
 * Format time in readable format
 */
if (!function_exists('readableTime')) {
    function readableTime($time) {
        if (!$time) {
            return 'N/A';
        }
        return $time->format('h:i A');
    }
}

/**
 * Get day name from date
 */
if (!function_exists('getDayName')) {
    function getDayName($date) {
        return $date->format('l');
    }
}

/**
 * Calculate hours between two dates
 */
if (!function_exists('getHoursBetween')) {
    function getHoursBetween($start, $end) {
        return round($start->diffInHours($end), 1);
    }
}

/**
 * Check if date is in past
 */
if (!function_exists('isPastDate')) {
    function isPastDate($date) {
        return $date->isPast();
    }
}

/**
 * Get percentage
 */
if (!function_exists('getPercentage')) {
    function getPercentage($current, $total) {
        if ($total == 0) {
            return 0;
        }
        return round(($current / $total) * 100, 2);
    }
}

/**
 * Slugify text
 */
if (!function_exists('slugify')) {
    function slugify($text) {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
    }
}
