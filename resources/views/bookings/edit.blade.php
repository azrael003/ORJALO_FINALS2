@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

<div class="py-12 px-4 min-h-screen bg-gradient-to-tr from-pink-300 via-green-300 to-blue-300">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-2xl p-8 border border-purple-200">
            <h2 class="text-2xl font-bold mb-6 text-center text-purple-700">Edit Booking</h2>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 mb-4 rounded-lg border border-green-300 text-center">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('bookings.update', $booking->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div>
                    <label for="title" class="block font-medium text-purple-700 mb-1">Title</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $booking->title) }}"
                        required
                        class="w-full border border-purple-300 bg-white text-purple-900 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400"
                    />
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block font-medium text-purple-700 mb-1">Description</label>
                    <textarea
                        name="description"
                        id="description"
                        rows="3"
                        required
                        class="w-full border border-purple-300 bg-white text-purple-900 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400"
                    >{{ old('description', $booking->description) }}</textarea>
                </div>

                <!-- Hidden Date Input -->
                <input
                    type="hidden"
                    name="booking_date"
                    id="booking_date"
                    value="{{ \Carbon\Carbon::parse(old('booking_date', $booking->booking_date))->format('Y-m-d H:i') }}"
                />

                <!-- Calendar -->
                <div>
                    <label for="inline-calendar" class="block text-sm font-medium text-purple-700 mb-2">Booking Date & Time</label>
                    <div
                        id="inline-calendar"
                        class="border border-purple-300 rounded-lg p-2 bg-white shadow-sm w-full text-purple-900"
                    ></div>
                    <p class="text-purple-600 text-xs mt-1">Select your booking date and time.</p>
                    @error('booking_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="pt-3 text-center">
                    <button
                        type="submit"
                        class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 transform hover:scale-105"
                    >
                        Update Booking
                    </button>
                </div>
            </form>

            <!-- Back Button -->
            <div class="mt-6 text-center space-y-3">
                <a href="{{ route('bookings.index') }}"
                   class="inline-block px-6 py-2 text-purple-700 border border-purple-400 rounded-lg hover:bg-purple-100 transition"
                >
                    &larr; Back to Bookings
                </a>

                <a href="{{ route('dashboard') }}"
                   class="inline-block px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition"
                >
                    Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const bookedDates = @json($bookedDates);
    const hiddenInput = document.getElementById("booking_date");

    flatpickr("#inline-calendar", {
        inline: true,
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        disable: bookedDates,
        time_24hr: false,
        defaultDate: hiddenInput.value || null,
        onChange: function(selectedDates, dateStr) {
            hiddenInput.value = dateStr;
        }
    });
</script>
@endsection
