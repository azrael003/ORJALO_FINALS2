@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

<style>
    /* Make the calendar container bigger */
    #inline-calendar {
        width: 400px;   /* Increased width */
        max-width: 100%; /* Responsive */
        font-size: 1.25rem; /* Larger font size */
    }

    /* Increase day size inside calendar */
    .flatpickr-day {
        height: 3rem;
        line-height: 3rem;
        width: 3rem;
        margin: 0.15rem;
        font-size: 1.1rem;
    }

    /* Increase size of time picker inputs */
    .flatpickr-time input {
        font-size: 1.1rem;
        width: 3.5rem;
        height: 2.5rem;
        padding: 0.25rem 0.5rem;
    }

    /* Increase size of navigation arrows */
    .flatpickr-prev-month,
    .flatpickr-next-month {
        height: 2.5rem;
        width: 2.5rem;
    }
</style>

<div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gradient-to-tr from-pink-300 via-green-300 to-blue-300">
    <div class="max-w-full sm:max-w-3xl mx-auto space-y-10">

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded text-sm text-center sm:text-left border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        <!-- Booking Form -->
        <section aria-labelledby="booking-form-heading" class="bg-white shadow-md rounded-2xl p-6 border border-pink-300">
            <h2 id="booking-form-heading" class="text-xl sm:text-2xl font-bold mb-6 text-purple-700 text-center sm:text-left">
                Create a Booking
            </h2>

            <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-purple-700">Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        value="{{ old('title') }}"
                        required
                        class="w-full mt-1 border border-purple-300 bg-white text-purple-900 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400" 
                    />
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-purple-700">Description</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="3"
                        required
                        class="w-full mt-1 border border-purple-300 bg-white text-purple-900 rounded-lg p-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hidden Date Input -->
                <input 
                    type="hidden" 
                    name="booking_date" 
                    id="booking_date" 
                    value="{{ old('booking_date') }}"
                />

                <!-- Calendar -->
                <div>
                    <label for="inline-calendar" class="block text-sm font-medium text-purple-700 mb-2">Booking Date &amp; Time</label>
                    <div 
                        id="inline-calendar"
                        class="border border-purple-300 rounded-lg p-2 bg-white shadow-sm w-full max-w-full text-purple-900"
                        aria-describedby="calendarHelp"
                    ></div>
                    <p id="calendarHelp" class="text-purple-600 text-xs mt-1">Select your booking date and time.</p>
                    @error('booking_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3 text-left">
                    <button 
                        type="submit" 
                        class="bg-pink-500 hover:bg-pink-600 text-white py-3 px-8 rounded-lg transition duration-300 font-semibold transform hover:scale-105 text-base"
                    >
                        Create Booking
                    </button>
                </div>
            </form>
        </section>

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
