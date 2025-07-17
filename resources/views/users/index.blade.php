@extends('layouts.app')

@section('title', 'Users List')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gradient-to-tr from-purple-100 via-pink-100 to-purple-200 py-10 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-7xl mx-auto bg-white rounded-xl shadow-lg p-10 border border-purple-300">
        <h1 class="text-3xl font-bold text-purple-700 mb-10 text-center">Registered Users</h1>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
                class="inline-block bg-pink-500 hover:bg-pink-600 text-white text-sm font-semibold py-2 px-4 rounded-lg transition">
                ← Back to Dashboard
            </a>
        </div>

        @if($users->count())
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-purple-200">
                    <thead class="bg-purple-100">
                        <tr>
                            <th class="text-left px-4 py-3 text-purple-700 font-semibold border-b">Name</th>
                            <th class="text-left px-4 py-3 text-purple-700 font-semibold border-b">Email</th>
                            <th class="text-left px-4 py-3 text-purple-700 font-semibold border-b">Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="hover:bg-purple-50">
                                <td class="px-4 py-3 text-purple-800">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-purple-700">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-purple-600 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @else
            <p class="text-center text-purple-600 italic">No users found.</p>
        @endif
    </div>
</div>
@endsection
