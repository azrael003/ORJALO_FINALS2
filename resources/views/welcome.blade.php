<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: false }" :class="darkMode ? 'dark' : ''">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Welcome</title>

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

<body class="min-h-screen bg-gradient-to-tr from-pink-200 via-green-200 to-blue-200 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 flex items-center justify-center px-4 py-10 transition-colors duration-300">

  <!-- 🌙 Dark Mode Toggle -->
  <div class="absolute top-5 right-5">
    <button @click="darkMode = !darkMode" class="p-2 rounded-full bg-white dark:bg-gray-700 shadow-md transition">
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

  <!-- 🎉 Welcome Card -->
  <div class="max-w-xl w-full bg-white dark:bg-gray-800 text-center rounded-2xl shadow-2xl p-10 animate-fade-in transition-all duration-500">
    <h1 class="text-4xl font-extrabold text-gray-800 dark:text-white mb-6">Welcome to Our App!</h1>
    <p class="text-gray-700 dark:text-gray-300 mb-8">Please register or login to continue.</p>

    <div class="flex justify-center flex-wrap gap-4">
      <a href="{{ route('register') }}"
         class="px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white font-semibold rounded-lg transition duration-200 shadow">
         Register
      </a>
      <a href="{{ route('login') }}"
         class="px-6 py-3 border border-pink-500 text-pink-600 dark:text-pink-300 font-semibold rounded-lg hover:bg-pink-100 dark:hover:bg-gray-700 transition duration-200 shadow">
         Login
      </a>
    </div>
  </div>

</body>
</html>
