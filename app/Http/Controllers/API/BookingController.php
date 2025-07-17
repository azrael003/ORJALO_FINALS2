<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\BookingCreated;

class BookingController extends Controller
{
    /**
     * Display a listing of the user's bookings and all booked dates.
     */
    public function index()
    {
        // Get all booked dates globally in ISO 8601 format for calendar disabling
        $bookedDates = Booking::pluck('booking_date')->map(function ($date) {
            return Carbon::parse($date)->toIso8601String();
        })->toArray();

        // Get bookings for the logged-in user, ordered by booking date descending
        $bookings = Booking::where('user_id', Auth::id())
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('bookings.index', compact('bookings', 'bookedDates'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'booking_date' => 'required|date|unique:bookings,booking_date',
        ]);

        $booking = Auth::user()->bookings()->create($validated);

        // Notify user about new booking
        Auth::user()->notify(new BookingCreated($booking));

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);

        // Pass booked dates excluding this booking's date if needed for calendar disable
        $bookedDates = Booking::where('id', '!=', $booking->id)
            ->pluck('booking_date')
            ->map(fn($date) => Carbon::parse($date)->toIso8601String())
            ->toArray();

        return view('bookings.edit', compact('booking', 'bookedDates'));
    }

    /**
     * Update the specified booking.
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'booking_date' => 'required|date|after_or_equal:today',
        ]);

        $booking->update($validated);

        // Redirect to dashboard with success message
        return redirect()->route('dashboard')->with('success', 'Booking updated successfully!');
    }

    /**
     * Remove the specified booking.
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
