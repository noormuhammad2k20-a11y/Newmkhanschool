@extends('layouts.app')

@section('title', 'New Admission Form')

@section('content')
    <style>
        /* Custom scrollbar for form area to maintain clean UI */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background-color: #c6c5d4; border-radius: 10px; }
    </style>
    <!-- Scrollable Form Canvas -->
    <main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop bg-surface-container-low w-full">
        <div class="max-w-[1200px] mx-auto">
            <!-- Page Header -->
            <div class="mb-lg flex flex-col sm:flex-row sm:items-end justify-between">
                <div>
                    <div class="flex items-center text-label-md font-label-md text-secondary mb-xs">
                        <a class="hover:text-primary transition-colors" href="{{ route('admin.students') }}">Students</a>
                        <span class="material-symbols-outlined text-[14px] mx-xs">chevron_right</span>
                        <span class="text-on-surface">Add New Student</span>
                    </div>
                    <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-surface">
                        Registration Form
                    </h2>
                    <p class="text-body-md font-body-md text-secondary mt-1">Enter complete details to enroll a new student into the system.</p>
                    <div id="status-badge" class="hidden mt-2 inline-flex items-center px-sm py-[2px] rounded font-label-md text-[12px] uppercase"></div>
                </div>
                <div class="mt-sm sm:mt-0 flex gap-sm">
                    <button class="px-md py-sm rounded border border-outline-variant bg-surface-container-lowest text-primary text-label-md font-label-md hover:bg-surface transition-colors flex items-center">
                        <span class="material-symbols-outlined mr-xs text-[18px]">close</span>
                        Cancel
                    </button>
                    <button class="px-md py-sm rounded bg-primary text-on-primary text-label-md font-label-md hover:opacity-90 transition-opacity flex items-center" form="studentForm" type="submit">
                        <span class="material-symbols-outlined mr-xs text-[18px]">save</span>
                        Save Record
                    </button>
                </div>
            </div>
            
            <!-- Main Form -->
            <form class="space-y-lg" id="studentForm">
                <!-- 1. Personal Section -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg md:p-xl">
                    <div class="border-b border-outline-variant pb-sm mb-lg flex items-center">
                        <span class="material-symbols-outlined text-primary mr-sm text-[24px]">person</span>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Personal Information</h3>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
                        <!-- Photo Placeholder -->
                        <div class="lg:col-span-3 flex flex-col items-center justify-start">
                            <div class="w-32 h-32 md:w-40 md:h-40 rounded border-2 border-dashed border-outline-variant bg-surface-container-low flex flex-col items-center justify-center text-secondary relative overflow-hidden group cursor-pointer hover:border-primary hover:bg-primary-fixed transition-colors">
                                <span class="material-symbols-outlined text-[32px] mb-xs group-hover:text-primary">add_a_photo</span>
                                <span class="text-label-md font-label-md text-center px-2 group-hover:text-primary">Upload Photo</span>
                                <input name="photo" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" type="file" />
                            </div>
                        </div>
                        <!-- Text Inputs -->
                        <div class="lg:col-span-9 grid grid-cols-1 md:grid-cols-3 gap-x-lg gap-y-md">
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">First Name <span class="text-error">*</span></label>
                                <input name="first_name" placeholder="e.g. Ali" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" required type="text" />
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Last Name</label>
                                <input name="last_name" placeholder="e.g. Ahmed" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Email <span class="text-error">*</span></label>
                                <input name="email" placeholder="e.g. student@school.com" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" required type="email" />
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Date of Birth <span class="text-error">*</span></label>
                                <div class="relative">
                                    <input name="date_of_birth" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" required type="date" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Gender <span class="text-error">*</span></label>
                                <div class="relative">
                                    <select name="gender" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">arrow_drop_down</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Place of Birth</label>
                                <input name="placeofbirth" placeholder="e.g. Lahore" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Religion</label>
                                <input name="religion" placeholder="e.g. Islam" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Caste</label>
                                <input name="caste" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 2. Academic Section -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg md:p-xl">
                    <div class="border-b border-outline-variant pb-sm mb-lg flex items-center">
                        <span class="material-symbols-outlined text-primary mr-sm text-[24px]">school</span>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Academic Details</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-lg">
                        <div>
                            <label class="block text-label-md font-label-md text-secondary mb-xs">GR No. / Admission No. <span class="text-error">*</span></label>
                            <input name="admission_number" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" required type="text" />
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-secondary mb-xs">Exam Roll</label>
                            <input name="exam_roll" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-secondary mb-xs">Admission Date</label>
                            <div class="relative">
                                <input name="admission_date" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" type="date" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-secondary mb-xs">Class Admitted</label>
                            <input name="class_admitted" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-secondary mb-xs">Current Class <span class="text-error">*</span></label>
                            <div class="relative">
                                <select name="current_class_id" id="classSelect" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" required>
                                    <option disabled selected value="">Select Class</option>
                                    @foreach($classes as $cls)
                                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">arrow_drop_down</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-secondary mb-xs">Current Section <span class="text-error">*</span></label>
                            <div class="relative">
                                <select name="current_section_id" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" required>
                                    <option disabled selected value="">Select Section</option>
                                    @foreach($sections as $sec)
                                        <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">arrow_drop_down</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-secondary mb-xs">Previous School</label>
                            <input name="previous_school" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-secondary mb-xs">Current School</label>
                            <input name="current_school" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                        </div>
                    </div>
                </div>

                <!-- 3. Parent/Guardian & Address Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
                    <!-- Parent Details -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg md:p-xl">
                        <div class="border-b border-outline-variant pb-sm mb-lg flex items-center">
                            <span class="material-symbols-outlined text-primary mr-sm text-[24px]">family_restroom</span>
                            <h3 class="text-headline-md font-headline-md text-on-surface">Parent / Guardian Details</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Father/Guardian Name</label>
                                <input name="guardian_name" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">B-Form / CNIC</label>
                                <input name="national_id" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Mobile Number</label>
                                <div class="relative">
                                    <input name="emergency_contact" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="tel" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Address Details -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg md:p-xl">
                        <div class="border-b border-outline-variant pb-sm mb-lg flex items-center">
                            <span class="material-symbols-outlined text-primary mr-sm text-[24px]">location_on</span>
                            <h3 class="text-headline-md font-headline-md text-on-surface">Address & Status</h3>
                        </div>
                        <div class="space-y-lg">
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Full Address</label>
                                <textarea name="address" class="w-full p-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors resize-none" rows="3"></textarea>
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Status <span class="text-error">*</span></label>
                                <div class="relative">
                                    <select name="status" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" required>
                                        <option value="Regular" selected>Regular</option>
                                        <option value="Irregular">Irregular</option>
                                        <option value="Transferred">Transferred</option>
                                    </select>
                                    <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">arrow_drop_down</span>
                                </div>
                            </div>
                            <div class="pt-sm border-t border-outline-variant">
                                <label class="flex items-center gap-sm cursor-pointer group">
                                    <div class="relative flex items-center justify-center w-5 h-5">
                                        <input type="hidden" name="is_tuition" value="0">
                                        <input type="checkbox" name="is_tuition" value="1" class="peer appearance-none w-5 h-5 border-2 border-outline rounded-[2px] checked:bg-primary checked:border-primary transition-colors focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                        <span class="material-symbols-outlined absolute text-on-primary text-[16px] pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                                    </div>
                                    <div>
                                        <div class="text-label-md font-label-md text-on-surface group-hover:text-primary transition-colors">Tuition Student</div>
                                        <div class="text-body-sm font-body-sm text-secondary">Check if this student is enrolled in tuition classes.</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Spacer for visual breathing room -->
                <div class="h-8"></div>
            </form>
        </div>
    </main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const classSelect = document.getElementById('classSelect');

    const form = document.getElementById('studentForm');
    const badge = document.getElementById('status-badge');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('/api/students', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            badge.classList.remove('hidden');
            if(response.status === 'success') {
                badge.textContent = 'Student Added Successfully';
                badge.className = 'mt-2 inline-flex items-center px-sm py-[2px] rounded font-label-md text-[12px] uppercase bg-[#2e7d32]/10 text-[#2e7d32]';
                form.reset();
            } else {
                badge.textContent = 'Error: ' + response.message;
                badge.className = 'mt-2 inline-flex items-center px-sm py-[2px] rounded font-label-md text-[12px] uppercase bg-error-container text-on-error-container';
            }
            setTimeout(() => { badge.classList.add('hidden'); }, 3000);
        })
        .catch(error => {
            badge.textContent = 'Network Error';
            badge.classList.remove('hidden');
            badge.className = 'mt-2 inline-flex items-center px-sm py-[2px] rounded font-label-md text-[12px] uppercase bg-error-container text-on-error-container';
            setTimeout(() => { badge.classList.add('hidden'); }, 3000);
        });
    });
});
</script>
@endsection
