@extends('layouts.app')

@section('title', 'View Booking')

@section('content')
<div class="min-h-screen bg-gradient-to-tr from-purple-100 via-pink-100 to-purple-200 py-12 px-4 flex justify-center">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-md p-6 border border-purple-200">
        <h2 class="text-2xl font-bold text-purple-700 mb-4">Booking Details</h2>

        <div class="space-y-4">
            <div>
                <label class="text-sm font-semibold text-purple-600">Title:</label>
                <p class="text-purple-800">{{ $booking->title }}</p>
            </div>

            <div>
                <label class="text-sm font-semibold text-purple-600">Description:</label>
                <p class="text-purple-800">{{ $booking->description }}</p>
            </div>

            <div>
                <label class="text-sm font-semibold text-purple-600">Booking Date & Time:</label>
                <p class="text-purple-800">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y h:i A') }}</p>
            </div>
        </div>

        <div class="mt-6 text-right">
            <a href="{{ route('dashboard') }}" class="text-sm text-purple-600 hover:underline">← Back to Dashboard</a>
        </div>
    </div>
</div>
@endsection
