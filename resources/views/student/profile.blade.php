@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">My Profile</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Manage your account settings and personal details</p>
        </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Left Column: Summary & Quick Info -->
            <div class="lg:col-span-1 space-y-md">
                <!-- Profile Card -->
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant text-center p-6 flex flex-col items-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-24 bg-primary-fixed opacity-50"></div>
                    <div class="relative w-32 h-32 mx-auto mb-4 mt-8">
                        @if($student->photo)
                            <img src="{{ asset('storage/'.$student->photo) }}" class="w-32 h-32 rounded-full object-cover border-4 border-surface-container-lowest shadow-md">
                        @else
                            <div class="w-32 h-32 rounded-full bg-primary-fixed flex items-center justify-center border-4 border-surface-container-lowest shadow-md mx-auto">
                                <span class="text-headline-xl font-headline-xl text-primary font-bold">{{ substr($student->first_name, 0, 1) }}</span>
                            </div>
                        @endif
                        <button class="absolute bottom-0 right-0 bg-primary text-on-primary w-8 h-8 rounded-full flex items-center justify-center shadow-md border-2 border-surface-container-lowest hover:bg-primary-container hover:text-on-primary-container transition-colors" title="Change Photo" onclick="document.getElementById('photoInput').click()">
                            <span class="material-symbols-rounded text-[16px]">photo_camera</span>
                        </button>
                    </div>
                    <h2 class="text-headline-lg font-headline-lg text-on-surface mb-1 font-bold">{{ $student->first_name }} {{ $student->last_name }}</h2>
                    <p class="text-body-md font-body-md text-secondary mb-4">{{ $user->email }}</p>
                    
                    <div class="flex flex-wrap justify-center gap-2 w-full">
                        <div class="flex-1 bg-surface-container px-3 py-2 rounded-lg border border-outline-variant">
                            <span class="block text-[10px] uppercase font-bold text-secondary tracking-wider">Class</span>
                            <span class="block text-body-lg font-bold text-on-surface">{{ $student->currentClass->name ?? 'N/A' }} {{ $student->currentSection->name ?? '' }}</span>
                        </div>
                        <div class="flex-1 bg-surface-container px-3 py-2 rounded-lg border border-outline-variant">
                            <span class="block text-[10px] uppercase font-bold text-secondary tracking-wider">Status</span>
                            <span class="block text-body-lg font-bold text-success">{{ $student->status }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- School Details -->
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                    <div class="p-4 border-b border-outline-variant bg-surface-bright flex items-center gap-2">
                        <span class="material-symbols-rounded text-primary text-[20px]">school</span>
                        <h3 class="text-headline-sm font-bold text-on-surface">Academic Info</h3>
                    </div>
                    <ul class="p-4 space-y-4 text-body-md">
                        <li class="flex justify-between items-center pb-3 border-b border-outline-variant/50">
                            <span class="text-secondary text-sm">Admission No.</span>
                            <span class="font-bold text-on-surface">{{ $student->admission_no }}</span>
                        </li>
                        <li class="flex justify-between items-center pb-3 border-b border-outline-variant/50">
                            <span class="text-secondary text-sm">Roll Number</span>
                            <span class="font-bold text-on-surface">{{ $student->exam_roll ?? '-' }}</span>
                        </li>
                        <li class="flex justify-between items-center pb-3 border-b border-outline-variant/50">
                            <span class="text-secondary text-sm">Admission Date</span>
                            <span class="font-bold text-on-surface">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('M d, Y') : '-' }}</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-secondary text-sm">Blood Group</span>
                            <span class="font-bold text-on-surface">{{ $student->blood_group ?? '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Edit Form & Details -->
            <div class="lg:col-span-2 space-y-md">
                
                <!-- Personal Information -->
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                    <div class="p-5 border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-primary text-[20px]">person</span>
                            <h3 class="text-headline-sm font-bold text-on-surface">Personal Information</h3>
                        </div>
                        <span class="text-[11px] text-secondary bg-surface-container px-2 py-1 rounded-md font-bold uppercase tracking-wider">Editable</span>
                    </div>
                    
                    <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                        @csrf
                        @method('PUT')
                        <input type="file" name="photo" id="photoInput" class="hidden" onchange="this.form.submit()">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
                            <div>
                                <label class="block text-label-sm font-bold text-secondary uppercase tracking-wider mb-2">Mobile Number</label>
                                <div class="relative">
                                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[18px]">phone</span>
                                    <input type="text" name="mobile_number" value="{{ old('mobile_number', $student->mobile_number) }}" class="w-full bg-surface-container-lowest text-on-surface border border-outline-variant rounded-lg shadow-sm py-2 px-3 pl-10 focus:border-primary focus:ring-primary text-sm transition-colors">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-label-sm font-bold text-secondary uppercase tracking-wider mb-2">Date of Birth</label>
                                <div class="relative">
                                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[18px]">calendar_month</span>
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d') : '') }}" class="w-full bg-surface-container-lowest text-on-surface border border-outline-variant rounded-lg shadow-sm py-2 px-3 pl-10 focus:border-primary focus:ring-primary text-sm transition-colors">
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-label-sm font-bold text-secondary uppercase tracking-wider mb-2">Residential Address</label>
                                <div class="relative">
                                    <span class="material-symbols-rounded absolute left-3 top-3 text-secondary text-[18px]">home</span>
                                    <textarea name="address" rows="3" class="w-full bg-surface-container-lowest text-on-surface border border-outline-variant rounded-lg shadow-sm py-2 px-3 pl-10 focus:border-primary focus:ring-primary text-sm transition-colors">{{ old('address', $student->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4 pt-4 border-t border-outline-variant/50">
                            <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container font-bold text-sm py-2 px-6 rounded-lg transition-colors shadow-sm flex items-center gap-2">
                                <span class="material-symbols-rounded text-[18px]">save</span>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Family & Emergency Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                        <div class="p-4 border-b border-outline-variant bg-surface-bright flex items-center gap-2">
                            <span class="material-symbols-rounded text-primary text-[20px]">family_restroom</span>
                            <h3 class="text-headline-sm font-bold text-on-surface">Parent/Guardian</h3>
                        </div>
                        <div class="p-5">
                            @if($student->parent)
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-lg">
                                        {{ substr($student->parent->first_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-on-surface">{{ $student->parent->first_name }} {{ $student->parent->last_name }}</p>
                                        <p class="text-[12px] text-secondary">Primary Contact</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 text-sm text-secondary">
                                        <span class="material-symbols-rounded text-[16px]">call</span>
                                        <span>{{ $student->parent->mobile_number ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-secondary">
                                        <span class="material-symbols-rounded text-[16px]">mail</span>
                                        <span class="truncate">{{ $student->parent->user->email ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-secondary italic">No parent/guardian information available.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                        <div class="p-4 border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-error text-[20px]">health_and_safety</span>
                                <h3 class="text-headline-sm font-bold text-on-surface">Emergency</h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4">
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-secondary mb-1">Emergency Contact</span>
                                    <p class="text-sm font-bold text-on-surface flex items-center gap-2">
                                        <span class="material-symbols-rounded text-[16px] text-secondary">contact_phone</span>
                                        {{ $student->parent->mobile_number ?? 'Please update in office' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="block text-[10px] font-bold uppercase tracking-wider text-secondary mb-1">Medical Conditions</span>
                                    <p class="text-sm text-on-surface bg-surface-container p-2 rounded-lg border border-outline-variant/50">
                                        {{ $student->medical_history ?? 'None reported' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                    <div class="p-5 border-b border-outline-variant bg-surface-bright flex items-center gap-2">
                        <span class="material-symbols-rounded text-primary text-[20px]">lock</span>
                        <h3 class="text-headline-sm font-bold text-on-surface">Security Settings</h3>
                    </div>
                    
                    <form action="{{ route('student.profile.update') }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-label-sm font-bold text-secondary uppercase tracking-wider mb-2">New Password</label>
                                <div class="relative">
                                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[18px]">key</span>
                                    <input type="password" name="password" class="w-full bg-surface-container-lowest text-on-surface border border-outline-variant rounded-lg shadow-sm py-2 px-3 pl-10 focus:border-primary focus:ring-primary text-sm" placeholder="Leave blank to keep current">
                                </div>
                            </div>
                            <div>
                                <label class="block text-label-sm font-bold text-secondary uppercase tracking-wider mb-2">Confirm Password</label>
                                <div class="relative">
                                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[18px]">key</span>
                                    <input type="password" name="password_confirmation" class="w-full bg-surface-container-lowest text-on-surface border border-outline-variant rounded-lg shadow-sm py-2 px-3 pl-10 focus:border-primary focus:ring-primary text-sm" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-surface-container hover:bg-surface-container-high border border-outline-variant text-on-surface font-bold text-sm py-2 px-6 rounded-lg transition-colors shadow-sm">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</main>
@endsection
