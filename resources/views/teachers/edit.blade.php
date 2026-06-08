@extends('layouts.app')

@section('title', 'Edit Teacher')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop bg-background">
    <div class="max-w-[1000px] mx-auto">
        <div class="mb-lg flex justify-between items-end border-b border-outline-variant pb-sm">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-primary">Edit Teacher: {{ $teacher->full_name }}</h2>
                <p class="text-body-md font-body-md text-on-surface-variant mt-xs">Update the teacher's information.</p>
            </div>
            <div class="flex gap-sm">
                <a href="{{ route('admin.teachers') }}" class="text-primary font-label-md text-label-md px-md py-sm hover:bg-surface-container-low rounded-DEFAULT border border-outline-variant">Cancel</a>
            </div>
        </div>
        <div class="flex flex-col gap-xl">
            <!-- Form Canvas -->
            <div class="w-full">
                <div class="bg-surface rounded-DEFAULT border border-outline-variant p-lg shadow-sm relative">
                    <form id="edit-teacher-form">
                        @php
                            $names = explode(' ', $teacher->full_name);
                            $firstName = array_shift($names);
                            $lastName = implode(' ', $names);
                        @endphp
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
                            <div class="flex flex-col gap-xs">
                                <label class="text-label-md font-label-md text-on-surface">First Name *</label>
                                <input name="first_name" required value="{{ $firstName }}" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" type="text" />
                            </div>
                            <div class="flex flex-col gap-xs">
                                <label class="text-label-md font-label-md text-on-surface">Last Name</label>
                                <input name="last_name" value="{{ $lastName }}" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" type="text" />
                            </div>
                            <div class="flex flex-col gap-xs">
                                <label class="text-label-md font-label-md text-on-surface">Email Address *</label>
                                <input name="email" required value="{{ $teacher->email }}" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" type="email" />
                            </div>
                            <div class="flex flex-col gap-xs">
                                <label class="text-label-md font-label-md text-on-surface">Mobile Number</label>
                                <input name="phone" value="{{ $teacher->mobile }}" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" type="tel" />
                            </div>
                            <div class="flex flex-col gap-xs md:col-span-2">
                                <label class="text-label-md font-label-md text-on-surface">CNIC / National ID</label>
                                <input name="cnic" value="{{ $teacher->cnic }}" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" type="text" />
                            </div>
                            
                            <div class="flex flex-col gap-xs md:col-span-2 mt-md">
                                <h4 class="text-title-md font-bold border-b border-outline-variant pb-xs">Academic Info</h4>
                            </div>
                            
                            <div class="flex flex-col gap-xs md:col-span-2">
                                <label class="text-label-md font-label-md text-on-surface">Highest Qualification *</label>
                                <input name="qualification" required value="{{ $teacher->qualification }}" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" type="text" />
                            </div>
                            <div class="flex flex-col gap-xs md:col-span-2">
                                <label class="text-label-md font-label-md text-on-surface">Subject Specialization *</label>
                                <input name="subject_specialization" required value="{{ $teacher->specialization }}" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" type="text" />
                            </div>
                            <div class="flex flex-col gap-xs md:col-span-2">
                                <label class="text-label-md font-label-md text-on-surface">Years of Experience</label>
                                <input name="experience" value="{{ $teacher->experience }}" class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" type="number" min="0" step="1" />
                            </div>

                            <div class="md:col-span-2 mt-xl flex justify-between gap-md border-t border-outline-variant pt-lg">
                                <button type="submit" class="bg-primary text-on-primary font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:bg-primary-container transition-colors shadow-sm flex items-center gap-xs ml-auto">
                                    <span class="material-symbols-outlined text-[18px]">save</span>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Success/Error Overlay inside the card -->
                    <div id="form-overlay" class="hidden absolute inset-0 bg-surface/90 backdrop-blur-sm z-50 flex flex-col items-center justify-center rounded-DEFAULT">
                        <span id="overlay-icon" class="material-symbols-outlined text-[64px] text-[#137333] mb-md">check_circle</span>
                        <h3 id="overlay-title" class="text-headline-md font-headline-md text-on-surface mb-xs">Success</h3>
                        <p id="overlay-msg" class="text-body-md text-on-surface-variant">Teacher updated successfully.</p>
                        <a href="{{ route('admin.teachers') }}" class="mt-lg bg-primary text-on-primary font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:bg-primary-container transition-colors shadow-sm">
                            Back to Directory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('edit-teacher-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('/api/teachers/{{ $teacher->id }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
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
                icon.className = 'material-symbols-outlined text-[64px] text-[#137333] mb-md';
                title.textContent = 'Success!';
                msg.textContent = 'Teacher has been updated successfully.';
            } else {
                icon.textContent = 'error';
                icon.className = 'material-symbols-outlined text-[64px] text-[#c5221f] mb-md';
                title.textContent = 'Error';
                msg.textContent = response.message || 'An error occurred while saving.';
            }
        });
    });
</script>
@endsection
