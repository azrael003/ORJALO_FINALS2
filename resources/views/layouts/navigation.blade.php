<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo Removed -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <!-- Logo was here, now removed -->
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')">
                        {{ __('Booking') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4 ml-auto">
                <!-- Notification Bell -->
                <div class="relative">
                    <button id="notifToggle" class="relative text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(auth()->user()->unreadNotifications->count())
                            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full animate-ping"></span>
                            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div id="notifDropdown"
                        class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded shadow-lg z-50">
                        <div class="p-4 font-semibold border-b">Notifications</div>
                        <ul class="max-h-64 overflow-y-auto">
                            @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                <li class="px-4 py-2 text-sm text-gray-700 border-b hover:bg-gray-100">
                                    {{ $notification->data['message'] ?? 'New Notification' }}
                                </li>
                            @empty
                                <li class="px-4 py-2 text-sm text-gray-500">No new notifications</li>
                            @endforelse
                        </ul>
                        @if(auth()->user()->unreadNotifications->count())
                            <form method="POST" action="{{ route('notifications.markAllRead') }}" class="p-2 text-center">
                                @csrf
                                <button type="submit" class="text-blue-500 text-sm hover:underline">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>

    <!-- Dropdown Toggle Script -->
    <script>
        document.getElementById('notifToggle')?.addEventListener('click', function () {
            document.getElementById('notifDropdown')?.classList.toggle('hidden');
        });
    </script>
</nav>
