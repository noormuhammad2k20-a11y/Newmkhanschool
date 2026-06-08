<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1;
        }
    </style>

    <!-- SideNavBar (Shared Component) -->
    
    <!-- Main Content Canvas -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen relative">
        <!-- TopNavBar (Shared Component) -->
        
        <!-- Page Content -->
        <div class="p-margin-desktop flex-1 max-w-[1440px] mx-auto w-full flex flex-col gap-lg">
            <!-- Page Header & Actions -->
            <div class="flex justify-between items-end pb-4 border-b border-outline-variant">
                <div>
                    <h1 class="font-headline-xl text-headline-xl text-on-surface mb-2">Teacher Timetable</h1>
                    <p class="font-body-lg text-body-lg text-secondary">Individual weekly schedule for Ahmad Raza
                        (Senior Mathematics)</p>
                </div>
                <div class="flex gap-3">
                    <button
                        class="px-4 py-2 bg-surface-container-lowest border border-outline-variant text-primary rounded-DEFAULT font-label-md flex items-center gap-2 hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                        Download PDF
                    </button>
                    <button
                        class="px-4 py-2 bg-primary text-on-primary rounded-DEFAULT font-label-md flex items-center gap-2 hover:bg-opacity-90 transition-opacity">
                        <span class="material-symbols-outlined text-sm">sync</span>
                        Sync to Calendar
                    </button>
                </div>
            </div>
            <!-- Profile Summary Card -->
            <div
                class="bg-surface-container-lowest border border-outline-variant rounded-DEFAULT p-md flex gap-lg items-center">
                <img alt="Teacher Profile" class="w-16 h-16 rounded-full object-cover border border-outline-variant"
                    data-alt="A professional headshot of a male teacher in his 40s, wearing a crisp light blue button-down shirt. He has short dark hair and a warm, approachable expression. The background is a blurred, bright modern office or classroom setting. High resolution, well-lit, corporate professional style."
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBevPkZKjwQdexVN8Gw82h_51RWc-CPtTlLs-jbfjPkvTW8Ub8q5nisYZsgknR_1_7-tXJlQFg1NQfnb7s73omDlJacFfdrsRmYL26wA-u36gOwqRzz3pDM3kgDPuEhb2n_PTKhkAf_5zt-lnV5GP0hCTqvx6gFcyFU9Y7a-Zl0tSXpcflSWziadsA_EraN1C3l9M_POkYJi8QK14FszqYNKL-vvx1swsqPMtgMtGaQSorCdofSllBFxOkI16LJYQOt5QchNVlg" />
                <div class="flex-1 grid grid-cols-4 gap-6">
                    <div>
                        <p class="font-label-md text-label-md text-secondary mb-1">EMPLOYEE ID</p>
                        <p class="font-body-md text-body-md text-on-surface font-semibold">TCH-20491</p>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md text-secondary mb-1">SPECIALIZATION</p>
                        <p class="font-body-md text-body-md text-on-surface font-semibold">Senior Mathematics</p>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md text-secondary mb-1">DEPARTMENT</p>
                        <p class="font-body-md text-body-md text-on-surface font-semibold">Science &amp; Math</p>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md text-secondary mb-1">WEEKLY LOAD</p>
                        <div class="flex items-center gap-2">
                            <div class="w-full bg-surface-container-high h-2 rounded-full overflow-hidden">
                                <div class="bg-primary h-full" style="width: 80%"></div>
                            </div>
                            <span class="font-label-md text-label-md text-primary font-bold">24/30</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Layout Grid: Main Timetable + Sidebar -->
            <div class="flex gap-lg flex-1">
                <!-- Main Timetable Area (10 cols) -->
                <div
                    class="flex-1 bg-surface-container-lowest border border-outline-variant rounded-DEFAULT overflow-hidden flex flex-col">
                    <div
                        class="p-md border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                        <h3 class="font-headline-md text-headline-md text-on-surface">Weekly Grid</h3>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="w-3 h-3 rounded-full bg-primary-container inline-block"></span>
                            <span class="font-label-md text-label-md text-secondary mr-3">Assigned</span>
                            <span
                                class="w-3 h-3 rounded-full border border-outline-variant bg-surface-bright inline-block"></span>
                            <span class="font-label-md text-label-md text-secondary">Free Period</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-high border-b border-outline-variant">
                                    <th
                                        class="p-sm font-label-md text-label-md text-secondary border-r border-outline-variant w-24 text-center">
                                        Time</th>
                                    <th
                                        class="p-sm font-label-md text-label-md text-secondary border-r border-outline-variant w-1/5 text-center">
                                        Monday</th>
                                    <th
                                        class="p-sm font-label-md text-label-md text-secondary border-r border-outline-variant w-1/5 text-center">
                                        Tuesday</th>
                                    <th
                                        class="p-sm font-label-md text-label-md text-secondary border-r border-outline-variant w-1/5 text-center">
                                        Wednesday</th>
                                    <th
                                        class="p-sm font-label-md text-label-md text-secondary border-r border-outline-variant w-1/5 text-center">
                                        Thursday</th>
                                    <th class="p-sm font-label-md text-label-md text-secondary w-1/5 text-center">Friday
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Period 1 -->
                                <tr class="border-b border-outline-variant bg-surface-bright">
                                    <td
                                        class="p-sm font-label-md text-label-md text-secondary border-r border-outline-variant text-center bg-surface-container-low">
                                        08:00 AM<br /><span class="text-xs font-normal">08:45 AM</span>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 11 - Calculus</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                302</span>
                                        </div>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 12 - Adv Math</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                305</span>
                                        </div>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-surface-container text-secondary rounded-sm p-2 h-full flex items-center justify-center border border-dashed border-outline-variant">
                                            <span class="text-xs italic">Prep Period</span>
                                        </div>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 11 - Calculus</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                302</span>
                                        </div>
                                    </td>
                                    <td class="p-xs">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 12 - Adv Math</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                305</span>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Break -->
                                <tr class="border-b border-outline-variant bg-surface-container-low h-8">
                                    <td
                                        class="p-1 font-label-md text-label-md text-secondary border-r border-outline-variant text-center bg-surface-container-high text-[10px]">
                                        08:45 AM
                                    </td>
                                    <td class="text-center font-label-md text-secondary text-xs uppercase tracking-wider bg-surface-variant/30"
                                        colspan="5">
                                        Morning Break
                                    </td>
                                </tr>
                                <!-- Period 2 -->
                                <tr class="border-b border-outline-variant bg-surface-container-lowest">
                                    <td
                                        class="p-sm font-label-md text-label-md text-secondary border-r border-outline-variant text-center bg-surface-container-low">
                                        09:00 AM<br /><span class="text-xs font-normal">09:45 AM</span>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 10 - Algebra</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                201</span>
                                        </div>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-surface-container text-secondary rounded-sm p-2 h-full flex items-center justify-center border border-dashed border-outline-variant">
                                            <span class="text-xs italic">Prep Period</span>
                                        </div>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 10 - Algebra</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                201</span>
                                        </div>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 10 - Algebra</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                201</span>
                                        </div>
                                    </td>
                                    <td class="p-xs">
                                        <div
                                            class="bg-surface-container text-secondary rounded-sm p-2 h-full flex items-center justify-center border border-dashed border-outline-variant">
                                            <span class="text-xs italic">Prep Period</span>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Continue pattern for more periods to fill grid visually -->
                                <tr class="border-b border-outline-variant bg-surface-bright">
                                    <td
                                        class="p-sm font-label-md text-label-md text-secondary border-r border-outline-variant text-center bg-surface-container-low">
                                        09:50 AM<br /><span class="text-xs font-normal">10:35 AM</span>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Dept. Meeting</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">group</span> Conf Room
                                                B</span>
                                        </div>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 11 - Geometry</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                304</span>
                                        </div>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 12 - Adv Math</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                305</span>
                                        </div>
                                    </td>
                                    <td class="p-xs border-r border-outline-variant">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Grade 11 - Geometry</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">room</span> Room
                                                304</span>
                                        </div>
                                    </td>
                                    <td class="p-xs">
                                        <div
                                            class="bg-primary-container text-on-primary-container rounded-sm p-2 h-full flex flex-col justify-center border border-primary/20">
                                            <span class="font-label-md text-label-md block">Dept. Meeting</span>
                                            <span class="text-xs flex items-center gap-1 mt-1 opacity-80"><span
                                                    class="material-symbols-outlined text-[12px]">group</span> Conf Room
                                                B</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Sidebar Panel: Unassigned / Management (2 cols width approx) -->
                <div class="w-72 flex flex-col gap-md">
                    <!-- Unassigned Periods Card -->
                    <div
                        class="bg-surface-container-lowest border border-outline-variant rounded-DEFAULT overflow-hidden flex flex-col flex-1">
                        <div class="p-sm px-md border-b border-outline-variant bg-surface-container-low">
                            <h3
                                class="font-headline-md text-body-md font-semibold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-error">warning</span>
                                Gap Coverage Needed
                            </h3>
                        </div>
                        <div class="p-md bg-surface flex-1">
                            <p class="font-body-md text-body-md text-secondary mb-4 text-sm">Drag classes to fill
                                unassigned periods in this teacher's schedule.</p>
                            <div class="space-y-2">
                                <div
                                    class="bg-surface-container-lowest border border-outline-variant p-2 rounded-sm cursor-grab hover:border-primary transition-colors flex items-center gap-3">
                                    <span class="material-symbols-outlined text-outline">drag_indicator</span>
                                    <div>
                                        <p class="font-label-md text-label-md text-on-surface">Substitute - Gr 9 Math
                                        </p>
                                        <p class="text-xs text-secondary">Wednesday • 09:00 AM</p>
                                    </div>
                                </div>
                                <div
                                    class="bg-surface-container-lowest border border-outline-variant p-2 rounded-sm cursor-grab hover:border-primary transition-colors flex items-center gap-3">
                                    <span class="material-symbols-outlined text-outline">drag_indicator</span>
                                    <div>
                                        <p class="font-label-md text-label-md text-on-surface">Study Hall Supervision
                                        </p>
                                        <p class="text-xs text-secondary">Friday • 08:00 AM</p>
                                    </div>
                                </div>
                                <div
                                    class="bg-surface-container-lowest border border-outline-variant p-2 rounded-sm cursor-grab hover:border-primary transition-colors flex items-center gap-3">
                                    <span class="material-symbols-outlined text-outline">drag_indicator</span>
                                    <div>
                                        <p class="font-label-md text-label-md text-on-surface">Remedial Math Clinic</p>
                                        <p class="text-xs text-secondary">Tuesday • 09:00 AM</p>
                                    </div>
                                </div>
                            </div>
                            <button
                                class="w-full mt-4 py-2 border border-dashed border-outline-variant text-primary font-label-md rounded-DEFAULT hover:bg-primary-fixed-dim/20 transition-colors">
                                View Full Substitution Pool
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


<?php include 'includes/footer.php'; ?>