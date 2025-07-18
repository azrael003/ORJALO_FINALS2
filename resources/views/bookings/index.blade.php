@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<div class="min-h-screen bg-gradient-to-tr from-pink-200 via-green-200 to-blue-200 dark:from-pink-700 dark:via-green-700 dark:to-blue-700">
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Success message -->
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 p-3 rounded-lg border border-green-300 dark:border-green-600 text-center mb-6">
                {{ session('success') }}
                <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-300 hover:underline ml-2">
                    View in Dashboard
                </a>
            </div>
        @endif

        <!-- Error messages -->
        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200 p-3 rounded-lg border border-red-300 dark:border-red-600 mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Booking Form Card -->
        <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-6 border border-purple-200 dark:border-gray-600">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-purple-700 dark:text-gray-200">
                    <i class="fas fa-plus-circle mr-2"></i> Create Booking
                </h2>
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <form method="POST" action="{{ route('bookings.store') }}" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Title *</label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        class="w-full px-3 py-2 rounded border border-purple-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="make a booking"
                    />
                </div>

                <!-- Description -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea
                        name="description"
                        rows="3"
                        class="w-full px-3 py-2 rounded border border-purple-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="Details about the booking"
                    >{{ old('description') }}</textarea>
                </div>

                <!-- Date Picker -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Date & Time *</label>
                    <input
                        type="hidden"
                        name="booking_date"
                        id="booking_date"
                        value="{{ old('booking_date') }}"
                        required
                    />
                    <div
                        id="inline-calendar"
                        class="border border-purple-300 dark:border-gray-600 rounded-lg p-2 bg-white dark:bg-gray-800 shadow-sm w-full"
                    ></div>
                    <p class="text-purple-600 dark:text-purple-300 text-xs mt-1">
                        Select any available date and time
                    </p>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded border border-purple-300 dark:border-gray-600 text-purple-700 dark:text-purple-300 hover:bg-purple-100 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="px-4 py-2 rounded bg-pink-500 hover:bg-pink-600 text-white font-semibold transition flex items-center"
                    >
                        <i class="fas fa-calendar-check mr-2"></i> Book Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hiddenInput = document.getElementById("booking_date");

        // Initialize calendar with NO disabled dates
        const calendar = flatpickr("#inline-calendar", {
            inline: true,
            enableTime: true,
            dateFormat: "Y-m-d H:i:s",
            minDate: "today",
            time_24hr: false,
            defaultDate: hiddenInput.value ? new Date(hiddenInput.value) : null,
            onChange: function(selectedDates, dateStr) {
                hiddenInput.value = dateStr;
            }
        });

        // Set initial value if there's old input
        if (hiddenInput.value) {
            calendar.setDate(hiddenInput.value);
        }
    });
</script>

<style>
    /* Calendar styling */
    #inline-calendar {
        width: 100%;
        max-width: 500px;
        font-size: 1.1rem;
    }

    .flatpickr-day {
        height: 2.5rem;
        line-height: 2.5rem;
        width: 2.5rem;
        margin: 0.1rem;
    }

    /* Available time slots */
    .flatpickr-day:hover,
    .flatpickr-day.selected {
        background: #ec4899;
        color: white;
        border-color: #ec4899;
    }

    /* Dark mode styles */
    .dark .flatpickr-calendar {
        background: #374151;
        color: #f3f4f6;
    }
    .dark .flatpickr-day:hover {
        background: #4b5563;
    }
    .dark .flatpickr-day.selected {
        background: #ec4899;
    }
</style>
@endsection
