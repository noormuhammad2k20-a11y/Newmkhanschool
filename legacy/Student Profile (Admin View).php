<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
<style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!-- SideNavBar -->
    
    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 flex flex-col h-screen overflow-hidden bg-background">
        <!-- TopNavBar -->
        
        <!-- Scrollable Canvas -->
        <div class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop w-full max-w-max-width mx-auto">
            <!-- Breadcrumb & Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-lg gap-md">
                <div class="flex items-center gap-sm text-body-md font-body-md text-secondary">
                    <a class="hover:text-primary transition-colors" href="#">Students</a>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="text-on-surface font-medium">Profile: STU-2023-0142</span>
                </div>
                <div class="flex gap-sm">
                    <button
                        class="px-md py-sm bg-surface text-primary border border-primary rounded font-label-md text-label-md flex items-center gap-xs hover:bg-secondary-container transition-colors">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Edit Profile
                    </button>
                    <button
                        class="px-md py-sm bg-primary text-on-primary rounded font-label-md text-label-md flex items-center gap-xs hover:bg-primary-container transition-colors">
                        <span class="material-symbols-outlined text-[18px]">print</span>
                        Print Report
                    </button>
                </div>
            </div>
            <!-- Profile Header (Bento Box style) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-md mb-lg">
                <!-- Main Identity Card -->
                <div
                    class="col-span-1 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-lg p-lg flex flex-col sm:flex-row gap-lg items-start">
                    <div class="relative shrink-0">
                        <img alt="Profile photo of a young female student with dark hair, wearing a white uniform shirt, looking directly at the camera with a neutral expression. Bright, even studio lighting. Professional administrative portrait style."
                            class="w-32 h-32 rounded-lg object-cover border border-outline-variant"
                            data-alt="Profile photo of a young female student with dark hair, wearing a white uniform shirt, looking directly at the camera with a neutral expression. Bright, even studio lighting. Professional administrative portrait style with a clean, light-mode aesthetic, utilizing deep blacks and pristine whites."
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuA7fTmX8yvwBdrqXloE1fG15llSc67zP1yID17QdhQZn9rvyTzK4iiGEXzBfvnK22_SBfZFf-N7KA5GfRcHlKoMIx53zfaB_eeij-4CAvP5zyKcBDoJQUQ1Zo_B5qd8XXGLhzWIZNu9sw2-NfnYkoO7rDp-TXBlzqtfqIYI5yd06NsyZtQrEqRIpNR1BDbRnG9Nr-Zv-toGSMHGZS3InSt1n8J2Ie_LLYZJMJjLEEHISUyxkyNTToC09qGGvd4wApJ174o6_Ott" />
                        <div
                            class="absolute -bottom-2 -right-2 bg-surface text-primary p-xs rounded border border-outline-variant shadow-sm">
                            <span class="material-symbols-outlined text-[20px] fill text-primary">verified</span>
                        </div>
                    </div>
                    <div class="flex-1 w-full">
                        <div class="flex justify-between items-start w-full">
                            <div>
                                <h2 class="text-headline-lg font-headline-lg text-on-surface mb-xs">Ayesha Khan</h2>
                                <p class="text-body-md font-body-md text-secondary flex items-center gap-xs mb-sm">
                                    <span class="material-symbols-outlined text-[16px]">school</span>
                                    Grade 10 - Section A (Science)
                                </p>
                            </div>
                            <span
                                class="px-sm py-xs bg-secondary-container text-on-secondary-container rounded-full text-label-md font-label-md border border-outline-variant border-opacity-50">Active</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-md mt-md pt-md border-t border-outline-variant">
                            <div>
                                <p class="text-label-md font-label-md text-secondary mb-xs">Student ID</p>
                                <p class="text-body-md font-body-md text-on-surface font-medium">STU-2023-0142</p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-secondary mb-xs">B-Form / CNIC</p>
                                <p class="text-body-md font-body-md text-on-surface font-medium">42101-1234567-8</p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-secondary mb-xs">Date of Birth</p>
                                <p class="text-body-md font-body-md text-on-surface font-medium">14 Aug 2008 (15y)</p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-secondary mb-xs">Admission Date</p>
                                <p class="text-body-md font-body-md text-on-surface font-medium">01 Apr 2018</p>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <p class="text-label-md font-label-md text-secondary mb-xs">Blood Group</p>
                                <p class="text-body-md font-body-md text-on-surface font-medium">O+</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Guardian Info Card -->
                <div
                    class="col-span-1 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-lg p-lg flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-sm mb-md border-b border-outline-variant pb-sm">
                            <span class="material-symbols-outlined text-secondary">family_restroom</span>
                            <h3 class="text-headline-md font-headline-md text-on-surface">Guardian Details</h3>
                        </div>
                        <div class="space-y-sm">
                            <div>
                                <p class="text-label-md font-label-md text-secondary">Father's Name</p>
                                <p class="text-body-md font-body-md text-on-surface font-medium">Muhammad Tariq Khan</p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-secondary">Primary Contact</p>
                                <p class="text-body-md font-body-md text-primary font-medium flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[16px]">call</span>
                                    +92 300 1234567
                                </p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-secondary">Residential Address</p>
                                <p class="text-body-md font-body-md text-on-surface">House 42, Street 7, Block 4,
                                    Clifton, Karachi.</p>
                            </div>
                        </div>
                    </div>
                    <button
                        class="mt-md w-full py-xs border border-outline-variant rounded text-label-md font-label-md text-secondary hover:bg-surface-container transition-colors">View
                        Full Contacts</button>
                </div>
            </div>
            <!-- Interactive Tabs Section -->
            <div
                class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden flex flex-col h-[500px]">
                <!-- Tab Headers -->
                <div class="flex border-b border-outline-variant bg-surface-container-low px-md pt-sm gap-md">
                    <button
                        class="px-md py-sm font-label-md text-label-md border-b-2 border-primary text-primary font-bold transition-colors">
                        Attendance History
                    </button>
                    <button
                        class="px-md py-sm font-label-md text-label-md border-b-2 border-transparent text-secondary hover:text-on-surface transition-colors">
                        Academic Records
                    </button>
                    <button
                        class="px-md py-sm font-label-md text-label-md border-b-2 border-transparent text-secondary hover:text-on-surface transition-colors">
                        Fee Status
                    </button>
                </div>
                <!-- Tab Content Area -->
                <div class="flex-1 p-lg overflow-y-auto bg-surface-container-lowest">
                    <!-- Attendance Content (Active) -->
                    <div class="h-full flex flex-col">
                        <div class="flex justify-between items-center mb-md">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Attendance Overview</h3>
                            <div class="flex gap-sm">
                                <select
                                    class="border border-outline-variant rounded bg-surface py-xs px-sm text-body-md font-body-md focus:border-primary focus:ring-0">
                                    <option>Term 1 (2023-2024)</option>
                                    <option>Term 2 (2023-2024)</option>
                                </select>
                            </div>
                        </div>
                        <!-- Stats Row -->
                        <div class="grid grid-cols-4 gap-md mb-lg">
                            <div class="p-md border border-outline-variant rounded bg-surface text-center">
                                <p class="text-label-md font-label-md text-secondary mb-xs">Total Days</p>
                                <p class="text-headline-lg font-headline-lg text-on-surface">85</p>
                            </div>
                            <div
                                class="p-md border border-outline-variant rounded bg-[#e8f5e9] text-center border-[#a5d6a7]">
                                <p class="text-label-md font-label-md text-secondary mb-xs">Present</p>
                                <p class="text-headline-lg font-headline-lg text-[#2e7d32]">78</p>
                            </div>
                            <div
                                class="p-md border border-outline-variant rounded bg-[#ffebee] text-center border-[#ef9a9a]">
                                <p class="text-label-md font-label-md text-secondary mb-xs">Absent</p>
                                <p class="text-headline-lg font-headline-lg text-[#c62828]">5</p>
                            </div>
                            <div
                                class="p-md border border-outline-variant rounded bg-[#fff8e1] text-center border-[#ffe082]">
                                <p class="text-label-md font-label-md text-secondary mb-xs">Leave</p>
                                <p class="text-headline-lg font-headline-lg text-[#f57f17]">2</p>
                            </div>
                        </div>
                        <!-- Calendar View Placeholder (Structured Data) -->
                        <div class="flex-1 border border-outline-variant rounded overflow-hidden flex flex-col">
                            <div
                                class="bg-surface-container py-xs px-md border-b border-outline-variant flex justify-between items-center">
                                <button class="text-secondary hover:text-on-surface"><span
                                        class="material-symbols-outlined">chevron_left</span></button>
                                <span class="text-body-md font-body-md font-semibold text-on-surface">October
                                    2023</span>
                                <button class="text-secondary hover:text-on-surface"><span
                                        class="material-symbols-outlined">chevron_right</span></button>
                            </div>
                            <div class="grid grid-cols-7 gap-px bg-outline-variant flex-1">
                                <!-- Days Header -->
                                <div
                                    class="bg-surface-container-low text-center py-xs text-label-md font-label-md text-secondary">
                                    Mon</div>
                                <div
                                    class="bg-surface-container-low text-center py-xs text-label-md font-label-md text-secondary">
                                    Tue</div>
                                <div
                                    class="bg-surface-container-low text-center py-xs text-label-md font-label-md text-secondary">
                                    Wed</div>
                                <div
                                    class="bg-surface-container-low text-center py-xs text-label-md font-label-md text-secondary">
                                    Thu</div>
                                <div
                                    class="bg-surface-container-low text-center py-xs text-label-md font-label-md text-secondary">
                                    Fri</div>
                                <div
                                    class="bg-surface-container-low text-center py-xs text-label-md font-label-md text-secondary">
                                    Sat</div>
                                <div
                                    class="bg-surface-container-low text-center py-xs text-label-md font-label-md text-secondary">
                                    Sun</div>
                                <!-- Sample Grid Row -->
                                <div
                                    class="bg-surface-container-lowest p-xs h-16 border-b border-r border-outline-variant border-opacity-50">
                                </div>
                                <div
                                    class="bg-surface-container-lowest p-xs h-16 border-b border-r border-outline-variant border-opacity-50">
                                </div>
                                <div
                                    class="bg-surface-container-lowest p-xs h-16 border-b border-r border-outline-variant border-opacity-50 flex flex-col items-center justify-center">
                                    <span class="text-body-md text-secondary">1</span>
                                    <span class="w-2 h-2 rounded-full bg-[#2e7d32] mt-xs"></span>
                                </div>
                                <div
                                    class="bg-surface-container-lowest p-xs h-16 border-b border-r border-outline-variant border-opacity-50 flex flex-col items-center justify-center">
                                    <span class="text-body-md text-secondary">2</span>
                                    <span class="w-2 h-2 rounded-full bg-[#2e7d32] mt-xs"></span>
                                </div>
                                <div
                                    class="bg-surface-container-lowest p-xs h-16 border-b border-r border-outline-variant border-opacity-50 flex flex-col items-center justify-center">
                                    <span class="text-body-md text-secondary">3</span>
                                    <span class="w-2 h-2 rounded-full bg-[#c62828] mt-xs"></span>
                                </div>
                                <div
                                    class="bg-surface-container-low p-xs h-16 border-b border-r border-outline-variant border-opacity-50 flex flex-col items-center justify-center">
                                    <span class="text-body-md text-secondary">4</span>
                                </div>
                                <div
                                    class="bg-surface-container-low p-xs h-16 border-b border-outline-variant border-opacity-50 flex flex-col items-center justify-center">
                                    <span class="text-body-md text-secondary">5</span>
                                </div>
                                <!-- Note: Minimal calendar rendering for structural demonstration -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


<?php include 'includes/footer.php'; ?>