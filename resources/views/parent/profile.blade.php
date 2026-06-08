@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your parent account settings</p>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Summary -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden text-center p-6">
            <div class="relative w-24 h-24 mx-auto mb-4">
                <div class="w-24 h-24 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center border-4 border-blue-50 dark:border-gray-700 mx-auto">
                    <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ substr($user->name, 0, 1) }}</span>
                </div>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>
            
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-medium border border-blue-100 dark:border-blue-800/50">
                Parent Portal
            </div>
        </div>
    </div>

    <!-- Right Column: Edit Form -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Update Information</h3>
            </div>
            
            <form action="{{ route('parent.profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Contact admin to change email.</p>
                    </div>
                </div>

                <hr class="border-gray-200 dark:border-gray-700 my-6">
                
                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Change Password</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                        <input type="password" name="password" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Leave blank to keep current">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors shadow-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
