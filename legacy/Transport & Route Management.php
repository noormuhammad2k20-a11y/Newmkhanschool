<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
    <style>
        /* Professional Zebra Striping for Tables */
        .table-zebra tbody tr:nth-child(even) {
            background-color: #f3f4f5;
        }

        /* surface-container-low */
        .table-zebra tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
        <!-- Dashboard Canvas -->
        <main class="flex-1 overflow-y-auto p-lg max-w-[1440px] mx-auto w-full">
            <div class="mb-lg flex justify-between items-end">
                <div>
                    <h2 class="font-headline-lg text-headline-lg text-on-background mb-1">School Transport Operations</h2>
                    <p class="font-body-md text-body-md text-secondary">Real-time fleet monitoring and route management.</p>
                </div>
                <div class="flex gap-md">
                    <button class="px-md py-sm border border-outline-variant rounded font-label-md text-label-md text-primary bg-surface-container-lowest hover:bg-surface-container-low transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm" data-icon="download">download</span>
                        Export Manifest
                    </button>
                    <button class="px-md py-sm border border-transparent rounded font-label-md text-label-md text-on-primary bg-primary hover:bg-on-primary-container transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm" data-icon="add">add</span>
                        Dispatch Bus
                    </button>
                </div>
            </div>
            <!-- Fleet Overview KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-md mb-lg">
                <!-- KPI 1 -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded p-md flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-sm">
                        <span class="font-label-md text-label-md text-secondary">Total Fleet</span>
                        <span class="material-symbols-outlined text-primary" data-icon="directions_bus">directions_bus</span>
                    </div>
                    <div class="font-headline-xl text-headline-xl text-on-background">42</div>
                    <div class="font-label-md text-label-md text-secondary mt-1">Registered Vehicles</div>
                </div>
                <!-- KPI 2 -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded p-md flex flex-col justify-between border-l-4 border-l-[#1a237e]">
                    <!-- Primary Container accent -->
                    <div class="flex justify-between items-start mb-sm">
                        <span class="font-label-md text-label-md text-secondary">Active Now</span>
                        <span class="material-symbols-outlined text-[#1a237e]" data-icon="route">route</span>
                    </div>
                    <div class="font-headline-xl text-headline-xl text-on-background">28</div>
                    <div class="font-label-md text-label-md text-secondary mt-1">Currently in transit</div>
                </div>
                <!-- KPI 3 -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded p-md flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-sm">
                        <span class="font-label-md text-label-md text-secondary">At School / Parked</span>
                        <span class="material-symbols-outlined text-secondary" data-icon="local_parking">local_parking</span>
                    </div>
                    <div class="font-headline-xl text-headline-xl text-on-background">12</div>
                    <div class="font-label-md text-label-md text-secondary mt-1">Awaiting dispatch</div>
                </div>
                <!-- KPI 4 -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded p-md flex flex-col justify-between border-l-4 border-l-error">
                    <div class="flex justify-between items-start mb-sm">
                        <span class="font-label-md text-label-md text-secondary">Maintenance</span>
                        <span class="material-symbols-outlined text-error" data-icon="build">build</span>
                    </div>
                    <div class="font-headline-xl text-headline-xl text-on-background">02</div>
                    <div class="font-label-md text-label-md text-error mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]" data-icon="warning">warning</span> Requires attention
                    </div>
                </div>
            </div>
            <!-- Main Layout Grid -->
            <div class="grid grid-cols-12 gap-md">
                <!-- Left Column: Route Management Table (col-span-8) -->
                <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded flex flex-col overflow-hidden">
                    <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                        <h3 class="font-headline-md text-headline-md text-on-surface">Active Routes</h3>
                        <div class="flex items-center gap-sm">
                            <span class="font-label-md text-label-md text-secondary">Filter:</span>
                            <select class="border border-outline-variant rounded px-2 py-1 font-body-md text-body-md bg-transparent focus:border-primary">
                                <option>All Statuses</option>
                                <option>In Transit</option>
                                <option>Delayed</option>
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left table-zebra font-body-md text-body-md">
                            <thead class="bg-surface-container font-label-md text-label-md text-secondary border-b border-outline-variant">
                                <tr>
                                    <th class="p-md font-semibold w-24">Route No.</th>
                                    <th class="p-md font-semibold">Driver Details</th>
                                    <th class="p-md font-semibold">Vehicle No.</th>
                                    <th class="p-md font-semibold">Status</th>
                                    <th class="p-md font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-on-surface divide-y divide-outline-variant">
                                <!-- Row 1 (Active/Selected State Simulation) -->
                                <tr class="bg-primary-fixed hover:bg-primary-fixed-dim cursor-pointer transition-colors border-l-2 border-l-primary" style="background-color: #e0e0ff;">
                                    <td class="p-md font-label-md text-primary-container">RT-104</td>
                                    <td class="p-md">
                                        <div class="font-label-md">James Miller</div>
                                        <div class="text-secondary text-xs">+1 (555) 0192</div>
                                    </td>
                                    <td class="p-md font-mono text-xs">BUS-A42</td>
                                    <td class="p-md">
                                        <span class="inline-flex items-center gap-1 bg-secondary-container text-on-secondary-container rounded-full px-2 py-1 font-label-md text-[10px] uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span> In Transit
                                        </span>
                                    </td>
                                    <td class="p-md text-right">
                                        <button class="text-primary hover:underline font-label-md">View</button>
                                    </td>
                                </tr>
                                <!-- Row 2 -->
                                <tr class="hover:bg-surface-container-low cursor-pointer transition-colors">
                                    <td class="p-md font-label-md">RT-092</td>
                                    <td class="p-md">
                                        <div class="font-label-md">Sarah Jenkins</div>
                                        <div class="text-secondary text-xs">+1 (555) 0844</div>
                                    </td>
                                    <td class="p-md font-mono text-xs">BUS-B11</td>
                                    <td class="p-md">
                                        <span class="inline-flex items-center gap-1 bg-surface-variant text-on-surface-variant rounded-full px-2 py-1 font-label-md text-[10px] uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> At School
                                        </span>
                                    </td>
                                    <td class="p-md text-right">
                                        <button class="text-secondary hover:text-primary font-label-md">View</button>
                                    </td>
                                </tr>
                                <!-- Row 3 -->
                                <tr class="hover:bg-surface-container-low cursor-pointer transition-colors">
                                    <td class="p-md font-label-md">RT-115</td>
                                    <td class="p-md">
                                        <div class="font-label-md">David Chen</div>
                                        <div class="text-secondary text-xs">+1 (555) 0321</div>
                                    </td>
                                    <td class="p-md font-mono text-xs">BUS-C09</td>
                                    <td class="p-md">
                                        <span class="inline-flex items-center gap-1 bg-error-container text-on-error-container rounded-full px-2 py-1 font-label-md text-[10px] uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span> Delayed (Traffic)
                                        </span>
                                    </td>
                                    <td class="p-md text-right">
                                        <button class="text-secondary hover:text-primary font-label-md">View</button>
                                    </td>
                                </tr>
                                <!-- Row 4 -->
                                <tr class="hover:bg-surface-container-low cursor-pointer transition-colors">
                                    <td class="p-md font-label-md">RT-055</td>
                                    <td class="p-md">
                                        <div class="font-label-md">Maria Garcia</div>
                                        <div class="text-secondary text-xs">+1 (555) 0776</div>
                                    </td>
                                    <td class="p-md font-mono text-xs">BUS-A19</td>
                                    <td class="p-md">
                                        <span class="inline-flex items-center gap-1 bg-surface-variant text-on-surface-variant rounded-full px-2 py-1 font-label-md text-[10px] uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> Parked
                                        </span>
                                    </td>
                                    <td class="p-md text-right">
                                        <button class="text-secondary hover:text-primary font-label-md">View</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Footer -->
                    <div class="p-sm border-t border-outline-variant bg-surface-container-lowest flex justify-between items-center">
                        <span class="font-body-md text-body-md text-secondary text-sm">Showing 1-4 of 28 Active Routes</span>
                        <div class="flex gap-1">
                            <button class="p-1 rounded text-secondary hover:bg-surface-container-low"><span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span></button>
                            <button class="p-1 rounded text-secondary hover:bg-surface-container-low"><span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span></button>
                        </div>
                    </div>
                </div>
                <!-- Right Column: Route Details & Tracking (col-span-4) -->
                <div class="col-span-12 lg:col-span-4 flex flex-col gap-md">
                    <!-- Detail Card for Selected Route -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden flex flex-col">
                        <!-- Map Placeholder -->
                        <div class="h-48 w-full bg-surface-container flex items-center justify-center relative overflow-hidden border-b border-outline-variant">
                            <!-- Simulated Map Background -->
                            <div class="absolute inset-0 bg-[#e0e7ff] opacity-50" style="background-image: radial-gradient(#94a3b8 1px, transparent 1px); background-size: 20px 20px;"></div>
                            <!-- Tracking Marker -->
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="bg-primary text-on-primary rounded-full p-2 shadow-md animate-bounce">
                                    <span class="material-symbols-outlined icon-fill text-lg" data-icon="directions_bus">directions_bus</span>
                                </div>
                                <div class="bg-surface-container-lowest border border-outline-variant px-2 py-1 mt-1 rounded text-xs font-label-md shadow-sm">RT-104</div>
                            </div>
                            <div class="absolute bottom-2 right-2 bg-surface-container-lowest/80 backdrop-blur px-2 py-1 rounded border border-outline-variant font-label-md text-[10px] text-secondary">
                                GPS Active • Last sync: Just now
                            </div>
                        </div>
                        <!-- Route Header -->
                        <div class="p-md border-b border-outline-variant">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-headline-md text-headline-md text-on-surface">Route RT-104</h4>
                                    <p class="font-body-md text-body-md text-secondary">North District Loop</p>
                                </div>
                                <span class="bg-secondary-container text-on-secondary-container rounded px-2 py-1 font-label-md text-xs">In Transit</span>
                            </div>
                            <div class="grid grid-cols-2 gap-y-2 mt-4 font-body-md text-sm text-on-surface">
                                <div><span class="text-secondary block text-xs">Vehicle</span><span class="font-mono">BUS-A42</span></div>
                                <div><span class="text-secondary block text-xs">Capacity</span>40 / 50</div>
                                <div class="col-span-2"><span class="text-secondary block text-xs">Driver</span>James Miller (ID: D-4029)</div>
                            </div>
                        </div>
                        <!-- Emergency Action Banner (High Level Alert UI styling) -->
                        <div class="p-md bg-error-container/20 border-b border-outline-variant flex items-center justify-between">
                            <div class="flex items-center gap-2 text-error">
                                <span class="material-symbols-outlined" data-icon="emergency">emergency</span>
                                <span class="font-label-md text-label-md">Emergency Protocol</span>
                            </div>
                            <!-- Toggle Switch (Visual Only) -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input class="sr-only peer" type="checkbox" value="" />
                                <div class="w-9 h-5 bg-outline-variant peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-outline-variant after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-error"></div>
                            </label>
                        </div>
                        <!-- Passenger List Container -->
                        <div class="flex-1 flex flex-col min-h-[250px]">
                            <div class="px-md py-sm border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                                <span class="font-label-md text-label-md text-secondary">Student Manifest</span>
                                <span class="font-label-md text-label-md bg-surface-container px-2 py-0.5 rounded text-secondary">40 Boarded</span>
                            </div>
                            <!-- List -->
                            <div class="overflow-y-auto max-h-64 p-2">
                                <ul class="divide-y divide-outline-variant">
                                    <li class="py-2 px-2 hover:bg-surface-container-low rounded flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant flex items-center justify-center font-label-md text-xs">ES</div>
                                            <div>
                                                <div class="font-label-md text-on-surface text-sm">Emma Smith</div>
                                                <div class="text-secondary text-xs">Grade 8 • Stop 2</div>
                                            </div>
                                        </div>
                                        <span class="material-symbols-outlined text-primary text-sm icon-fill" data-icon="check_circle" title="Boarded">check_circle</span>
                                    </li>
                                    <li class="py-2 px-2 hover:bg-surface-container-low rounded flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant flex items-center justify-center font-label-md text-xs">LJ</div>
                                            <div>
                                                <div class="font-label-md text-on-surface text-sm">Liam Johnson</div>
                                                <div class="text-secondary text-xs">Grade 10 • Stop 3</div>
                                            </div>
                                        </div>
                                        <span class="material-symbols-outlined text-primary text-sm icon-fill" data-icon="check_circle" title="Boarded">check_circle</span>
                                    </li>
                                    <li class="py-2 px-2 hover:bg-surface-container-low rounded flex justify-between items-center opacity-60">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-surface-container-high text-secondary flex items-center justify-center font-label-md text-xs">OW</div>
                                            <div>
                                                <div class="font-label-md text-on-surface text-sm line-through">Olivia Williams</div>
                                                <div class="text-secondary text-xs">Grade 7 • Absent</div>
                                            </div>
                                        </div>
                                        <span class="material-symbols-outlined text-outline text-sm" data-icon="cancel" title="Absent">cancel</span>
                                    </li>
                                    <li class="py-2 px-2 hover:bg-surface-container-low rounded flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant flex items-center justify-center font-label-md text-xs">NB</div>
                                            <div>
                                                <div class="font-label-md text-on-surface text-sm">Noah Brown</div>
                                                <div class="text-secondary text-xs">Grade 9 • Stop 4</div>
                                            </div>
                                        </div>
                                        <span class="material-symbols-outlined text-outline text-sm" data-icon="radio_button_unchecked" title="Awaiting Boarding">radio_button_unchecked</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="p-md mt-auto border-t border-outline-variant">
                                <button class="w-full py-2 bg-surface-container hover:bg-surface-container-high border border-outline-variant rounded font-label-md text-primary transition-colors">
                                    View Full Manifest
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

<?php include 'includes/footer.php'; ?>
