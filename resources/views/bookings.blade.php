<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingViewController extends Controller
{
    /**
     * Show the list of bookings for the authenticated user.
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Store a new booking.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'booking_date' => 'required|date',
        ]);

        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->title = $request->title;
        $booking->description = $request->description;
        $booking->booking_date = $request->booking_date;
        $booking->save();

        return redirect()->route('dashboard')->with('success', 'Booking successfully created!');
    }
}
