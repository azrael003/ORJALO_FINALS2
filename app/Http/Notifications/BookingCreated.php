<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Booking;

class BookingCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    // Define channels: mail and database
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    // Email notification content
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Created')
            ->line('Your booking "' . $this->booking->title . '" was created.')
            ->action('View Booking', url('/bookings/' . $this->booking->id))
            ->line('Thank you for using our app!');
    }

    // Database notification data
    public function toArray($notifiable)
    {
        return [
            'message' => 'Your booking "' . $this->booking->title . '" was created.',
            'booking_id' => $this->booking->id,
        ];
    }
}
