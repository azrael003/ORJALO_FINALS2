<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with total stats and user-specific bookings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get total bookings across all users
       $totalBookings = Booking::where('user_id', Auth::id())->count();

        // Get total number of users
        $totalUsers = User::count();

        // Optionally, get last 10 registered users
        $users = User::orderBy('created_at', 'desc')->take(10)->get();

        // Get bookings for the logged-in user ordered by latest booking_date
        $bookings = Booking::where('user_id', Auth::id())
                           ->orderBy('booking_date', 'desc')
                           ->get();

        return view('dashboard', compact('totalBookings', 'totalUsers', 'users', 'bookings'));
    }
}
