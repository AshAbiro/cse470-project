<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthController extends Controller
{
    /**
     * Show the health status of the application.
     */
    public function check()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Amusement Park Management System is running',
            'app_name' => config('app.name'),
            'environment' => config('app.env'),
            'debug' => config('app.debug'),
            'timestamp' => now()
        ], 200);
    }
}
