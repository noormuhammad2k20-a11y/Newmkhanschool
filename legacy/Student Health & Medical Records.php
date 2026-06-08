<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
            <!-- Scrollable Content Canvas -->
            <div class="p-gutter md:p-margin-desktop overflow-y-auto w-full">
                <!-- Page Header -->
                <div class="flex justify-between items-end mb-lg">
                    <div>
                        <h2 class="font-headline-xl text-headline-xl text-on-background">Clinic Dashboard</h2>
                        <p class="font-body-md text-secondary mt-1">Monitor student health status, incidents, and clinic resources.</p>
                    </div>
                    <button class="px-md py-2 bg-primary text-on-primary font-label-md rounded border border-primary hover:bg-on-primary-fixed-variant transition-colors flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span> New Log entry
                    </button>
                </div>
                <!-- Bento Grid Layout -->
                <div class="grid grid-cols-12 gap-lg">
                    <!-- Critical Alerts (High Visibility) - Span 8 -->
                    <section class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-error/30 rounded-xl overflow-hidden shadow-sm relative">
                        <div class="absolute top-0 left-0 w-1 h-full bg-error"></div>
                        <div class="p-md border-b border-outline-variant flex justify-between items-center bg-error-container/20">
                            <h3 class="font-headline-lg text-headline-lg text-on-surface flex items-center gap-sm">
                                <span class="material-symbols-outlined text-error" data-icon="warning">warning</span>
                                Critical Conditions &amp; Alerts
                            </h3>
                            <a class="font-label-md text-primary hover:underline" href="#">View All Registry</a>
                        </div>
                        <div class="p-md">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                                <!-- Alert Item -->
                                <div class="p-md border border-outline-variant rounded-lg bg-surface-bright flex gap-md items-start">
                                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center font-label-md text-secondary">SJ</div>
                                    <div>
                                        <h4 class="font-headline-md text-headline-md text-on-surface text-[16px] leading-tight">Sarah Jenkins</h4>
                                        <p class="font-body-md text-secondary text-[13px]">Grade 8B</p>
                                        <div class="mt-2 inline-flex items-center px-2 py-1 bg-error-container text-on-error-container font-label-md rounded-full text-[11px]">
                                            Severe Peanut Allergy
                                        </div>
                                    </div>
                                </div>
                                <!-- Alert Item -->
                                <div class="p-md border border-outline-variant rounded-lg bg-surface-bright flex gap-md items-start">
                                    <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center font-label-md text-secondary">MK</div>
                                    <div>
                                        <h4 class="font-headline-md text-headline-md text-on-surface text-[16px] leading-tight">Marcus King</h4>
                                        <p class="font-body-md text-secondary text-[13px]">Grade 10A</p>
                                        <div class="mt-2 inline-flex items-center px-2 py-1 bg-tertiary-container text-on-tertiary rounded-full font-label-md text-[11px]">
                                            Type 1 Diabetes
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Vaccination Summary - Span 4 -->
                    <section class="col-span-12 lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm flex flex-col">
                        <div class="p-md border-b border-outline-variant">
                            <h3 class="font-headline-lg text-headline-lg text-on-surface">Vaccination Status</h3>
                        </div>
                        <div class="p-md flex-1 flex flex-col justify-center items-center">
                            <!-- Simulated Chart Area -->
                            <div class="relative w-32 h-32 mb-4">
                                <svg class="w-full h-full transform -rotate-90" viewbox="0 0 36 36">
                                    <circle class="stroke-surface-container-highest" cx="18" cy="18" fill="none" r="16" stroke-width="4"></circle>
                                    <circle class="stroke-primary" cx="18" cy="18" fill="none" r="16" stroke-dasharray="85, 100" stroke-width="4"></circle>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="font-headline-xl text-primary text-[28px]">85%</span>
                                </div>
                            </div>
                            <p class="font-body-md text-center text-on-surface mb-2">School-wide compliance rate for mandatory immunizations.</p>
                            <button class="w-full py-2 border border-outline-variant rounded font-label-md text-primary hover:bg-surface-container-low transition-colors">Generate Report</button>
                        </div>
                    </section>
                    <!-- Recent Logs Table - Span 8 -->
                    <section class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                        <div class="p-md border-b border-outline-variant flex justify-between items-center">
                            <h3 class="font-headline-lg text-headline-lg text-on-surface">Recent Check-ups &amp; Visits</h3>
                            <button class="text-secondary hover:text-primary transition-colors"><span class="material-symbols-outlined" data-icon="filter_list">filter_list</span></button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left font-body-md">
                                <thead class="bg-surface-container border-b border-outline-variant text-secondary font-label-md">
                                    <tr>
                                        <th class="p-md font-semibold">Student</th>
                                        <th class="p-md font-semibold">Time</th>
                                        <th class="p-md font-semibold">Reason</th>
                                        <th class="p-md font-semibold">Action Taken</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                        <td class="p-md text-on-surface">Emma Thompson</td>
                                        <td class="p-md text-secondary">09:15 AM</td>
                                        <td class="p-md text-on-surface">Headache</td>
                                        <td class="p-md"><span class="inline-flex px-2 py-1 bg-surface-container-high rounded text-[12px]">Rest / Paracetamol</span></td>
                                    </tr>
                                    <tr class="bg-surface-container-low border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                        <td class="p-md text-on-surface">David Chen</td>
                                        <td class="p-md text-secondary">10:30 AM</td>
                                        <td class="p-md text-on-surface">Routine Screening</td>
                                        <td class="p-md"><span class="inline-flex px-2 py-1 bg-surface-container-high rounded text-[12px]">Cleared</span></td>
                                    </tr>
                                    <tr class="border-b border-outline-variant hover:bg-surface-bright transition-colors">
                                        <td class="p-md text-on-surface">Liam Patel</td>
                                        <td class="p-md text-secondary">11:45 AM</td>
                                        <td class="p-md text-on-surface">Stomach Ache</td>
                                        <td class="p-md"><span class="inline-flex px-2 py-1 bg-surface-container-high rounded text-[12px]">Observation</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-sm bg-surface-bright border-t border-outline-variant text-center">
                            <a class="font-label-md text-primary hover:underline" href="#">View Full Log</a>
                        </div>
                    </section>
                    <!-- Inventory & Minor Incidents - Span 4 Vertical Stack -->
                    <div class="col-span-12 lg:col-span-4 flex flex-col gap-lg">
                        <!-- Clinic Inventory -->
                        <section class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                            <div class="p-md border-b border-outline-variant">
                                <h3 class="font-headline-lg text-headline-lg text-on-surface">Supply Inventory</h3>
                            </div>
                            <div class="p-md space-y-4">
                                <div>
                                    <div class="flex justify-between font-label-md mb-1">
                                        <span class="text-on-surface">First Aid Kits</span>
                                        <span class="text-secondary">Low (12/50)</span>
                                    </div>
                                    <div class="w-full bg-surface-container-highest rounded-full h-2">
                                        <div class="bg-error h-2 rounded-full" style="width: 24%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between font-label-md mb-1">
                                        <span class="text-on-surface">EpiPens (Unassigned)</span>
                                        <span class="text-secondary">Good (5/5)</span>
                                    </div>
                                    <div class="w-full bg-surface-container-highest rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between font-label-md mb-1">
                                        <span class="text-on-surface">Bandages / Gauze</span>
                                        <span class="text-secondary">Adequate (65%)</span>
                                    </div>
                                    <div class="w-full bg-surface-container-highest rounded-full h-2">
                                        <div class="bg-primary-fixed-dim h-2 rounded-full" style="width: 65%"></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Incident Reports -->
                        <section class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm flex-1">
                            <div class="p-md border-b border-outline-variant flex justify-between items-center">
                                <h3 class="font-headline-lg text-headline-lg text-on-surface">Recent Incidents</h3>
                                <button class="text-primary hover:text-on-primary-fixed-variant transition-colors"><span class="material-symbols-outlined text-[20px]" data-icon="add_circle">add_circle</span></button>
                            </div>
                            <ul class="divide-y divide-outline-variant">
                                <li class="p-md hover:bg-surface-bright transition-colors cursor-pointer">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="font-headline-md text-headline-md text-[14px] text-on-surface">Scraped Knee - Sports Field</span>
                                        <span class="font-label-md text-secondary text-[10px]">Yesterday</span>
                                    </div>
                                    <p class="font-body-md text-secondary text-[13px] line-clamp-1">Minor abrasion during physical education. Cleaned and bandaged.</p>
                                </li>
                                <li class="p-md hover:bg-surface-bright transition-colors cursor-pointer">
                                    <div class="flex justify-between items-start mb-1">
                                        <span class="font-headline-md text-headline-md text-[14px] text-on-surface">Sprained Ankle - Stairs</span>
                                        <span class="font-label-md text-secondary text-[10px]">Oct 12</span>
                                    </div>
                                    <p class="font-body-md text-secondary text-[13px] line-clamp-1">Ice applied, parents notified for pickup.</p>
                                </li>
                            </ul>
                        </section>
                    </div>
                </div> <!-- End Bento Grid -->
            </div>

<?php include 'includes/footer.php'; ?>
