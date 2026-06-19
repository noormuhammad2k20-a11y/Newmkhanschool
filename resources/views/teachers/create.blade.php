@extends('layouts.app')

@section('title', 'Add New Teacher')

@section('content')
    <style>
        .stepper-line {
            width: 100%;
            height: 2px;
            background-color: var(--color-outline-variant, #c6c5d4);
            position: absolute;
            left: 50%;
            top: 20px;
            z-index: 0;
            transition: background-color 0.3s ease;
        }

        .stepper-line.active {
            background-color: var(--color-primary, #000666);
        }
        .step-content {
            display: none;
        }
        
        .step-content.active {
            display: block;
        }

        .stepper-icon.completed {
            background-color: #137333 !important;
            color: white !important;
            border-color: #137333 !important;
        }
        .stepper-icon.active-step {
            background-color: var(--color-primary, #000666) !important;
            color: var(--color-on-primary, #ffffff) !important;
            border-color: var(--color-primary, #000666) !important;
        }
        .stepper-icon.future-step {
            background-color: var(--color-surface, #ffffff) !important;
            color: var(--color-on-surface-variant, #44474e) !important;
            border-color: var(--color-outline-variant, #c6c5d4) !important;
        }
        .stepper-text.completed, .stepper-text.active-step {
            color: var(--color-on-surface, #1a1b21) !important;
        }
        .stepper-text.future-step {
            color: var(--color-on-surface-variant, #44474e) !important;
        }
        .stepper-line.completed {
            background-color: #137333 !important;
        }
        .stepper-line.active-step {
            background-color: var(--color-primary, #000666) !important;
        }
    </style>

        <main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop bg-background">
            <div class="max-w-[1000px] mx-auto">
                <div class="mb-lg flex justify-between items-end border-b border-outline-variant pb-sm">
                    <div>
                        <h2 class="text-headline-lg font-headline-lg text-primary">Add New Teacher</h2>
                        <p class="text-body-md font-body-md text-on-surface-variant mt-xs">Fill these sections one by one; the color will change to indicate progress.</p>
                    </div>
                    <div class="flex gap-sm">
                        <a href="{{ route('admin.teachers') }}" class="text-primary font-label-md text-label-md px-md py-sm hover:bg-surface-container-low rounded-DEFAULT border border-outline-variant">Cancel</a>
                    </div>
                </div>
                <div class="flex flex-col gap-xl">
                    <!-- Stepper Top -->
                    <div class="w-full">
                        <div class="flex flex-row justify-between relative">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center flex-1 relative group" id="nav-step-1">
                                <div class="stepper-line active w-full absolute left-[50%] top-[20px] h-[2px]" id="line-step-1"></div>
                                <div id="icon-step-1" class="stepper-icon active-step w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md shrink-0 border-2 border-primary transition-colors relative z-10">
                                    1
                                </div>
                                <div class="text-center mt-sm">
                                    <h3 id="title-step-1" class="stepper-text active-step text-body-md font-body-md font-bold text-on-surface transition-colors">Basic Information</h3>
                                    <p class="text-body-sm font-body-sm text-on-surface-variant hidden md:block">Personal details &amp; contact</p>
                                </div>
                            </div>
                            <!-- Step 2 -->
                            <div class="flex flex-col items-center flex-1 relative group transition-opacity" id="nav-step-2">
                                <div class="stepper-line w-full absolute left-[50%] top-[20px] h-[2px]" id="line-step-2"></div>
                                <div id="icon-step-2" class="stepper-icon future-step w-10 h-10 rounded-full bg-surface text-on-surface-variant flex items-center justify-center font-label-md text-label-md shrink-0 border-2 border-outline-variant transition-colors relative z-10">
                                    2
                                </div>
                                <div class="text-center mt-sm">
                                    <h3 id="title-step-2" class="stepper-text future-step text-body-md font-body-md font-bold text-on-surface-variant transition-colors">Academic Info</h3>
                                    <p class="text-body-sm font-body-sm text-on-surface-variant hidden md:block">Qualifications &amp; experience</p>
                                </div>
                            </div>
                            <!-- Step 3 -->
                            <div class="flex flex-col items-center flex-1 relative group transition-opacity" id="nav-step-3">
                                <div id="icon-step-3" class="stepper-icon future-step w-10 h-10 rounded-full bg-surface text-on-surface-variant flex items-center justify-center font-label-md text-label-md shrink-0 border-2 border-outline-variant transition-colors relative z-10">
                                    3
                                </div>
                                <div class="text-center mt-sm">
                                    <h3 id="title-step-3" class="stepper-text future-step text-body-md font-body-md font-bold text-on-surface-variant transition-colors">Documents</h3>
                                    <p class="text-body-sm font-body-sm text-on-surface-variant hidden md:block">Upload verifications</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Form Canvas -->
                    <div class="w-full">
                        <div class="bg-surface rounded-DEFAULT border border-outline-variant p-lg shadow-sm relative">
                            <form id="add-teacher-form">
                                <!-- STEP 1: Basic Info -->
                                <div id="step-1" class="step-content active">
                                    <div class="mb-lg border-b border-outline-variant pb-sm flex justify-between items-center">
                                        <h3 class="text-headline-md font-headline-md text-primary">Basic Information</h3>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">First Name *</label>
                                            <input name="first_name" required class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="First name" type="text" />
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Last Name</label>
                                            <input name="last_name" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="Last name" type="text" />
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Email Address *</label>
                                            <input name="email" required class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="teacher@school.edu" type="email" />
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Mobile Number</label>
                                            <input name="phone" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="+92 3XX XXXXXXX" type="tel" />
                                        </div>
                                        <div class="flex flex-col gap-xs md:col-span-2">
                                            <label class="text-label-md font-label-md text-on-surface">CNIC / National ID</label>
                                            <input name="cnic" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="00000-0000000-0" type="text" />
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Gender</label>
                                            <select name="gender" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Date of Birth</label>
                                            <input name="dob" type="date" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" />
                                        </div>
                                        <div class="flex flex-col gap-xs md:col-span-2">
                                            <label class="text-label-md font-label-md text-on-surface">Address</label>
                                            <input name="address" type="text" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="Full residential address" />
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Branch</label>
                                            <select name="branch_id" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full">
                                                <option value="">Select Branch</option>
                                                <!-- Add branch options if applicable. Assuming a default branch is used if not selected for now. -->
                                            </select>
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Profile Photo</label>
                                            <input name="photo" type="file" accept="image/*" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" />
                                        </div>
                                        
                                        <div class="md:col-span-2 mt-xl flex justify-end gap-md border-t border-outline-variant pt-lg">
                                            <button type="button" onclick="nextStep(2)" class="bg-primary text-on-primary font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:bg-primary-container transition-colors shadow-sm flex items-center gap-xs">
                                                Next Step
                                                <span class="material-symbols-rounded text-[18px]">arrow_forward</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- STEP 2: Academic Info -->
                                <div id="step-2" class="step-content">
                                    <div class="mb-lg border-b border-outline-variant pb-sm flex justify-between items-center">
                                        <h3 class="text-headline-md font-headline-md text-primary">Academic Info</h3>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                                        <div class="flex flex-col gap-xs md:col-span-2">
                                            <label class="text-label-md font-label-md text-on-surface">Highest Qualification *</label>
                                            <input name="qualification" required class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="e.g. MS Physics, B.Ed" type="text" />
                                        </div>
                                        <div class="flex flex-col gap-xs md:col-span-2">
                                            <label class="text-label-md font-label-md text-on-surface">Subject Specialization *</label>
                                            <input name="subject_specialization" required class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="e.g. Mathematics, Physics" type="text" />
                                        </div>
                                        <div class="flex flex-col gap-xs md:col-span-2">
                                            <label class="text-label-md font-label-md text-on-surface">Years of Experience</label>
                                            <input name="experience" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="e.g. 5" type="number" min="0" step="1" />
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Joining Date</label>
                                            <input name="joining_date" type="date" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" />
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Basic Salary</label>
                                            <input name="basic_salary" type="number" step="0.01" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="e.g. 50000.00" />
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Employment Status</label>
                                            <select name="status" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full">
                                                <option value="Active">Active</option>
                                                <option value="On Leave">On Leave</option>
                                                <option value="Terminated">Terminated</option>
                                            </select>
                                        </div>
                                        <div class="flex flex-col gap-xs">
                                            <label class="text-label-md font-label-md text-on-surface">Assigned Classes/Subjects</label>
                                            <input name="assigned_classes" type="text" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="e.g. Grade 10 Math" />
                                            <p class="text-body-sm text-on-surface-variant mt-1">Separate with commas or enter a description.</p>
                                        </div>
                                        
                                        <div class="md:col-span-2 mt-xl flex justify-between gap-md border-t border-outline-variant pt-lg">
                                            <button type="button" onclick="prevStep(1)" class="bg-surface border border-outline-variant text-primary font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:bg-surface-container-low transition-colors shadow-sm flex items-center gap-xs">
                                                <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                                                Back
                                            </button>
                                            <button type="button" onclick="nextStep(3)" class="bg-primary text-on-primary font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:bg-primary-container transition-colors shadow-sm flex items-center gap-xs">
                                                Next Step
                                                <span class="material-symbols-rounded text-[18px]">arrow_forward</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- STEP 3: Documents -->
                                <div id="step-3" class="step-content">
                                    <div class="mb-lg border-b border-outline-variant pb-sm flex justify-between items-center">
                                        <h3 class="text-headline-md font-headline-md text-primary">Documents</h3>
                                    </div>
                                    <div class="flex flex-col gap-lg">
                                        <div class="w-full rounded border-2 border-dashed border-outline-variant bg-surface-container-low flex flex-col items-center justify-center text-secondary relative p-xl group cursor-pointer hover:border-primary hover:bg-primary-fixed transition-colors">
                                            <span class="material-symbols-rounded text-[48px] mb-sm group-hover:text-primary">upload_file</span>
                                            <h4 class="text-title-md font-bold text-on-surface group-hover:text-primary">Upload Resume / CV</h4>
                                            <p class="text-body-sm mt-xs">PDF or Word Document (Max 5MB)</p>
                                            <input name="resume" accept=".pdf,.doc,.docx" class="absolute inset-0 opacity-0 cursor-pointer" type="file" />
                                        </div>
                                        
                                        <div class="mt-xl flex justify-between gap-md border-t border-outline-variant pt-lg relative">
                                            <button type="button" onclick="prevStep(2)" class="bg-surface border border-outline-variant text-primary font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:bg-surface-container-low transition-colors shadow-sm flex items-center gap-xs">
                                                <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                                                Back
                                            </button>
                                            <button type="submit" class="bg-[#137333] text-white font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:opacity-90 transition-opacity shadow-sm flex items-center gap-xs">
                                                <span class="material-symbols-rounded text-[18px]">check_circle</span>
                                                Complete Registration
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- Success/Error Overlay inside the card -->
                            <div id="form-overlay" class="hidden absolute inset-0 bg-surface/90 backdrop-blur-sm z-50 flex flex-col items-center justify-center rounded-DEFAULT">
                                <span id="overlay-icon" class="material-symbols-rounded text-[64px] text-[#137333] mb-md">check_circle</span>
                                <h3 id="overlay-title" class="text-headline-md font-headline-md text-on-surface mb-xs">Success</h3>
                                <p id="overlay-msg" class="text-body-md text-on-surface-variant">Teacher registered successfully.</p>
                                <button onclick="window.location.reload()" class="mt-lg bg-primary text-on-primary font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:bg-primary-container transition-colors shadow-sm">
                                    Add Another Teacher
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

<script>
    // Multi-step logic
    function updateStepper(step) {
        // Reset all
        for(let i=1; i<=3; i++) {
            const nav = document.getElementById('nav-step-' + i);
            const icon = document.getElementById('icon-step-' + i);
            const title = document.getElementById('title-step-' + i);
            const line = document.getElementById('line-step-' + i);
            
            nav.classList.remove('opacity-60');
            
            // Apply base classes
            icon.className = 'stepper-icon future-step w-10 h-10 rounded-full flex items-center justify-center font-label-md text-label-md shrink-0 border-2 transition-colors relative z-10 bg-surface';
            title.className = 'stepper-text future-step text-body-md font-body-md font-bold transition-colors';
            
            if(line) line.className = 'stepper-line w-full absolute left-[50%] top-[20px] h-[2px] transition-colors z-0';
        }
        
        // Update states based on current step
        for(let i=1; i<=3; i++) {
            const nav = document.getElementById('nav-step-' + i);
            const icon = document.getElementById('icon-step-' + i);
            const title = document.getElementById('title-step-' + i);
            const line = document.getElementById('line-step-' + i);
            
            if (i < step) {
                // Completed step (Green)
                icon.className = 'stepper-icon completed w-10 h-10 rounded-full flex items-center justify-center font-label-md text-label-md shrink-0 border-2 transition-colors relative z-10';
                title.className = 'stepper-text completed text-body-md font-body-md font-bold transition-colors text-[#137333]';
                if(line) line.className = 'stepper-line completed w-full absolute left-[50%] top-[20px] h-[2px] transition-colors z-0';
            } else if (i === step) {
                // Current step (Primary blue)
                icon.className = 'stepper-icon active-step w-10 h-10 rounded-full flex items-center justify-center font-label-md text-label-md shrink-0 border-2 transition-colors relative z-10';
                title.className = 'stepper-text active-step text-body-md font-body-md font-bold transition-colors text-primary';
                if(line) line.className = 'stepper-line active-step w-full absolute left-[50%] top-[20px] h-[2px] transition-colors z-0';
            }
        }
    }

    function nextStep(step) {
        // Basic validation before moving next
        const currentStepDiv = document.getElementById('step-' + (step - 1));
        const inputs = currentStepDiv.querySelectorAll('input[required]');
        let isValid = true;
        inputs.forEach(input => {
            if(!input.value) {
                input.reportValidity();
                isValid = false;
            }
        });
        
        if(!isValid) return;

        document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
        document.getElementById('step-' + step).classList.add('active');
        updateStepper(step);
    }

    function prevStep(step) {
        document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
        document.getElementById('step-' + step).classList.add('active');
        updateStepper(step);
    }

    // Form submission
    document.getElementById('add-teacher-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('full_name', formData.get('first_name') + ' ' + (formData.get('last_name') || ''));

        fetch('/api/teachers', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            const overlay = document.getElementById('form-overlay');
            const icon = document.getElementById('overlay-icon');
            const title = document.getElementById('overlay-title');
            const msg = document.getElementById('overlay-msg');
            
            overlay.classList.remove('hidden');
            
            if (response.status === 'success') {
                icon.textContent = 'check_circle';
                icon.className = 'material-symbols-rounded text-[64px] text-[#137333] mb-md';
                title.textContent = 'Success!';
                msg.textContent = 'Teacher has been registered successfully.';
            } else {
                icon.textContent = 'error';
                icon.className = 'material-symbols-rounded text-[64px] text-[#c5221f] mb-md';
                title.textContent = 'Error';
                msg.textContent = response.message || 'An error occurred while saving.';
            }
        });
    });
</script>
@endsection
