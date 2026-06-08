<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
    <style>
        /* Custom scrollbar for form area to maintain clean UI */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #c6c5d4;
            border-radius: 10px;
        }
    </style>
        <!-- Scrollable Form Canvas -->
        <main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop bg-surface-container-low w-full">
            <div class="max-w-[1000px] mx-auto">
                <!-- Page Header -->
                <div class="mb-lg flex flex-col sm:flex-row sm:items-end justify-between">
                    <div>
                        <div class="flex items-center text-label-md font-label-md text-secondary mb-xs">
                            <a class="hover:text-primary transition-colors" href="#">Students</a>
                            <span class="material-symbols-outlined text-[14px] mx-xs">chevron_right</span>
                            <span class="text-on-surface">Add New Student</span>
                        </div>
                        <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-surface">
                            Registration Form</h2>
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
                            <!-- Photo Upload (Left Col on Desktop) -->
                            <div class="lg:col-span-3 flex flex-col items-center justify-start">
                                <div class="w-32 h-32 md:w-40 md:h-40 rounded border-2 border-dashed border-outline-variant bg-surface-container-low flex flex-col items-center justify-center text-secondary relative overflow-hidden group cursor-pointer hover:border-primary hover:bg-primary-fixed transition-colors">
                                    <span class="material-symbols-outlined text-[32px] mb-xs group-hover:text-primary">add_a_photo</span>
                                    <span class="text-label-md font-label-md text-center px-2 group-hover:text-primary">Upload Photo</span>
                                    <input accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" type="file" />
                                </div>
                                <p class="text-[10px] font-body-md text-secondary mt-2 text-center">JPG or PNG, Max 2MB</p>
                            </div>
                            <!-- Text Inputs (Right Col on Desktop) -->
                            <div class="lg:col-span-9 grid grid-cols-1 md:grid-cols-2 gap-x-lg gap-y-md">
                                <div>
                                    <label class="block text-label-md font-label-md text-secondary mb-xs">First Name <span class="text-error">*</span></label>
                                    <input name="first_name" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" placeholder="e.g. Ali" required="" type="text" />
                                </div>
                                <div>
                                    <label class="block text-label-md font-label-md text-secondary mb-xs">Last Name <span class="text-error">*</span></label>
                                    <input name="last_name" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" placeholder="e.g. Ahmed" required="" type="text" />
                                </div>
                                <div>
                                    <label class="block text-label-md font-label-md text-secondary mb-xs">Admission No. <span class="text-error">*</span></label>
                                    <input name="admission_number" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" placeholder="e.g. ADM-2023-001" required="" type="text" />
                                </div>
                                <div>
                                    <label class="block text-label-md font-label-md text-secondary mb-xs">B-Form / National ID Number</label>
                                    <input name="national_id" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" placeholder="00000-0000000-0" type="text" />
                                </div>
                                <div>
                                    <label class="block text-label-md font-label-md text-secondary mb-xs">Date of Birth <span class="text-error">*</span></label>
                                    <div class="relative">
                                        <input name="date_of_birth" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" required type="date" />
                                        <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">calendar_month</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-label-md font-label-md text-secondary mb-xs">Gender <span class="text-error">*</span></label>
                                    <div class="relative">
                                        <select name="gender" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" required>
                                            <option disabled="" selected="" value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">arrow_drop_down</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 2. Parent/Guardian Section -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg md:p-xl">
                        <div class="border-b border-outline-variant pb-sm mb-lg flex items-center">
                            <span class="material-symbols-outlined text-primary mr-sm text-[24px]">family_restroom</span>
                            <h3 class="text-headline-md font-headline-md text-on-surface">Parent / Guardian Details</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Father/Guardian Name</label>
                                <input name="guardian_name" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" placeholder="Full Name" type="text" />
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Father/Guardian CNIC</label>
                                <input name="guardian_id" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" placeholder="00000-0000000-0" type="text" />
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-secondary mb-xs">Mobile Number <span class="text-error">*</span></label>
                                <div class="relative">
                                    <input name="emergency_contact" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" placeholder="300 0000000" required="" type="tel" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 3. Academic & 4. Address Sections (Side by Side on Large Desktop) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
                        <!-- Academic Section -->
                        <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg">
                            <div class="border-b border-outline-variant pb-sm mb-lg flex items-center">
                                <span class="material-symbols-outlined text-primary mr-sm text-[24px]">school</span>
                                <h3 class="text-headline-md font-headline-md text-on-surface">Academic Details</h3>
                            </div>
                            <div class="space-y-md">
                                <div class="grid grid-cols-2 gap-md">
                                    <div>
                                        <label class="block text-label-md font-label-md text-secondary mb-xs">Class <span class="text-error">*</span></label>
                                        <div class="relative">
                                            <select name="current_class_id" id="classSelect" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" required="">
                                                <option disabled="" selected="" value="">Select</option>
                                                <!-- Classes will be loaded dynamically -->
                                            </select>
                                            <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">arrow_drop_down</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-label-md font-label-md text-secondary mb-xs">Section <span class="text-error">*</span></label>
                                        <div class="relative">
                                            <select name="section" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" required="">
                                                <option disabled="" selected="" value="">Select</option>
                                                <option value="A">Section A</option>
                                                <option value="B">Section B</option>
                                                <option value="C">Section C</option>
                                            </select>
                                            <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">arrow_drop_down</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-label-md font-label-md text-secondary mb-xs">Admission Date <span class="text-error">*</span></label>
                                    <div class="relative">
                                        <input name="enrollment_date" class="w-full h-10 px-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors appearance-none" type="date" required />
                                        <span class="material-symbols-outlined absolute right-sm top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">calendar_month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Address Section -->
                        <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg">
                            <div class="border-b border-outline-variant pb-sm mb-lg flex items-center">
                                <span class="material-symbols-outlined text-primary mr-sm text-[24px]">location_on</span>
                                <h3 class="text-headline-md font-headline-md text-on-surface">Address Details</h3>
                            </div>
                            <div class="space-y-md">
                                <div>
                                    <label class="block text-label-md font-label-md text-secondary mb-xs">Full Address</label>
                                    <textarea name="address" class="w-full p-sm border border-outline-variant rounded bg-surface-container-lowest text-body-md font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors resize-none" placeholder="House No, Street, Landmark..." rows="4"></textarea>
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
    // Load Classes
    fetch('api/classes.php')
        .then(res => res.json())
        .then(response => {
            if(response.status === 'success') {
                const select = document.getElementById('classSelect');
                response.data.forEach(cls => {
                    select.innerHTML += `<option value="${cls.id}">${cls.name}</option>`;
                });
            }
        });

    const form = document.getElementById('studentForm');
    const badge = document.getElementById('status-badge');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('api/students.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
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

<?php include 'includes/footer.php'; ?>
