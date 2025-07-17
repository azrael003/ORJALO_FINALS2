<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingViewController extends Controller
{
    /**
     * Display a list of authenticated user's bookings.
     * Pass bookedDates for use in calendar view.
     */
    public function index()
    {
        $user = Auth::user();

        $bookings = Booking::where('user_id', $user->id)
            ->orderBy('booking_date')
            ->get();

        $bookedDates = $bookings->pluck('booking_date')->map(fn($date) =>
            Carbon::parse($date)->format('Y-m-d H:i')
        )->toArray();

        return view('bookings.index', compact('bookings', 'bookedDates'));
    }

    /**
     * Store a new booking for the authenticated user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'booking_date' => 'required|date|after_or_equal:now',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'booking_date' => $request->booking_date,
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully!');
    }

    /**
     * Show the edit form for a specific booking.
     */
    public function edit(Booking $booking)
    {
        $this->authorizeBooking($booking);

        // Get all other booked dates except the current booking
        $bookedDates = Booking::where('id', '!=', $booking->id)
            ->pluck('booking_date')
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d H:i'))
            ->toArray();

        return view('bookings.edit', compact('booking', 'bookedDates'));
    }

    /**
     * Update the specified booking.
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'booking_date' => 'required|date|after_or_equal:now',
        ]);

        $booking->update($request->only(['title', 'description', 'booking_date']));

        // Redirect to dashboard after update
        return redirect()->route('dashboard')
            ->with('success', 'Booking updated!');
    }

    /**
     * Remove the specified booking.
     */
    public function destroy(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted!');
    }

    /**
     * Ensure the booking belongs to the currently authenticated user.
     */
    private function authorizeBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
