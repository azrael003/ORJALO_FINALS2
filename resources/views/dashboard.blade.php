@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<div
    x-data="dashboardData()"
    x-init="init()"
    :class="{'dark': darkMode}"
    class="min-h-screen flex bg-gradient-to-tr from-pink-200 via-green-200 to-blue-200 dark:from-pink-700 dark:via-green-700 dark:to-blue-700"
>
    <!-- Sidebar -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-pink-400 via-green-400 to-blue-500 dark:from-pink-800 dark:via-green-800 dark:to-blue-800 text-white shadow-lg transform transition-transform duration-300 ease-in-out z-30 md:relative md:translate-x-0 flex flex-col"
    >
        <div class="flex items-center justify-center h-16 border-b border-white/20">
            <i class="fas fa-chart-line text-2xl mr-2"></i>
            <h1 class="text-2xl font-bold tracking-wider">Booking System</h1>
        </div>
        <nav class="flex-grow px-4 py-6 space-y-4">
            <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-3 rounded hover:bg-white/20 transition"
               :class="{'bg-white/20': !showCalendar && !showReportsView && !showUsersManagement && !showThisMonthBookings && !showUpcomingBookings}">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>

            <a href="#" @click.prevent="showBookingsSection()" class="flex items-center py-2 px-3 rounded hover:bg-white/20 transition"
               :class="{'bg-white/20': showThisMonthBookings || showUpcomingBookings}">
                <i class="fas fa-calendar-alt mr-2"></i> My Bookings
            </a>

            <div x-show="showBookingSubmenu" x-transition class="pl-4 space-y-2">
                <a href="#" @click.prevent="showThisMonthBookings = true; showUpcomingBookings = false"
                   class="flex items-center py-1 px-2 rounded text-sm hover:bg-white/20 transition"
                   :class="{'bg-white/20': showThisMonthBookings}">
                    <i class="fas fa-calendar-day mr-2"></i> This Month
                </a>
                <a href="#" @click.prevent="showUpcomingBookings = true; showThisMonthBookings = false"
                   class="flex items-center py-1 px-2 rounded text-sm hover:bg-white/20 transition"
                   :class="{'bg-white/20': showUpcomingBookings}">
                    <i class="fas fa-clock mr-2"></i> Upcoming
                </a>
            </div>

            <a href="#" @click.prevent="showUsersManagement = true" class="flex items-center py-2 px-3 rounded hover:bg-white/20 transition"
               :class="{'bg-white/20': showUsersManagement}">
                <i class="fas fa-users-cog mr-2"></i> User Management
            </a>

            <a href="#" @click.prevent="showCalendarView()" class="flex items-center py-2 px-3 rounded hover:bg-white/20 transition"
               :class="{'bg-white/20': showCalendar}">
                <i class="fas fa-calendar mr-2"></i> Calendar
            </a>

            <a href="#" @click.prevent="showReports()" class="flex items-center py-2 px-3 rounded hover:bg-white/20 transition"
               :class="{'bg-white/20': showReportsView}">
                <i class="fas fa-chart-pie mr-2"></i> Analytics
            </a>

            <a href="#" @click.prevent="showSettings = true" class="flex items-center py-2 px-3 rounded hover:bg-white/20 transition"
               :class="{'bg-white/20': showSettings}">
                <i class="fas fa-cog mr-2"></i> Settings
            </a>
        </nav>
        <div class="p-4 border-t border-white/20 space-y-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="font-medium" x-text="currentUser.name"></div>
                    <div class="text-xs opacity-80" x-text="currentUser.email"></div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-center py-2 rounded bg-white/20 hover:bg-white/30 transition">
                <i class="fas fa-user-edit mr-2"></i> Profile
            </a>
            <button
                @click="toggleDarkMode()"
                class="w-full flex items-center justify-center py-2 rounded bg-white/20 hover:bg-white/30 transition"
                aria-label="Toggle dark mode"
            >
                <template x-if="!darkMode">
                    <span><i class="fas fa-moon mr-2"></i> Dark Mode</span>
                </template>
                <template x-if="darkMode">
                    <span><i class="fas fa-sun mr-2"></i> Light Mode</span>
                </template>
            </button>
        </div>
    </aside>

    <!-- Main content wrapper -->
    <div class="flex flex-col flex-1 min-h-screen md:ml-64">
        <!-- Topbar for mobile -->
        <header class="flex items-center justify-between bg-white dark:bg-gray-800 shadow px-4 py-3 md:hidden">
            <button
                @click="sidebarOpen = true"
                aria-label="Open sidebar"
                class="text-pink-600 dark:text-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 rounded"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                <i class="fas fa-chart-line mr-1"></i> Dashboard
            </h1>
            <div class="w-6"></div>
        </header>

        <!-- Overlay for sidebar on mobile -->
        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-50"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-50"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black opacity-50 z-20 md:hidden"
            x-cloak
        ></div>

        <!-- Page Content -->
        <main class="p-6 sm:px-8 overflow-y-auto">
            <!-- Success message -->
            <div x-show="showSuccess" x-transition.opacity class="bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 p-3 rounded-lg border border-green-300 dark:border-green-600 text-center mb-6">
                <span x-text="successMessage"></span>
            </div>

            <!-- Error message -->
            <div x-show="showError" x-transition.opacity class="bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200 p-3 rounded-lg border border-red-300 dark:border-red-600 text-center mb-6">
                <span x-text="errorMessage"></span>
            </div>

            <!-- Calendar View -->
            <div x-show="showCalendar" x-transition class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-6 border border-purple-200 dark:border-gray-600 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-xl font-bold text-purple-700 dark:text-gray-200">
                            <i class="fas fa-calendar mr-2"></i> Calendar View
                        </h2>
                        <div class="flex items-center space-x-2">
                            <select x-model="calendarMonth" @change="generateCalendarDays()" class="bg-white dark:bg-gray-600 border border-purple-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 text-sm rounded focus:ring-pink-500 focus:border-pink-500 p-1">
                                <option value="0">January</option>
                                <option value="1">February</option>
                                <option value="2">March</option>
                                <option value="3">April</option>
                                <option value="4">May</option>
                                <option value="5">June</option>
                                <option value="6">July</option>
                                <option value="7">August</option>
                                <option value="8">September</option>
                                <option value="9">October</option>
                                <option value="10">November</option>
                                <option value="11">December</option>
                            </select>
                            <select x-model="calendarYear" @change="generateCalendarDays()" class="bg-white dark:bg-gray-600 border border-purple-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 text-sm rounded focus:ring-pink-500 focus:border-pink-500 p-1">
                                <template x-for="year in calendarYears" :key="year">
                                    <option x-text="year" :value="year"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <button @click="showCalendar = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="calendar-container">
                    <div class="grid grid-cols-7 gap-1 text-center">
                        <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day">
                            <div class="font-semibold py-2" x-text="day"></div>
                        </template>
                        <template x-for="(day, index) in calendarDays" :key="index">
                            <div
                                class="border p-2 h-24 overflow-y-auto"
                                :class="{
                                    'bg-gray-100 dark:bg-gray-600': !day.currentMonth,
                                    'hover:bg-pink-50 dark:hover:bg-pink-900': day.currentMonth
                                }"
                            >
                                <div class="text-right" x-text="day.date.getDate()"></div>
                                <template x-for="event in getEventsForDay(day.date)" :key="event.id">
                                    <div class="text-xs p-1 mt-1 bg-pink-100 dark:bg-pink-800 rounded truncate"
                                         x-text="event.title"
                                         @click="openModal('edit', event)"></div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Reports View -->
            <div x-show="showReportsView" x-transition class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-6 border border-purple-200 dark:border-gray-600 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-purple-700 dark:text-gray-200">
                        <i class="fas fa-chart-pie mr-2"></i> Analytics
                    </h2>
                    <button @click="showReportsView = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-600 rounded-lg">
                        <h3 class="font-semibold mb-2">Booking Summary</h3>
                        <p>Total Bookings: <span x-text="bookings.length"></span></p>
                        <p>This Month: <span x-text="getBookingsThisMonth().length"></span></p>
                        <p>Upcoming: <span x-text="getUpcomingBookings().length"></span></p>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-600 rounded-lg">
                        <h3 class="font-semibold mb-2">User Statistics</h3>
                        <p>Total Users: <span x-text="users.length"></span></p>
                        <p>Active Users: <span x-text="users.filter(u => u.active).length"></span></p>
                        <p>Inactive Users: <span x-text="users.filter(u => !u.active).length"></span></p>
                    </div>
                </div>
            </div>

            <!-- Users Management View -->
            <div x-show="showUsersManagement" x-transition class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-6 border border-purple-200 dark:border-gray-600 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-purple-700 dark:text-gray-200">
                        <i class="fas fa-users-cog mr-2"></i> User Management
                    </h2>
                    <div class="flex space-x-2">
                        <button
                            @click="openAddUserModal()"
                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm flex items-center"
                        >
                            <i class="fas fa-plus mr-1"></i> Add User
                        </button>
                        <button @click="showUsersManagement = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-blue-100 dark:bg-blue-700 text-blue-700 dark:text-blue-300 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Role</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Last Active</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="user in filteredUsers" :key="user.id">
                                <tr class="border-b border-blue-200 dark:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900 transition">
                                    <td class="px-6 py-4" x-text="user.name"></td>
                                    <td class="px-6 py-4" x-text="user.email"></td>
                                    <td class="px-6 py-4" x-text="user.role || 'user'"></td>
                                    <td class="px-6 py-4">
                                        <span x-text="user.active ? 'Active' : 'Inactive'"
                                              :class="user.active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200'"
                                              class="px-2 py-1 rounded-full text-xs"></span>
                                    </td>
                                    <td class="px-6 py-4" x-text="user.last_active ? formatDate(user.last_active) : 'Never'"></td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <button
                                            @click="toggleUserStatus(user.id)"
                                            :class="user.active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600'"
                                            class="text-white px-3 py-1 rounded text-sm"
                                        >
                                            <template x-if="user.active">Deactivate</template>
                                            <template x-if="!user.active">Activate</template>
                                        </button>
                                        <button
                                            @click="confirmDeleteUser(user.id)"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
                                            :disabled="user.id === currentUser.id"
                                            :class="{'opacity-50 cursor-not-allowed': user.id === currentUser.id}"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- This Month Bookings View -->
            <div x-show="showThisMonthBookings" x-transition class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-6 border border-purple-200 dark:border-gray-600 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-purple-700 dark:text-gray-200">
                        <i class="fas fa-calendar-day mr-2"></i> This Month's Bookings
                    </h2>
                    <button @click="showThisMonthBookings = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-pink-100 dark:bg-pink-700 text-purple-700 dark:text-purple-300 uppercase text-xs">
                            <tr>
                                <th class="py-2 px-3">Title</th>
                                <th class="py-2 px-3">Description</th>
                                <th class="py-2 px-3">Date</th>
                                <th class="py-2 px-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="booking in getBookingsThisMonth()" :key="booking.id">
                                <tr class="border-b border-purple-200 dark:border-gray-600 hover:bg-pink-50 dark:hover:bg-gray-600 transition">
                                    <td class="py-2 px-3 truncate max-w-xs" x-text="booking.title"></td>
                                    <td class="py-2 px-3 truncate max-w-sm" x-text="booking.description || 'N/A'"></td>
                                    <td class="py-2 px-3 whitespace-nowrap" x-text="formatDate(booking.booking_date)"></td>
                                    <td class="py-2 px-3 text-center space-x-2">
                                        <button
                                            @click="openModal('edit', booking)"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-semibold underline"
                                        >
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button
                                            @click="deleteBooking(booking.id)"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 font-semibold underline"
                                        >
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="getBookingsThisMonth().length === 0">
                                <tr>
                                    <td colspan="4" class="text-center py-4 italic text-purple-600 dark:text-purple-300">
                                        No bookings found for this month.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upcoming Bookings View -->
            <div x-show="showUpcomingBookings" x-transition class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-6 border border-purple-200 dark:border-gray-600 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-purple-700 dark:text-gray-200">
                        <i class="fas fa-clock mr-2"></i> Upcoming Bookings
                    </h2>
                    <button @click="showUpcomingBookings = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                        <thead class="bg-pink-100 dark:bg-pink-700 text-purple-700 dark:text-purple-300 uppercase text-xs">
                            <tr>
                                <th class="py-2 px-3">Title</th>
                                <th class="py-2 px-3">Description</th>
                                <th class="py-2 px-3">Date</th>
                                <th class="py-2 px-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="booking in getUpcomingBookings()" :key="booking.id">
                                <tr class="border-b border-purple-200 dark:border-gray-600 hover:bg-pink-50 dark:hover:bg-gray-600 transition">
                                    <td class="py-2 px-3 truncate max-w-xs" x-text="booking.title"></td>
                                    <td class="py-2 px-3 truncate max-w-sm" x-text="booking.description || 'N/A'"></td>
                                    <td class="py-2 px-3 whitespace-nowrap" x-text="formatDate(booking.booking_date)"></td>
                                    <td class="py-2 px-3 text-center space-x-2">
                                        <button
                                            @click="openModal('edit', booking)"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-semibold underline"
                                        >
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>
                                        <button
                                            @click="deleteBooking(booking.id)"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 font-semibold underline"
                                        >
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="getUpcomingBookings().length === 0">
                                <tr>
                                    <td colspan="4" class="text-center py-4 italic text-purple-600 dark:text-purple-300">
                                        No upcoming bookings found.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Settings View -->
            <div x-show="showSettings" x-transition class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-6 border border-purple-200 dark:border-gray-600 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-purple-700 dark:text-gray-200">
                        <i class="fas fa-cog mr-2"></i> Settings
                    </h2>
                    <button @click="showSettings = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-600 rounded-lg">
                        <h3 class="font-semibold mb-2">Appearance</h3>
                        <div class="flex items-center justify-between">
                            <span>Dark Mode</span>
                            <button
                                @click="toggleDarkMode()"
                                class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500"
                                :class="darkMode ? 'bg-pink-600' : 'bg-gray-200'"
                            >
                                <span
                                    class="inline-block w-4 h-4 transform transition rounded-full bg-white"
                                    :class="darkMode ? 'translate-x-6' : 'translate-x-1'"
                                ></span>
                            </button>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-600 rounded-lg">
                        <h3 class="font-semibold mb-2">Data Management</h3>
                        <button @click="exportAllData()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mr-2">
                            <i class="fas fa-file-export mr-1"></i> Export All Data
                        </button>
                        <button @click="confirmResetData()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                            <i class="fas fa-trash mr-1"></i> Reset All Data
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dashboard View -->
            <div x-show="!showCalendar && !showReportsView && !showUsersManagement && !showThisMonthBookings && !showUpcomingBookings && !showSettings"
                 x-transition class="space-y-6">
                <!-- Welcome -->
                <div class="bg-gradient-to-r from-pink-300 via-green-300 to-blue-200 dark:from-pink-700 dark:via-green-700 dark:to-blue-700 shadow-md rounded-xl p-6 border border-pink-300 dark:border-green-700">
                    <h3 class="text-2xl font-bold text-purple-700 dark:text-gray-200">Welcome, <span x-text="currentUser.name"></span>!</h3>
                    <p class="text-purple-600 dark:text-gray-400 mt-2">Here's your dashboard summary:</p>
                </div>

                <!-- Stats cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        @click="showBookingsSection()"
                        class="bg-white dark:bg-gray-700 border-l-4 border-pink-400 dark:border-green-500 shadow-md p-5 rounded-lg cursor-pointer hover:bg-pink-50 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] active:scale-95"
                        title="View your bookings"
                        role="button" tabindex="0"
                    >
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-purple-700 dark:text-gray-200">Total Bookings</h4>
                            <i class="fas fa-calendar-check text-pink-400 dark:text-green-400 text-xl"></i>
                        </div>
                        <div class="flex items-center mt-2">
                            <p class="text-2xl font-bold text-pink-500 dark:text-green-400" x-text="bookings.length"></p>
                        </div>
                    </div>

                    <div
                        @click="showUsersManagement = true"
                        class="bg-white dark:bg-gray-700 border-l-4 border-blue-400 dark:border-blue-500 shadow-md p-5 rounded-lg cursor-pointer hover:bg-blue-50 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] active:scale-95"
                    >
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-purple-700 dark:text-gray-200">Total Users</h4>
                            <i class="fas fa-users text-blue-400 dark:text-blue-400 text-xl"></i>
                        </div>
                        <p class="text-2xl font-bold text-blue-500 dark:text-blue-400" x-text="users.length"></p>
                    </div>

                    <div
                        @click="showThisMonthBookings = true; showUpcomingBookings = false"
                        class="bg-white dark:bg-gray-700 border-l-4 border-purple-400 dark:border-purple-500 shadow-md p-5 rounded-lg cursor-pointer hover:bg-purple-50 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] active:scale-95"
                    >
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-purple-700 dark:text-gray-200">This Month</h4>
                            <i class="fas fa-calendar-day text-purple-400 dark:text-purple-400 text-xl"></i>
                        </div>
                        <p class="text-2xl font-bold text-purple-500 dark:text-purple-400" x-text="getBookingsThisMonth().length"></p>
                    </div>

                    <div
                        @click="showUpcomingBookings = true; showThisMonthBookings = false"
                        class="bg-white dark:bg-gray-700 border-l-4 border-green-400 dark:border-green-500 shadow-md p-5 rounded-lg cursor-pointer hover:bg-green-50 dark:hover:bg-gray-600 transition-all duration-300 transform hover:scale-[1.02] active:scale-95"
                    >
                        <div class="flex items-center justify-between">
                            <h4 class="text-lg font-semibold text-purple-700 dark:text-gray-200">Upcoming</h4>
                            <i class="fas fa-clock text-green-400 dark:text-green-400 text-xl"></i>
                        </div>
                        <p class="text-2xl font-bold text-green-500 dark:text-green-400" x-text="getUpcomingBookings().length"></p>
                    </div>
                </div>

                <!-- Bookings Search & Pagination -->
                <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-6 border border-purple-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
                        <div class="relative flex-1">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-purple-500"></i>
                            <input
                                type="search"
                                placeholder="Search bookings..."
                                class="w-full pl-10 pr-4 py-2 rounded border border-purple-300 dark:border-gray-500 focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 focus:outline-none dark:bg-gray-600 dark:text-gray-100"
                                x-model="search"
                                @input.debounce.300ms="filterBookings()"
                            />
                        </div>
                        <button
                            @click="openModal('add')"
                            class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-4 rounded transition flex items-center justify-center"
                        >
                            <i class="fas fa-plus mr-2"></i> Add Booking
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                            <thead class="bg-pink-100 dark:bg-pink-700 text-purple-700 dark:text-purple-300 uppercase text-xs">
                                <tr>
                                    <th class="py-2 px-3">Title</th>
                                    <th class="py-2 px-3">Description</th>
                                    <th class="py-2 px-3">Date</th>
                                    <th class="py-2 px-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="booking in paginatedBookings()" :key="booking.id">
                                    <tr class="border-b border-purple-200 dark:border-gray-600 hover:bg-pink-50 dark:hover:bg-gray-600 transition">
                                        <td class="py-2 px-3 truncate max-w-xs" x-text="booking.title"></td>
                                        <td class="py-2 px-3 truncate max-w-sm" x-text="booking.description || 'N/A'"></td>
                                        <td class="py-2 px-3 whitespace-nowrap" x-text="formatDate(booking.booking_date)"></td>
                                        <td class="py-2 px-3 text-center space-x-2">
                                            <button
                                                @click="openModal('edit', booking)"
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-semibold underline"
                                            >
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </button>
                                            <button
                                                @click="deleteBooking(booking.id)"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 font-semibold underline"
                                            >
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="filteredBookings.length === 0">
                                    <tr>
                                        <td colspan="4" class="text-center py-4 italic text-purple-600 dark:text-purple-300">
                                            No bookings found.
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Showing <span x-text="(currentPage - 1) * perPage + 1"></span> to
                            <span x-text="Math.min(currentPage * perPage, filteredBookings.length)"></span> of
                            <span x-text="filteredBookings.length"></span> results
                        </div>
                        <div class="flex space-x-2">
                            <button
                                @click="prevPage()"
                                :disabled="currentPage === 1"
                                class="px-3 py-1 rounded border border-purple-300 dark:border-gray-500 disabled:opacity-50 flex items-center"
                            >
                                <i class="fas fa-chevron-left mr-1 text-xs"></i> Prev
                            </button>
                            <template x-for="page in totalPages()" :key="page">
                                <button
                                    @click="goToPage(page)"
                                    :class="{'bg-pink-500 text-white': currentPage === page, 'bg-white dark:bg-gray-600': currentPage !== page}"
                                    class="px-3 py-1 rounded border border-purple-300 dark:border-gray-500"
                                    x-text="page"
                                ></button>
                            </template>
                            <button
                                @click="nextPage()"
                                :disabled="currentPage === totalPages()"
                                class="px-3 py-1 rounded border border-purple-300 dark:border-gray-500 disabled:opacity-50 flex items-center"
                            >
                                Next <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md p-6 border border-purple-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                        <h3 class="text-xl font-semibold text-purple-700 dark:text-gray-200 mb-2 sm:mb-0">
                            <i class="fas fa-chart-bar mr-2"></i>Monthly Bookings Summary
                        </h3>
                        <div class="flex items-center">
                            <label for="year-select" class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">Year:</label>
                            <select
                                id="year-select"
                                x-model="selectedYear"
                                @change="updateChart()"
                                class="bg-white dark:bg-gray-600 border border-purple-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 text-sm rounded focus:ring-pink-500 focus:border-pink-500 block w-full p-1"
                            >
                                <template x-for="year in availableYears" :key="year">
                                    <option x-text="year" :value="year" :selected="year === selectedYear"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="chart-container relative" style="height: 400px;">
                        <canvas id="bookingsChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Booking Modal -->
    <div
        x-show="modalOpen"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40"
        x-cloak
        @keydown.escape.window="closeModal()"
    >
        <div
            x-show="modalOpen"
            x-transition.scale.origin.top.duration.300ms
            @click.away="closeModal()"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4 p-6"
            role="dialog" aria-modal="true" aria-labelledby="modal-title"
        >
            <h2 class="text-xl font-semibold mb-4 text-purple-700 dark:text-purple-300" x-text="modalMode === 'add' ? 'Add Booking' : 'Edit Booking'"></h2>
            <form @submit.prevent="saveBooking">
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Title *</label>
                    <input type="text" x-model="modalBooking.title" required
                        class="w-full px-3 py-2 rounded border border-purple-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    />
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea x-model="modalBooking.description" rows="3"
                        class="w-full px-3 py-2 rounded border border-purple-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    ></textarea>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Date *</label>
                    <input type="datetime-local" x-model="modalBooking.booking_date" required
                        class="w-full px-3 py-2 rounded border border-purple-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    />
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="closeModal()" class="px-4 py-2 rounded border border-purple-300 dark:border-gray-600 text-purple-700 dark:text-purple-300 hover:bg-purple-100 dark:hover:bg-gray-600 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-pink-500 hover:bg-pink-600 text-white font-semibold transition">
                        <template x-if="modalMode === 'add'"><i class="fas fa-plus mr-1"></i> Add</template>
                        <template x-if="modalMode === 'edit'"><i class="fas fa-save mr-1"></i> Save</template>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add User Modal -->
    <div
        x-show="addUserModalOpen"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        x-cloak
        @keydown.escape.window="closeAddUserModal()"
    >
        <div
            x-show="addUserModalOpen"
            x-transition.scale.origin.top.duration.300ms
            @click.away="closeAddUserModal()"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4 p-6"
            role="dialog" aria-modal="true" aria-labelledby="add-user-modal-title"
        >
            <h2 class="text-xl font-semibold mb-4 text-purple-700 dark:text-purple-300">Add New User</h2>
            <form @submit.prevent="addUser">
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Name *</label>
                    <input type="text" x-model="newUser.name" required
                        class="w-full px-3 py-2 rounded border border-purple-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    />
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email *</label>
                    <input type="email" x-model="newUser.email" required
                        class="w-full px-3 py-2 rounded border border-purple-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    />
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Role *</label>
                    <select x-model="newUser.role" required
                        class="w-full px-3 py-2 rounded border border-purple-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    >
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password *</label>
                    <input type="password" x-model="newUser.password" required
                        class="w-full px-3 py-2 rounded border border-purple-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                    />
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="closeAddUserModal()" class="px-4 py-2 rounded border border-purple-300 dark:border-gray-600 text-purple-700 dark:text-purple-300 hover:bg-purple-100 dark:hover:bg-gray-600 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-white font-semibold transition">
                        <i class="fas fa-user-plus mr-1"></i> Add User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete User Confirmation Modal -->
    <div
        x-show="deleteUserModalOpen"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        x-cloak
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirm User Deletion</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button
                    @click="deleteUserModalOpen = false"
                    class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                >
                    Cancel
                </button>
                <button
                    @click="deleteUser(selectedUserId)"
                    class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white font-semibold transition"
                >
                    Delete User
                </button>
            </div>
        </div>
    </div>

    <!-- Reset Data Confirmation Modal -->
    <div
        x-show="resetDataModalOpen"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        x-cloak
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirm Data Reset</h3>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Are you sure you want to reset all data? This will delete all bookings and users except your account. This action cannot be undone.</p>
            <div class="flex justify-end space-x-3">
                <button
                    @click="resetDataModalOpen = false"
                    class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                >
                    Cancel
                </button>
                <button
                    @click="resetAllData()"
                    class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white font-semibold transition"
                >
                    Reset All Data
                </button>
            </div>
        </div>
    </div>

    <script>
        function dashboardData() {
            return {
                sidebarOpen: false,
                darkMode: false,
                showCalendar: false,
                showReportsView: false,
                showUsersManagement: false,
                showThisMonthBookings: false,
                showUpcomingBookings: false,
                showSettings: false,
                showBookingSubmenu: false,
                bookings: [],
                users: @json($users ?? []),
                currentUser: @json(Auth::user()),
                filteredBookings: [],
                filteredUsers: [],
                search: '',
                currentPage: 1,
                perPage: 5,
                modalOpen: false,
                modalMode: 'add',
                modalBooking: { id: null, title: '', description: '', booking_date: '' },
                addUserModalOpen: false,
                newUser: { name: '', email: '', role: 'user', password: '', active: true },
                totalBookings: 0,
                loadingBookings: false,
                chart: null,
                selectedYear: new Date().getFullYear(),
                availableYears: [],
                monthlyData: { labels: [], counts: [] },
                showSuccess: false,
                successMessage: '',
                showError: false,
                errorMessage: '',
                calendarDays: [],
                deleteUserModalOpen: false,
                resetDataModalOpen: false,
                selectedUserId: null,
                calendarMonth: new Date().getMonth(),
                calendarYear: new Date().getFullYear(),
                calendarYears: Array.from({length: 10}, (_, i) => new Date().getFullYear() - 5 + i),

                init() {
                    this.loadDarkMode();
                    this.loadBookings();
                    this.loadUsers();
                    this.initializeYears();
                    this.generateCalendarDays();
                    this.$nextTick(() => {
                        this.prepareMonthlyData();
                        this.renderChart();
                    });

                    // Set current user as active
                    this.updateCurrentUserActivity();

                    // Set up event listeners for real-time updates
                    this.setupEventListeners();
                },

                updateCurrentUserActivity() {
                    const userIndex = this.users.findIndex(u => u.id === this.currentUser.id);
                    if (userIndex !== -1) {
                        this.users[userIndex].active = true;
                        this.users[userIndex].last_active = new Date().toISOString();
                        this.saveUsers();
                    }
                },

                loadBookings() {
                    // Load from localStorage or server data
                    const storedBookings = localStorage.getItem('bookings');
                    if (storedBookings) {
                        this.bookings = JSON.parse(storedBookings);
                    } else {
                        this.bookings = @json($bookings ?? []);
                    }
                    this.filteredBookings = [...this.bookings];
                    this.totalBookings = this.bookings.length;
                },

                loadUsers() {
                    // Load users from localStorage or server data
                    const storedUsers = localStorage.getItem('users');
                    if (storedUsers) {
                        this.users = JSON.parse(storedUsers);
                    } else {
                        this.users = @json($users ?? []);
                    }

                    // Filter out deleted users
                    const deletedUsers = JSON.parse(localStorage.getItem('deletedUsers') || '[]');
                    this.users = this.users.filter(user => !deletedUsers.includes(user.id));

                    // Ensure current user exists
                    if (!this.users.some(u => u.id === this.currentUser.id)) {
                        this.users.push(this.currentUser);
                    }

                    this.filteredUsers = [...this.users];
                    this.saveUsers();
                },

                saveBookings() {
                    localStorage.setItem('bookings', JSON.stringify(this.bookings));
                },

                saveUsers() {
                    localStorage.setItem('users', JSON.stringify(this.users));
                },

                initializeYears() {
                    // Get unique years from bookings
                    const years = new Set();
                    const currentYear = new Date().getFullYear();
                    years.add(currentYear);

                    this.bookings.forEach(booking => {
                        try {
                            if (booking.booking_date) {
                                const date = new Date(booking.booking_date);
                                if (!isNaN(date)) {
                                    years.add(date.getFullYear());
                                }
                            }
                        } catch (e) {
                            console.error('Error processing booking date:', e);
                        }
                    });

                    // Convert to array and sort descending
                    this.availableYears = Array.from(years).sort((a, b) => b - a);

                    // Set selected year to current year if available
                    if (this.availableYears.includes(currentYear)) {
                        this.selectedYear = currentYear;
                    } else if (this.availableYears.length > 0) {
                        this.selectedYear = this.availableYears[0];
                    }
                },

                prepareMonthlyData() {
                    const counts = {};
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                    // Initialize counts for all months
                    for (let i = 0; i < 12; i++) {
                        counts[i] = 0;
                    }

                    // Count bookings for each month of selected year
                    this.bookings.forEach(booking => {
                        try {
                            if (booking.booking_date) {
                                const date = new Date(booking.booking_date);
                                if (!isNaN(date) && date.getFullYear() === this.selectedYear) {
                                    counts[date.getMonth()] = (counts[date.getMonth()] || 0) + 1;
                                }
                            }
                        } catch (e) {
                            console.error('Error processing booking date:', e);
                        }
                    });

                    this.monthlyData = {
                        labels: months,
                        counts: months.map((_, i) => counts[i] || 0)
                    };
                },

                generateCalendarDays() {
                    const year = this.calendarYear;
                    const month = parseInt(this.calendarMonth);

                    // Get first day of month and last day of month
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);

                    // Get days from previous month to show
                    const prevMonthDays = firstDay.getDay(); // 0 = Sunday, 6 = Saturday

                    // Get days from next month to show
                    const nextMonthDays = 6 - lastDay.getDay(); // Days needed to complete the last week

                    // Calculate total days to show (usually 42 = 6 weeks)
                    const totalDays = prevMonthDays + lastDay.getDate() + nextMonthDays;

                    const days = [];

                    // Previous month days
                    const prevMonthLastDay = new Date(year, month, 0).getDate();
                    for (let i = prevMonthDays - 1; i >= 0; i--) {
                        const day = new Date(year, month - 1, prevMonthLastDay - i);
                        days.push({
                            date: day,
                            currentMonth: false
                        });
                    }

                    // Current month days
                    for (let i = 1; i <= lastDay.getDate(); i++) {
                        const day = new Date(year, month, i);
                        days.push({
                            date: day,
                            currentMonth: true
                        });
                    }

                    // Next month days
                    for (let i = 1; i <= nextMonthDays; i++) {
                        const day = new Date(year, month + 1, i);
                        days.push({
                            date: day,
                            currentMonth: false
                        });
                    }

                    this.calendarDays = days;
                },

                getEventsForDay(day) {
                    return this.bookings.filter(booking => {
                        const bookingDate = new Date(booking.booking_date);
                        return bookingDate.toDateString() === day.toDateString();
                    });
                },

                getBookingsThisMonth() {
                    const today = new Date();
                    const currentMonth = today.getMonth();
                    const currentYear = today.getFullYear();

                    return this.bookings.filter(booking => {
                        const bookingDate = new Date(booking.booking_date);
                        return bookingDate.getMonth() === currentMonth &&
                               bookingDate.getFullYear() === currentYear;
                    });
                },

                getUpcomingBookings() {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    return this.bookings.filter(booking => {
                        const bookingDate = new Date(booking.booking_date);
                        return bookingDate >= today;
                    });
                },

                showBookingsSection() {
                    this.showThisMonthBookings = true;
                    this.showUpcomingBookings = false;
                    this.showBookingSubmenu = !this.showBookingSubmenu;
                },

                showCalendarView() {
                    this.showCalendar = true;
                    this.showReportsView = false;
                    this.showUsersManagement = false;
                    this.showThisMonthBookings = false;
                    this.showUpcomingBookings = false;
                    this.showSettings = false;
                    this.generateCalendarDays();
                },

                showReports() {
                    this.showReportsView = true;
                    this.showCalendar = false;
                    this.showUsersManagement = false;
                    this.showThisMonthBookings = false;
                    this.showUpcomingBookings = false;
                    this.showSettings = false;
                },

                openAddUserModal() {
                    this.newUser = {
                        name: '',
                        email: '',
                        role: 'user',
                        password: '',
                        active: true,
                        id: Date.now() // Generate a temporary ID
                    };
                    this.addUserModalOpen = true;
                },

                closeAddUserModal() {
                    this.addUserModalOpen = false;
                },

                addUser() {
                    // Basic validation
                    if (!this.newUser.name || !this.newUser.email || !this.newUser.password) {
                        this.showErrorMessage('Please fill all required fields');
                        return;
                    }

                    // Check if email already exists
                    if (this.users.some(u => u.email === this.newUser.email)) {
                        this.showErrorMessage('A user with this email already exists');
                        return;
                    }

                    // Add the new user
                    this.users.push({
                        id: this.newUser.id,
                        name: this.newUser.name,
                        email: this.newUser.email,
                        role: this.newUser.role,
                        active: this.newUser.active,
                        last_active: this.newUser.active ? new Date().toISOString() : null,
                        created_at: new Date().toISOString()
                    });

                    // Save and update UI
                    this.saveUsers();
                    this.filteredUsers = [...this.users];
                    this.closeAddUserModal();
                    this.showSuccessMessage('User added successfully!');
                },

                exportAllData() {
                    const data = {
                        bookings: this.bookings,
                        users: this.users,
                        currentYear: this.selectedYear
                    };

                    const content = JSON.stringify(data, null, 2);
                    const blob = new Blob([content], { type: 'application/json' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `booking-system-backup-${new Date().toISOString().split('T')[0]}.json`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);

                    this.showSuccessMessage('All data exported successfully!');
                },

                confirmResetData() {
                    this.resetDataModalOpen = true;
                },

                resetAllData() {
                    // Reset bookings
                    this.bookings = [];
                    this.filteredBookings = [];
                    this.saveBookings();

                    // Reset users but keep current user
                    this.users = [this.currentUser];
                    this.filteredUsers = [this.currentUser];
                    localStorage.setItem('deletedUsers', JSON.stringify([]));
                    this.saveUsers();

                    // Reset years
                    this.selectedYear = new Date().getFullYear();
                    this.availableYears = [this.selectedYear];

                    // Update UI
                    this.prepareMonthlyData();
                    this.renderChart();
                    this.generateCalendarDays();

                    this.resetDataModalOpen = false;
                    this.showSuccessMessage('All data has been reset successfully!');
                },

                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                    if (this.chart) {
                        this.chart.destroy();
                        this.renderChart();
                    }
                },

                loadDarkMode() {
                    const savedMode = localStorage.getItem('darkMode');
                    if (savedMode !== null) {
                        this.darkMode = savedMode === 'true';
                    } else {
                        this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }
                },

                filterBookings() {
                    const term = this.search.trim().toLowerCase();
                    this.filteredBookings = this.bookings.filter(b => {
                        return (b.title && b.title.toLowerCase().includes(term)) ||
                               (b.description && b.description.toLowerCase().includes(term));
                    });
                    this.currentPage = 1;
                },

                paginatedBookings() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredBookings.slice(start, start + this.perPage);
                },

                totalPages() {
                    return Math.max(1, Math.ceil(this.filteredBookings.length / this.perPage));
                },

                nextPage() {
                    if (this.currentPage < this.totalPages()) {
                        this.currentPage++;
                    }
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                    }
                },

                goToPage(page) {
                    if (page >= 1 && page <= this.totalPages()) {
                        this.currentPage = page;
                    }
                },

                openModal(mode, booking = null) {
                    this.modalMode = mode;
                    if (mode === 'edit' && booking) {
                        this.modalBooking = {...booking};
                        // Format date for datetime-local input
                        if (this.modalBooking.booking_date) {
                            const date = new Date(this.modalBooking.booking_date);
                            if (!isNaN(date)) {
                                const pad = num => num.toString().padStart(2, '0');
                                this.modalBooking.booking_date = `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
                            }
                        }
                    } else {
                        // Set default date/time for new booking (now + 1 hour)
                        const now = new Date();
                        now.setHours(now.getHours() + 1);
                        const pad = num => num.toString().padStart(2, '0');
                        this.modalBooking = {
                            id: Date.now(),
                            title: '',
                            description: '',
                            booking_date: `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`
                        };
                    }
                    this.modalOpen = true;
                },

                closeModal() {
                    this.modalOpen = false;
                },

                saveBooking() {
                    const booking = {...this.modalBooking};

                    // Format date for storage (YYYY-MM-DD HH:MM:SS)
                    if (booking.booking_date) {
                        const date = new Date(booking.booking_date);
                        if (!isNaN(date)) {
                            const pad = num => num.toString().padStart(2, '0');
                            booking.booking_date = `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:00`;

                            // Add the year to availableYears if it's new
                            const year = date.getFullYear();
                            if (!this.availableYears.includes(year)) {
                                this.availableYears.push(year);
                                this.availableYears.sort((a, b) => b - a);
                            }
                        }
                    }

                    // Add or update booking
                    if (this.modalMode === 'add') {
                        this.bookings.push(booking);
                        this.showSuccessMessage('Booking added successfully!');
                    } else {
                        const idx = this.bookings.findIndex(b => b.id === booking.id);
                        if (idx !== -1) this.bookings.splice(idx, 1, booking);
                        this.showSuccessMessage('Booking updated successfully!');
                    }

                    // Update UI and save to localStorage
                    this.filterBookings();
                    this.totalBookings = this.bookings.length;
                    this.saveBookings();
                    this.prepareMonthlyData();
                    this.renderChart();
                    this.generateCalendarDays();
                    this.closeModal();
                },

                showSuccessMessage(message) {
                    this.successMessage = message;
                    this.showSuccess = true;
                    setTimeout(() => {
                        this.showSuccess = false;
                    }, 3000);
                },

                showErrorMessage(message) {
                    this.errorMessage = message;
                    this.showError = true;
                    setTimeout(() => {
                        this.showError = false;
                    }, 3000);
                },

                deleteBooking(id) {
                    if (!confirm('Are you sure you want to delete this booking?')) return;
                    this.bookings = this.bookings.filter(b => b.id !== id);
                    this.filterBookings();
                    this.totalBookings = this.bookings.length;
                    this.saveBookings();
                    this.prepareMonthlyData();
                    this.renderChart();
                    this.generateCalendarDays();
                    this.showSuccessMessage('Booking deleted successfully!');
                },

                confirmDeleteUser(userId) {
                    this.selectedUserId = userId;
                    this.deleteUserModalOpen = true;
                },

                deleteUser(userId) {
                    // Don't allow deleting current user
                    if (userId === this.currentUser.id) {
                        this.showErrorMessage('You cannot delete your own account');
                        return;
                    }

                    // Store deleted user ID in localStorage to persist across refreshes
                    const deletedUsers = JSON.parse(localStorage.getItem('deletedUsers') || '[]');
                    if (!deletedUsers.includes(userId)) {
                        deletedUsers.push(userId);
                        localStorage.setItem('deletedUsers', JSON.stringify(deletedUsers));
                    }

                    // Remove from current users list
                    this.users = this.users.filter(u => u.id !== userId);
                    this.filteredUsers = this.filteredUsers.filter(u => u.id !== userId);
                    this.saveUsers();

                    this.deleteUserModalOpen = false;
                    this.showSuccessMessage('User deleted successfully!');
                },

                toggleUserStatus(userId) {
                    const user = this.users.find(u => u.id === userId);
                    if (user) {
                        user.active = !user.active;
                        user.last_active = user.active ? new Date().toISOString() : null;
                        this.saveUsers();
                        this.showSuccessMessage(`User ${user.active ? 'activated' : 'deactivated'} successfully!`);
                    }
                },

                formatDate(dateStr) {
                    if (!dateStr) return 'N/A';
                    try {
                        const d = new Date(dateStr);
                        if (isNaN(d)) return 'Invalid date';
                        return d.toLocaleDateString(undefined, {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    } catch (e) {
                        return 'Invalid date';
                    }
                },

                renderChart() {
                    const ctx = document.getElementById('bookingsChart');
                    if (!ctx) return;

                    // Destroy previous chart if exists
                    if (this.chart) {
                        this.chart.destroy();
                    }

                    const isDark = this.darkMode;
                    const textColor = isDark ? '#f3f4f6' : '#111827';
                    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                    const maxCount = Math.max(...this.monthlyData.counts, 1);

                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: this.monthlyData.labels,
                            datasets: [{
                                label: 'Bookings',
                                data: this.monthlyData.counts,
                                backgroundColor: this.monthlyData.counts.map(count => {
                                    const isPeak = count === maxCount && count > 0;
                                    return isPeak
                                        ? (isDark ? 'rgba(74, 222, 128, 0.7)' : 'rgba(74, 222, 128, 0.5)') // Green for peak
                                        : (isDark ? 'rgba(236, 72, 153, 0.7)' : 'rgba(236, 72, 153, 0.5)'); // Pink for normal
                                }),
                                borderColor: isDark ? 'rgba(236, 72, 153, 1)' : 'rgba(236, 72, 153, 1)',
                                borderWidth: 1,
                                borderRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: (context) => {
                                            const count = context.raw;
                                            return `${count} booking${count !== 1 ? 's' : ''}`;
                                        },
                                        title: (context) => {
                                            return `${context[0].label} ${this.selectedYear}`;
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: `Monthly Bookings ${this.selectedYear}`,
                                    color: textColor,
                                    font: {
                                        size: 16
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        precision: 0,
                                        color: textColor
                                    },
                                    grid: {
                                        color: gridColor
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: textColor
                                    },
                                    grid: {
                                        color: gridColor,
                                        display: false
                                    }
                                }
                            },
                            animation: {
                                duration: 500,
                                easing: 'easeOutQuart'
                            }
                        }
                    });
                },

                updateChart() {
                    this.prepareMonthlyData();
                    this.renderChart();
                },

                setupEventListeners() {
                    // Listen for storage changes to sync data across tabs
                    window.addEventListener('storage', (event) => {
                        if (event.key === 'bookings') {
                            this.bookings = JSON.parse(event.newValue);
                            this.filterBookings();
                            this.prepareMonthlyData();
                            this.renderChart();
                        }
                        if (event.key === 'users') {
                            this.loadUsers();
                        }
                    });

                    // Update user activity every minute
                    setInterval(() => {
                        this.updateCurrentUserActivity();
                    }, 60000);
                }
            };
        }
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        [x-cloak] { display: none !important; }

        .chart-container {
            position: relative;
            width: 100%;
            min-height: 300px;
        }

        @media (max-width: 640px) {
            .chart-container {
                min-height: 250px;
            }
        }

        .dark .bg-white {
            background-color: #1f2937;
        }
        .dark .text-gray-900 {
            color: #f3f4f6;
        }
        .dark .border-purple-200 {
            border-color: #4c1d95;
        }
    </style>
</div>
@endsection
