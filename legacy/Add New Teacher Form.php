<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
    <style>
        .stepper-line {
            width: 2px;
            height: 100%;
            background-color: var(--color-outline-variant, #c6c5d4);
            position: absolute;
            left: 15px;
            top: 32px;
            z-index: 0;
        }

        .stepper-line.active {
            background-color: var(--color-primary, #000666);
        }
    </style>

        <!-- Form Content -->
        <main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop bg-background">
            <div class="max-w-[1000px] mx-auto">
                <div class="mb-lg flex justify-between items-end border-b border-outline-variant pb-sm">
                    <div>
                        <h2 class="text-headline-lg font-headline-lg text-primary">Add New Teacher</h2>
                        <p class="text-body-md font-body-md text-on-surface-variant mt-xs">Complete all sections to register a new faculty member.</p>
                    </div>
                    <div class="flex gap-sm">
                        <a href="Teacher Directory.php" class="text-primary font-label-md text-label-md px-md py-sm hover:bg-surface-container-low rounded-DEFAULT border border-outline-variant">Cancel</a>
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row gap-xl">
                    <!-- Stepper Sidebar -->
                    <div class="w-full lg:w-64 shrink-0">
                        <div class="relative pl-sm">
                            <!-- Step 1 -->
                            <div class="relative pb-xl group">
                                <div class="stepper-line active h-full absolute left-[15px] top-[32px] w-[2px]"></div>
                                <div class="flex items-start gap-md relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-label-md text-label-md shrink-0 border-2 border-primary">
                                        1
                                    </div>
                                    <div>
                                        <h3 class="text-body-lg font-body-lg font-bold text-on-surface">Basic Information</h3>
                                        <p class="text-body-md font-body-md text-on-surface-variant">Personal details &amp; contact</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Step 2 -->
                            <div class="relative pb-xl group opacity-60 hover:opacity-100 transition-opacity cursor-pointer">
                                <div class="stepper-line h-full absolute left-[15px] top-[32px] w-[2px]"></div>
                                <div class="flex items-start gap-md relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-surface text-on-surface-variant flex items-center justify-center font-label-md text-label-md shrink-0 border-2 border-outline-variant group-hover:border-primary transition-colors">
                                        2
                                    </div>
                                    <div>
                                        <h3 class="text-body-lg font-body-lg font-bold text-on-surface-variant group-hover:text-primary transition-colors">Academic Info</h3>
                                        <p class="text-body-md font-body-md text-on-surface-variant">Qualifications &amp; experience</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Step 3 -->
                            <div class="relative group opacity-60 hover:opacity-100 transition-opacity cursor-pointer">
                                <div class="flex items-start gap-md relative z-10">
                                    <div class="w-8 h-8 rounded-full bg-surface text-on-surface-variant flex items-center justify-center font-label-md text-label-md shrink-0 border-2 border-outline-variant group-hover:border-primary transition-colors">
                                        3
                                    </div>
                                    <div>
                                        <h3 class="text-body-lg font-body-lg font-bold text-on-surface-variant group-hover:text-primary transition-colors">Documents</h3>
                                        <p class="text-body-md font-body-md text-on-surface-variant">Upload verifications</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Form Canvas -->
                    <div class="flex-1">
                        <div class="bg-surface rounded-DEFAULT border border-outline-variant p-lg shadow-sm">
                            <div class="mb-lg border-b border-outline-variant pb-sm flex justify-between items-center">
                                <h3 class="text-headline-md font-headline-md text-primary">Basic Information</h3>
                                <span id="form-status" class="hidden px-sm py-[2px] rounded-full text-label-md font-label-md border"></span>
                            </div>
                            <form id="add-teacher-form" class="grid grid-cols-1 md:grid-cols-2 gap-lg">
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
                                    <label class="text-label-md font-label-md text-on-surface">Subject Specialization *</label>
                                    <input name="subject_specialization" required class="border border-outline-variant rounded-DEFAULT p-sm text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary transition-colors bg-surface-lowest w-full" placeholder="e.g. Mathematics, Physics" type="text" />
                                </div>
                                
                                <div class="md:col-span-2 mt-xl flex justify-end gap-md border-t border-outline-variant pt-lg">
                                    <button type="submit" class="bg-primary text-on-primary font-label-md text-label-md px-lg py-sm rounded-DEFAULT hover:bg-primary-container transition-colors shadow-sm flex items-center gap-xs">
                                        <span class="material-symbols-outlined text-[18px]">save</span>
                                        Save Teacher
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

<script>
    document.getElementById('add-teacher-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('api/teachers.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            const statusBadge = document.getElementById('form-status');
            statusBadge.classList.remove('hidden');
            
            if (response.status === 'success') {
                statusBadge.textContent = 'Teacher Added';
                statusBadge.className = 'px-sm py-[2px] rounded-full text-label-md font-label-md border bg-[#e6f4ea] text-[#137333] border-[#137333]/20';
                this.reset();
            } else {
                statusBadge.textContent = 'Error: ' + response.message;
                statusBadge.className = 'px-sm py-[2px] rounded-full text-label-md font-label-md border bg-[#fce8e6] text-[#c5221f] border-[#c5221f]/20';
            }
            
            setTimeout(() => {
                statusBadge.classList.add('hidden');
            }, 3000);
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
