@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your account settings and personal details</p>
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
                @if($student->photo)
                    <img src="{{ asset('storage/'.$student->photo) }}" class="w-24 h-24 rounded-full object-cover border-4 border-blue-50 dark:border-blue-900/30">
                @else
                    <div class="w-24 h-24 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center border-4 border-blue-50 dark:border-gray-700 mx-auto">
                        <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ substr($student->first_name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</h2>
            <p class="text-sm text-gray-500 mb-4">Admission No: {{ $student->admission_no }}</p>
            
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-medium border border-blue-100 dark:border-blue-800/50">
                Class: {{ $student->currentClass->name ?? 'N/A' }} {{ $student->currentSection->name ?? '' }}
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">School Details</h3>
            <ul class="space-y-3 text-sm">
                <li class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-500">Roll Number</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $student->exam_roll ?? '-' }}</span>
                </li>
                <li class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                    <span class="text-gray-500">Admission Date</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('M d, Y') : '-' }}</span>
                </li>
                <li class="flex justify-between items-center py-2">
                    <span class="text-gray-500">Status</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">{{ $student->status }}</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Right Column: Edit Form -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Update Information</h3>
            </div>
            
            <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Contact admin to change email.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mobile Number</label>
                        <input type="text" name="mobile_number" value="{{ old('mobile_number', $student->mobile_number) }}" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Residential Address</label>
                        <textarea name="address" rows="3" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm py-2 px-3 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500">{{ old('address', $student->address) }}</textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Update Profile Photo</label>
                        <input type="file" name="photo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
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
