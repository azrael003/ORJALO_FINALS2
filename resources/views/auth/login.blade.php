<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: false }" :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>

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
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6 text-yellow-500"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 3v2m0 14v2m9-11h-2M5 12H3m14.364 6.364l-1.414-1.414M6.05 6.05L4.636 4.636m12.728 0l-1.414 1.414M6.05 17.95l-1.414 1.414"
          />
        </svg>
      </template>
      <template x-if="darkMode">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6 text-white"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 3c.132 0 .263.005.394.014a9 9 0 108.592 8.592A7 7 0 0112 3z"
          />
        </svg>
      </template>
    </button>
  </div>

  <div
    class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 animate-fade-in transition-all duration-500"
  >
    <h2
      class="text-2xl font-semibold text-center text-gray-800 dark:text-white mb-6"
    >
      Log In to Your Account
    </h2>

    <!-- Session Status -->
    @if (session('status'))
      <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
        {{ session('status') }}
      </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
      @csrf

      <!-- Email -->
      <div>
        <label
          for="email"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >Email Address</label
        >
        <input
          id="email"
          type="email"
          name="email"
          value="{{ old('email') }}"
          required
          autofocus
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 dark:bg-gray-700 dark:text-white"
        />
        @error('email')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password -->
      <div>
        <label
          for="password"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
          >Password</label
        >
        <input
          id="password"
          type="password"
          name="password"
          required
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-400 dark:focus:ring-pink-600 dark:bg-gray-700 dark:text-white"
        />
        @error('password')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Remember Me -->
      <div class="flex items-center">
        <input
          id="remember_me"
          type="checkbox"
          name="remember"
          class="h-4 w-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500 dark:border-gray-600 dark:bg-gray-700"
        />
        <label
          for="remember_me"
          class="ml-2 block text-sm text-gray-700 dark:text-gray-300"
          >Remember Me</label
        >
      </div>

      <!-- Submit -->
      <div>
        <button
          type="submit"
          class="w-full bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 rounded-lg transition duration-200 shadow"
        >
          Login
        </button>
      </div>
    </form>

    <!-- Forgot Password and Register -->
    <div
      class="text-center text-sm text-gray-600 dark:text-gray-400 mt-6 space-y-2"
    >
      @if (Route::has('password.request'))
        <a
          href="{{ route('password.request') }}"
          class="text-pink-600 dark:text-pink-400 hover:underline"
          >Forgot your password?</a
        ><br />
      @endif
      <span
        >Don't have an account?
        <a
          href="{{ route('register') }}"
          class="text-pink-600 dark:text-pink-400 font-medium hover:underline"
          >Register</a
        >
      </span>
    </div>
  </div>
</body>
</html>
