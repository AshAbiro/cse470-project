<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->take(3)->get();
        $staffMembers = User::where('role', 'staff')->get();

        return view('client.contact', compact('admins', 'staffMembers'));
    }
}
