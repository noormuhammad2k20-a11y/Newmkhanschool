<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
        <!-- Main Dashboard Canvas -->
        <main class="flex-1 overflow-y-auto bg-surface-bright p-margin-desktop w-full">
            <div class="max-w-[1440px] mx-auto space-y-xl">
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
                    <div>
                        <h2 class="font-headline-xl text-headline-xl text-on-surface">Daily Attendance &amp; Leave Management</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Overview for Thursday, October 26, 2023</p>
                    </div>
                    <button class="inline-flex items-center gap-sm px-lg py-sm bg-primary text-on-primary font-label-md text-label-md rounded hover:opacity-90 transition-opacity whitespace-nowrap shadow-[0_4px_12px_rgba(26,35,126,0.08)]">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">description</span>
                        Generate Monthly Duty Report
                    </button>
                </div>
                <!-- Summary Stats (Bento Grid Style) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg flex items-center justify-between">
                        <div>
                            <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Total Staff Present</p>
                            <h3 class="font-headline-xl text-headline-xl text-on-surface">142</h3>
                            <p class="font-body-md text-body-md text-secondary mt-xs flex items-center gap-xs">
                                <span class="material-symbols-outlined text-surface-tint" style="font-size: 16px;">trending_up</span>
                                +2% from yesterday
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined" style="font-size: 24px;">group</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg flex items-center justify-between relative overflow-hidden">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-error-container rounded-bl-full opacity-20 -z-0"></div>
                        <div class="relative z-10">
                            <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Currently On Leave</p>
                            <h3 class="font-headline-xl text-headline-xl text-on-surface">8</h3>
                            <p class="font-body-md text-body-md text-secondary mt-xs">3 Sick, 5 Casual</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-error-container flex items-center justify-center text-error relative z-10">
                            <span class="material-symbols-outlined" style="font-size: 24px;">event_busy</span>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg flex items-center justify-between">
                        <div>
                            <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Late Arrivals</p>
                            <h3 class="font-headline-xl text-headline-xl text-on-surface">4</h3>
                            <p class="font-body-md text-body-md text-secondary mt-xs">Pending administrative review</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined" style="font-size: 24px;">schedule</span>
                        </div>
                    </div>
                </div>
                <!-- Main Data Section -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-xl">
                    <!-- Leave Requests Table (Span 8) -->
                    <div class="lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded flex flex-col">
                        <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                            <h3 class="font-headline-md text-headline-md text-on-surface">Pending Leave Requests</h3>
                            <button class="font-label-md text-label-md text-primary hover:underline">View All History</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-surface-container border-b border-outline-variant">
                                        <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Teacher Name</th>
                                        <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Leave Type</th>
                                        <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Duration</th>
                                        <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Status</th>
                                        <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="font-body-md text-body-md text-on-surface">
                                    <tr class="border-b border-surface-variant hover:bg-surface-bright transition-colors">
                                        <td class="py-md px-md font-semibold">Sarah Jenkins</td>
                                        <td class="py-md px-md">Medical / Sick</td>
                                        <td class="py-md px-md">Oct 27 - Oct 29 (3 Days)</td>
                                        <td class="py-md px-md">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-highest text-on-surface-variant font-label-md text-[10px] uppercase">Pending</span>
                                        </td>
                                        <td class="py-md px-md text-right space-x-2">
                                            <button class="px-3 py-1 bg-secondary-container text-primary font-label-md text-label-md rounded hover:bg-primary-container hover:text-on-primary transition-colors">Approve</button>
                                            <button class="px-3 py-1 border border-outline-variant text-on-surface-variant font-label-md text-label-md rounded hover:bg-error-container hover:text-error hover:border-error transition-colors">Reject</button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-surface-variant bg-surface-container-low hover:bg-surface-bright transition-colors">
                                        <td class="py-md px-md font-semibold">Michael Chang</td>
                                        <td class="py-md px-md">Casual Leave</td>
                                        <td class="py-md px-md">Oct 30 (1 Day)</td>
                                        <td class="py-md px-md">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-highest text-on-surface-variant font-label-md text-[10px] uppercase">Pending</span>
                                        </td>
                                        <td class="py-md px-md text-right space-x-2">
                                            <button class="px-3 py-1 bg-secondary-container text-primary font-label-md text-label-md rounded hover:bg-primary-container hover:text-on-primary transition-colors">Approve</button>
                                            <button class="px-3 py-1 border border-outline-variant text-on-surface-variant font-label-md text-label-md rounded hover:bg-error-container hover:text-error hover:border-error transition-colors">Reject</button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-surface-variant hover:bg-surface-bright transition-colors">
                                        <td class="py-md px-md font-semibold">Elena Rostova</td>
                                        <td class="py-md px-md">Professional Dev</td>
                                        <td class="py-md px-md">Nov 2 - Nov 3 (2 Days)</td>
                                        <td class="py-md px-md">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-highest text-on-surface-variant font-label-md text-[10px] uppercase">Pending</span>
                                        </td>
                                        <td class="py-md px-md text-right space-x-2">
                                            <button class="px-3 py-1 bg-secondary-container text-primary font-label-md text-label-md rounded hover:bg-primary-container hover:text-on-primary transition-colors">Approve</button>
                                            <button class="px-3 py-1 border border-outline-variant text-on-surface-variant font-label-md text-label-md rounded hover:bg-error-container hover:text-error hover:border-error transition-colors">Reject</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Daily Attendance Log (Span 4) -->
                    <div class="lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded flex flex-col h-full max-h-[500px]">
                        <div class="p-md border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                            <h3 class="font-headline-md text-headline-md text-on-surface">Live Attendance Log</h3>
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-secondary-container text-primary cursor-pointer hover:bg-primary-container hover:text-on-primary transition-colors">
                                <span class="material-symbols-outlined" style="font-size: 16px;">refresh</span>
                            </span>
                        </div>
                        <!-- Search / Filter inside log -->
                        <div class="p-sm border-b border-outline-variant bg-surface-container-lowest">
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-outline" style="font-size: 18px;">search</span>
                                <input class="w-full pl-8 pr-3 py-1.5 text-body-md font-body-md border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-surface-bright text-on-surface placeholder:text-outline transition-all" placeholder="Search staff..." type="text" />
                            </div>
                        </div>
                        <div class="flex-1 overflow-y-auto p-sm space-y-1">
                            <!-- Log Item -->
                            <div class="flex items-center justify-between p-sm hover:bg-surface-container-low rounded transition-colors">
                                <div class="flex items-center gap-sm">
                                    <div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-primary font-label-md text-label-md">JD</div>
                                    <div>
                                        <p class="font-body-md text-body-md font-semibold text-on-surface leading-tight">John Doe</p>
                                        <p class="font-label-md text-label-md text-secondary">Mathematics Dept.</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-label-md text-label-md text-primary flex items-center gap-1 justify-end">
                                        <span class="material-symbols-outlined text-primary" style="font-size: 14px;">login</span> 07:45 AM
                                    </p>
                                </div>
                            </div>
                            <!-- Log Item -->
                            <div class="flex items-center justify-between p-sm hover:bg-surface-container-low rounded transition-colors">
                                <div class="flex items-center gap-sm">
                                    <div class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center text-primary font-label-md text-label-md">AW</div>
                                    <div>
                                        <p class="font-body-md text-body-md font-semibold text-on-surface leading-tight">Alice Wright</p>
                                        <p class="font-label-md text-label-md text-secondary">Science Dept.</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-label-md text-label-md text-primary flex items-center gap-1 justify-end">
                                        <span class="material-symbols-outlined text-primary" style="font-size: 14px;">login</span> 07:52 AM
                                    </p>
                                </div>
                            </div>
                            <!-- Log Item Late -->
                            <div class="flex items-center justify-between p-sm hover:bg-surface-container-low rounded transition-colors bg-error-container/20">
                                <div class="flex items-center gap-sm">
                                    <div class="w-8 h-8 rounded-full bg-error-container flex items-center justify-center text-error font-label-md text-label-md">RB</div>
                                    <div>
                                        <p class="font-body-md text-body-md font-semibold text-on-surface leading-tight">Robert Brown</p>
                                        <p class="font-label-md text-label-md text-secondary">History Dept.</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-label-md text-label-md text-error flex items-center gap-1 justify-end">
                                        <span class="material-symbols-outlined text-error" style="font-size: 14px;">warning</span> 08:15 AM
                                    </p>
                                </div>
                            </div>
                            <!-- Log Item Checked Out -->
                            <div class="flex items-center justify-between p-sm hover:bg-surface-container-low rounded transition-colors opacity-75">
                                <div class="flex items-center gap-sm">
                                    <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant font-label-md text-label-md">LK</div>
                                    <div>
                                        <p class="font-body-md text-body-md font-semibold text-on-surface leading-tight">Laura King</p>
                                        <p class="font-label-md text-label-md text-secondary">Administration</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-label-md text-label-md text-secondary flex items-center gap-1 justify-end">
                                        <span class="material-symbols-outlined text-secondary" style="font-size: 14px;">logout</span> 11:30 AM
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

<?php include 'includes/footer.php'; ?>
