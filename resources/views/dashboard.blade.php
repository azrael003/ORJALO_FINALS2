@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div
  x-data="dashboard()"
  x-init="init()"
  :class="{ 'dark': darkMode }"
  class="min-h-screen flex bg-gradient-to-tr from-pink-200 via-green-200 to-blue-200 dark:from-pink-700 dark:via-green-700 dark:to-blue-700 text-gray-900 dark:text-gray-100"
>
  <!-- Sidebar -->
  <aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-pink-400 via-green-400 to-blue-500 dark:from-pink-800 dark:via-green-800 dark:to-blue-800 shadow-xl transform transition-transform duration-300 ease-in-out z-40 md:relative md:translate-x-0 flex flex-col"
  >
    <div class="h-16 flex items-center justify-center border-b border-white/30">
      <h1 class="text-3xl font-extrabold tracking-wide flex items-center gap-2 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 12l2-2m0 0l7-7 7 7m-9 5v6m0-6h6" />
        </svg>
        Dashboard
      </h1>
    </div>

    <nav class="flex-grow px-5 py-6 space-y-5 overflow-y-auto">
      <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-white hover:text-pink-200 transition rounded-lg px-3 py-2 font-semibold focus:outline-none focus:ring-2 focus:ring-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 12l2-2m0 0l7-7 7 7m-9 5v6m0-6h6" />
        </svg>
        Home
      </a>

      <a href="{{ route('users.index') }}" class="flex items-center gap-3 text-white hover:text-pink-200 transition rounded-lg px-3 py-2 font-semibold focus:outline-none focus:ring-2 focus:ring-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5.121 17.804A7 7 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        Users
      </a>

      <button
        @click="showBookings = !showBookings"
        aria-expanded="showBookings.toString()"
        aria-controls="bookings-list"
        class="flex items-center gap-3 text-white hover:text-pink-200 transition rounded-lg px-3 py-2 w-full font-semibold focus:outline-none focus:ring-2 focus:ring-white"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
          <line x1="16" y1="2" x2="16" y2="6" />
          <line x1="8" y1="2" x2="8" y2="6" />
          <line x1="3" y1="10" x2="21" y2="10" />
        </svg>
        Bookings
        <svg :class="showBookings ? 'rotate-90' : ''" class="h-4 w-4 ml-auto transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="9 6 15 12 9 18"></polyline>
        </svg>
      </button>

      <template x-if="showBookings">
        <div id="bookings-list" class="mt-2 space-y-1 max-h-56 overflow-y-auto px-2">
          <template x-if="filteredBookings.length > 0">
            <template x-for="booking in pagedBookings" :key="booking.id">
              <button
                @click="openEditForm(booking)"
                type="button"
                :title="booking.title"
                class="block w-full text-left text-white truncate rounded-md py-1 px-2 hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white"
                x-text="booking.title"
              ></button>
            </template>
          </template>
          <template x-if="filteredBookings.length === 0">
            <p class="text-sm italic text-white/70 px-2">No bookings</p>
          </template>

          <!-- Pagination -->
          <div class="flex justify-between items-center text-xs mt-3 px-2 select-none text-white/90">
            <button
              @click="changePage(currentPage - 1)"
              :disabled="currentPage <= 1"
              class="px-2 py-1 rounded bg-white/20 hover:bg-white/30 disabled:opacity-50 transition"
            >Prev</button>
            <span>Page <span x-text="currentPage"></span> / <span x-text="totalPages"></span></span>
            <button
              @click="changePage(currentPage + 1)"
              :disabled="currentPage >= totalPages"
              class="px-2 py-1 rounded bg-white/20 hover:bg-white/30 disabled:opacity-50 transition"
            >Next</button>
          </div>
        </div>
      </template>

    </nav>

    <div class="p-5 border-t border-white/30">
      <button
        @click="toggleDarkMode()"
        class="w-full py-3 rounded-lg bg-white/20 hover:bg-white/30 transition text-white font-semibold focus:outline-none focus:ring-2 focus:ring-white"
        aria-label="Toggle dark mode"
      >
        <template x-if="!darkMode">🌙 Dark Mode</template>
        <template x-if="darkMode">☀️ Light Mode</template>
      </button>
    </div>
  </aside>

  <!-- Main content -->
  <div class="flex flex-col flex-1 min-h-screen md:ml-64">

    <!-- Topbar -->
    <header class="flex items-center justify-between px-6 py-3 bg-white dark:bg-gray-900 shadow-md md:hidden">
      <button
        @click="toggleSidebar()"
        aria-label="Toggle sidebar"
        class="text-pink-600 dark:text-pink-400 focus:outline-none focus:ring-2 focus:ring-pink-500 rounded"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <h1 class="font-bold text-xl text-gray-900 dark:text-gray-100">Dashboard</h1>
      <div></div>
    </header>

    <!-- Overlay -->
    <div
      x-show="sidebarOpen"
      @click="sidebarOpen = false"
      x-transition.opacity
      class="fixed inset-0 bg-black opacity-50 z-30 md:hidden"
      style="display:none"
      aria-hidden="true"
    ></div>

    <!-- Main content area -->
    <main class="flex-1 overflow-auto p-8 md:p-12 bg-white dark:bg-gray-900">
      <!-- Welcome & Summary Section -->
      <section class="max-w-6xl mx-auto mb-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

          <!-- Welcome Card -->
          <div class="md:col-span-3 bg-gradient-to-tr from-pink-300 via-green-200 to-blue-300 dark:from-pink-800 dark:via-green-700 dark:to-blue-700 rounded-lg shadow p-6 text-center">
            <h2 class="text-2xl font-bold mb-2 text-gray-800 dark:text-white">
              Welcome, <span class="text-pink-700 dark:text-pink-300">{{ Auth::user()->name }}</span>!
            </h2>
            <p class="text-gray-600 dark:text-gray-300">Here's your dashboard summary:</p>
          </div>

          <!-- Total Bookings Card -->
          <div
            @click="sidebarOpen = true; showBookings = true"
            class="cursor-pointer bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow hover:shadow-lg transition flex items-center justify-between"
          >
            <div>
              <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Total Bookings</h3>
              <p class="text-3xl font-bold text-pink-600 dark:text-pink-300" x-text="bookings.length">0</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-500 dark:text-pink-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-9 4h4m1 4h2a2 2 0 002-2V7a2 2 0 00-2-2h-2.586a1 1 0 00-.707.293L12 7l-2.707-2.707A1 1 0 008.586 4H6a2 2 0 00-2 2v10a2 2 0 002 2h2" />
            </svg>
          </div>

          <!-- Total Users Card -->
          <div
            @click="window.location.href='{{ route('users.index') }}'"
            class="cursor-pointer bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow hover:shadow-lg transition flex items-center justify-between"
          >
            <div>
              <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Total Users</h3>
              <p class="text-3xl font-bold text-purple-600 dark:text-purple-300">{{ $usersCount ?? 0 }}</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
        </div>
      </section>

      <!-- Controls -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-10 max-w-6xl mx-auto">
        <div class="flex-1 max-w-lg">
          <label for="search" class="block mb-1 font-semibold">Search Bookings</label>
          <input
            id="search"
            type="text"
            x-model.debounce.300="searchQuery"
            placeholder="Search by title..."
            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-800 dark:text-gray-100"
          />
        </div>

        <button
          @click="openAddForm()"
          class="bg-pink-600 hover:bg-pink-700 focus:ring-pink-500 focus:ring-2 text-white rounded-lg px-6 py-3 font-semibold shadow transition flex items-center gap-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 4v16m8-8H4" />
          </svg>
          Add Booking
        </button>
      </div>

      <!-- Bookings Table -->
      <section class="max-w-6xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-pink-600 dark:bg-pink-700">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-white font-semibold text-sm">ID</th>
              <th scope="col" class="px-6 py-3 text-left text-white font-semibold text-sm">Title</th>
              <th scope="col" class="px-6 py-3 text-left text-white font-semibold text-sm">Booking Date</th>
              <th scope="col" class="px-6 py-3 text-center text-white font-semibold text-sm">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <template x-for="booking in pagedBookings" :key="booking.id">
              <tr class="hover:bg-pink-50 dark:hover:bg-pink-900 transition cursor-pointer" @click="openEditForm(booking)">
                <td class="px-6 py-3 whitespace-nowrap text-sm" x-text="booking.id"></td>
                <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold" x-text="booking.title"></td>
                <td class="px-6 py-3 whitespace-nowrap text-sm" x-text="new Date(booking.booking_date).toLocaleDateString()"></td>
                <td class="px-6 py-3 whitespace-nowrap text-center text-sm">
                  <button
                    @click.stop="deleteBooking(booking.id)"
                    class="text-red-600 hover:text-red-800 focus:outline-none focus:ring-2 focus:ring-red-600 rounded"
                    title="Delete booking"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </td>
              </tr>
            </template>

            <template x-if="pagedBookings.length === 0">
              <tr>
                <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400 italic">No bookings found.</td>
              </tr>
            </template>
          </tbody>
        </table>
      </section>

      <!-- Pagination Controls -->
      <div class="max-w-6xl mx-auto mt-4 flex justify-between items-center text-gray-700 dark:text-gray-300 text-sm select-none">
        <button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage <= 1"
          class="px-4 py-2 rounded bg-pink-600 text-white hover:bg-pink-700 disabled:opacity-50 transition"
        >Previous</button>
        <span>Page <span x-text="currentPage"></span> / <span x-text="totalPages"></span></span>
        <button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage >= totalPages"
          class="px-4 py-2 rounded bg-pink-600 text-white hover:bg-pink-700 disabled:opacity-50 transition"
        >Next</button>
      </div>

      <!-- Chart -->
      <section class="max-w-6xl mx-auto mt-12 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Monthly Bookings</h2>
        <canvas id="monthlyBookingsChart" class="max-w-full h-64"></canvas>
      </section>

      <!-- Booking Form Modal -->
      <div
        x-show="formOpen"
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        aria-modal="true"
        role="dialog"
        style="display: none;"
      >
        <div
          @click.away="closeForm()"
          class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-8 max-w-md w-full relative"
        >
          <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100" x-text="editingBooking ? 'Edit Booking' : 'Add Booking'"></h3>

          <form @submit.prevent="submitForm" class="space-y-5">
            <div>
              <label for="title" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">Title</label>
              <input
                id="title"
                type="text"
                x-model="form.title"
                required
                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-800 dark:text-gray-100"
              />
            </div>

            <div>
              <label for="booking_date" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">Booking Date</label>
              <input
                id="booking_date"
                type="date"
                x-model="form.booking_date"
                required
                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-800 dark:text-gray-100"
              />
            </div>

            <div class="flex justify-end items-center gap-4">
              <button
                type="button"
                @click="closeForm()"
                class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold transition"
              >Cancel</button>

              <button
                type="submit"
                :disabled="loading"
                class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg font-semibold shadow transition disabled:opacity-50"
              >
                <template x-if="loading">
                  <svg class="animate-spin h-5 w-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                  </svg>
                </template>
                <span x-text="editingBooking ? 'Update' : 'Add'"></span>
              </button>
            </div>
          </form>

        </div>
      </div>
    </main>
  </div>

  <script>
    function dashboard() {
      return {
        sidebarOpen: false,
        darkMode: localStorage.getItem('darkMode') === 'true' || false,
        showBookings: true,

        bookings: @json($bookings ?? []),
        searchQuery: '',
        filteredBookings: [],
        pagedBookings: [],
        currentPage: 1,
        perPage: 20,
        totalPages: 1,

        formOpen: false,
        editingBooking: null,
        loading: false,
        form: { id: null, title: '', booking_date: '' },

        monthlyData: { labels: [], counts: [] },
        chart: null,

        init() {
          this.filterAndPage();
          this.prepareMonthlyData();
          this.renderChart();

          // Apply dark mode class if needed
          if (this.darkMode) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
        },

        toggleSidebar() {
          this.sidebarOpen = !this.sidebarOpen;
        },

        toggleDarkMode() {
          this.darkMode = !this.darkMode;
          localStorage.setItem('darkMode', this.darkMode);

          // Apply the dark mode class to the HTML element
          if (this.darkMode) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
        },

        filterAndPage() {
          const q = this.searchQuery.trim().toLowerCase();
          if (q) {
            this.filteredBookings = this.bookings.filter(b => b.title.toLowerCase().includes(q));
          } else {
            this.filteredBookings = [...this.bookings];
          }

          this.totalPages = Math.ceil(this.filteredBookings.length / this.perPage);
          if (this.currentPage > this.totalPages) this.currentPage = this.totalPages || 1;
          this.pagedBookings = this.filteredBookings.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
        },

        changePage(page) {
          if (page < 1 || page > this.totalPages) return;
          this.currentPage = page;
          this.filterAndPage();
        },

        openAddForm() {
          this.editingBooking = null;
          this.form = { id: null, title: '', booking_date: '' };
          this.formOpen = true;
        },

        openEditForm(booking) {
          this.editingBooking = booking.id;
          this.form = { id: booking.id, title: booking.title, booking_date: booking.booking_date };
          this.formOpen = true;
        },

        closeForm() {
          this.formOpen = false;
          this.editingBooking = null;
          this.loading = false;
          this.form = { id: null, title: '', booking_date: '' };
        },

        async submitForm() {
          this.loading = true;
          try {
            await new Promise(resolve => setTimeout(resolve, 800));

            if (this.editingBooking !== null) {
              const idx = this.bookings.findIndex(b => b.id === this.form.id);
              if (idx !== -1) {
                this.bookings[idx].title = this.form.title;
                this.bookings[idx].booking_date = this.form.booking_date;
              }
            } else {
              const newId = this.bookings.length ? Math.max(...this.bookings.map(b => b.id)) + 1 : 1;
              this.bookings.push({
                id: newId,
                title: this.form.title,
                booking_date: this.form.booking_date
              });
            }

            this.filterAndPage();
            this.prepareMonthlyData();
            this.renderChart();
            this.closeForm();

          } catch (error) {
            alert('Error saving booking');
          } finally {
            this.loading = false;
          }
        },

        async deleteBooking(id) {
          if (!confirm('Are you sure you want to delete this booking?')) return;
          this.loading = true;

          try {
            await new Promise(resolve => setTimeout(resolve, 500));
            this.bookings = this.bookings.filter(b => b.id !== id);
            this.filterAndPage();
            this.prepareMonthlyData();
            this.renderChart();
          } catch (error) {
            alert('Error deleting booking');
          } finally {
            this.loading = false;
          }
        },

        prepareMonthlyData() {
          const counts = {};
          const now = new Date();
          const currentYear = now.getFullYear();

          this.monthlyData.labels = Array.from({ length: 12 }, (_, i) => new Date(currentYear, i).toLocaleString('default', { month: 'short' }));

          for (let i = 1; i <= 12; i++) {
            counts[i] = 0;
          }

          this.bookings.forEach(b => {
            const d = new Date(b.booking_date);
            if (d.getFullYear() === currentYear) {
              counts[d.getMonth() + 1] = (counts[d.getMonth() + 1] || 0) + 1;
            }
          });

          this.monthlyData.counts = this.monthlyData.labels.map((_, i) => counts[i + 1] || 0);
        },

        renderChart() {
          const ctx = document.getElementById('monthlyBookingsChart').getContext('2d');
          if (this.chart) this.chart.destroy();

          this.chart = new Chart(ctx, {
            type: 'bar',
            data: {
              labels: this.monthlyData.labels,
              datasets: [{
                label: 'Bookings',
                data: this.monthlyData.counts,
                backgroundColor: 'rgba(219, 39, 119, 0.7)',
                borderRadius: 4,
                maxBarThickness: 40,
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  stepSize: 1,
                  ticks: { precision: 0 }
                }
              }
            }
          });
        }
      }
    }
  </script>
</div>
@endsection
