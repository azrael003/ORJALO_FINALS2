<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    // Can user view the booking?
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    // Can user update the booking?
    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    // Can user delete the booking?
    public function delete(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id;
    }

    // Can user create a booking? (Allow all authenticated users)
    public function create(User $user): bool
    {
        return true;
    }
}
