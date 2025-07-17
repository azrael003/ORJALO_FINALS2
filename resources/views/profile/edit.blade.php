@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gradient-to-tr from-purple-100 via-pink-100 to-purple-200 py-10 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-10 border border-purple-300">
        <h2 class="text-3xl font-bold text-purple-700 mb-8 text-center">Edit Your Profile</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 mb-6 rounded-lg border border-green-300 text-center">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-purple-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full border border-purple-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-purple-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full border border-purple-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400"
                        required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-purple-700 mb-1">New Password <span class="text-xs text-gray-500">(optional)</span></label>
                    <input type="password" name="password"
                        class="w-full border border-purple-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-purple-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-purple-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-400">
                </div>
            </div>

            <!-- Update Button -->
            <div class="text-center pt-6">
                <button type="submit"
                    class="bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-8 rounded-lg transition">
                    Update Profile
                </button>
            </div>
        </form>

        <hr class="my-10 border-purple-300">

        <!-- Delete Account -->
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account?')">
            @csrf
            @method('DELETE')

            <h3 class="text-xl font-semibold text-red-700 mb-4 text-center">Delete Account</h3>

            <div class="max-w-md mx-auto">
                <label class="block text-sm font-medium text-red-700 mb-1">Confirm Password</label>
                <input type="password" name="password"
                    class="w-full border border-red-300 rounded-lg px-4 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-red-400"
                    required>
                @error('password')
                    <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
                @enderror

                <div class="text-center">
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-8 rounded-lg transition">
                        Delete Account
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
