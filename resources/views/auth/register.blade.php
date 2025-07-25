<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: false }" :class="darkMode ? 'dark' : ''">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>

  <!-- Tailwind CSS & Alpine.js -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="//unpkg.com/alpinejs" defer></script>

  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
      animation: fade-in 0.6s ease-out;
    }
  </style>
</head>
<body
  class="min-h-screen bg-gradient-to-tr from-pink-200 via-green-200 to-blue-200 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 flex items-center justify-center px-4 py-10 transition-colors duration-300"
>

  <!-- Dark Mode Toggle Button -->
  <div class="absolute top-5 right-5">
    <button
      @click="darkMode = !darkMode"
      class="p-2 rounded-full bg-white dark:bg-gray-700 shadow-md transition"
      aria-label="Toggle dark mode"
    >
      <template x-if="!darkMode">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v2m0 14v2m9-11h-2M5 12H3m14.364 6.364l-1.414-1.414M6.05 6.05L4.636 4.636m12.728 0l-1.414 1.414M6.05 17.95l-1.414 1.414"/>
        </svg>
      </template>
      <template x-if="darkMode">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3c.132 0 .263.005.394.014a9 9 0 108.592 8.592A7 7 0 0112 3z"/>
        </svg>
      </template>
    </button>
  </div>

  <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 animate-fade-in transition-all duration-500">
    <h2 class="text-2xl font-semibold text-center text-gray-800 dark:text-white mb-6">
      Create an Account
    </h2>

    <!-- Laravel Register Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
      @csrf

      <!-- Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Full Name
        </label>
        <input
          type="text"
          id="name"
          name="name"
          value="{{ old('name') }}"
          required
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 dark:bg-gray-700 dark:text-white"
        />
        @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Email Address
        </label>
        <input
          type="email"
          id="email"
          name="email"
          value="{{ old('email') }}"
          required
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 dark:bg-gray-700 dark:text-white"
        />
        @error('email')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password -->
      <div x-data="{ show: false }" class="relative">
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Password
        </label>
        <input
          :type="show ? 'text' : 'password'"
          id="password"
          name="password"
          required
          class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 dark:bg-gray-700 dark:text-white"
        />
        <button
          type="button"
          @click="show = !show"
          class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-500 dark:text-gray-300 focus:outline-none"
          tabindex="-1"
        >
          <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>

          <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.957 9.957 0 012.232-3.592m2.708-2.35A9.957 9.957 0 0112 5c4.477 0 8.267 2.943 9.541 7a9.965 9.965 0 01-4.164 5.184M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 3l18 18" />
          </svg>
        </button>
        @error('password')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div x-data="{ show: false }" class="relative">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Confirm Password
        </label>
        <input
          :type="show ? 'text' : 'password'"
          id="password_confirmation"
          name="password_confirmation"
          required
          class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 dark:bg-gray-700 dark:text-white"
        />
        <button
          type="button"
          @click="show = !show"
          class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-500 dark:text-gray-300 focus:outline-none"
          tabindex="-1"
        >
          <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>

          <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.957 9.957 0 012.232-3.592m2.708-2.35A9.957 9.957 0 0112 5c4.477 0 8.267 2.943 9.541 7a9.965 9.965 0 01-4.164 5.184M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 3l18 18" />
          </svg>
        </button>
      </div>

      <!-- Submit Button -->
      <div>
        <button
          type="submit"
          class="w-full bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded-lg transition duration-200 shadow"
        >
          Register
        </button>
      </div>
    </form>

    <!-- Login Link -->
    <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-6">
      Already have an account?
      <a href="{{ route('login') }}" class="text-pink-600 dark:text-pink-400 font-medium hover:underline">
        Login
      </a>
    </p>
  </div>
</body>
</html>
