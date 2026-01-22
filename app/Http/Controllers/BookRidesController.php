<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;

class BookRidesController extends Controller
{
    public function index()
    {
        // Fetch all 21 rides
        $rides = Ride::all();

        return view('client.book-rides', compact('rides'));
    }
}
