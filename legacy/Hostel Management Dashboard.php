<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
    <style>
        body {
            background-color: #e3f2fd;
        }

        /* Professional Sky Blue background */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid #e0e0e0;
        }

        .table-zebra tr:nth-child(even) {
            background-color: #e3f2fd;
        }
    </style>
        <!-- Main Canvas -->
        <main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop bg-[#e3f2fd]">
            <div class="max-w-[1440px] mx-auto space-y-lg">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-md mb-lg">
                    <div>
                        <h2 class="font-headline-xl text-headline-xl text-primary mb-xs">Hostel Management</h2>
                        <p class="font-body-lg text-body-lg text-secondary">Overview of capacity, occupancy, and operations for Central Campus Hostels.</p>
                    </div>
                    <div class="flex gap-sm">
                        <button class="px-md py-[8px] bg-white border border-outline-variant text-primary font-label-md rounded hover:bg-surface-container-low transition-colors shadow-sm">
                            <span class="material-symbols-outlined align-middle text-[18px] mr-xs">download</span> Export Report
                        </button>
                        <button class="px-md py-[8px] bg-primary text-white font-label-md rounded hover:bg-primary/90 transition-colors shadow-sm">
                            <span class="material-symbols-outlined align-middle text-[18px] mr-xs">add</span> New Assignment
                        </button>
                    </div>
                </div>
                <!-- Bento Grid Layout -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-lg">
                    <!-- Stats Overview (Occupancy & Capacity) -->
                    <div class="md:col-span-8 grid grid-cols-1 sm:grid-cols-3 gap-md">
                        <!-- Stat Card 1 -->
                        <div class="glass-card rounded-lg p-md flex flex-col justify-between">
                            <div class="flex justify-between items-start mb-sm">
                                <span class="font-label-md text-secondary">Total Capacity</span>
                                <span class="material-symbols-outlined text-primary bg-primary-container/20 p-[4px] rounded">domain</span>
                            </div>
                            <div>
                                <div class="font-headline-xl text-primary">1,200</div>
                                <div class="font-body-md text-secondary mt-xs flex items-center">
                                    <span class="material-symbols-outlined text-[16px] text-[#2e7d32] mr-xs">trending_up</span>
                                    <span class="text-[#2e7d32] font-semibold mr-xs">+50</span> from last year
                                </div>
                            </div>
                        </div>
                        <!-- Stat Card 2 -->
                        <div class="glass-card rounded-lg p-md flex flex-col justify-between border-l-4 border-l-[#1976d2]">
                            <div class="flex justify-between items-start mb-sm">
                                <span class="font-label-md text-secondary">Current Occupancy</span>
                                <span class="material-symbols-outlined text-[#1976d2] bg-[#1976d2]/10 p-[4px] rounded">group</span>
                            </div>
                            <div>
                                <div class="font-headline-xl text-on-surface">1,085</div>
                                <div class="w-full bg-surface-container-high h-2 mt-sm rounded-full overflow-hidden">
                                    <div class="bg-[#1976d2] h-full rounded-full" style="width: 90%;"></div>
                                </div>
                                <div class="font-body-md text-secondary mt-xs text-right">90.4% Full</div>
                            </div>
                        </div>
                        <!-- Stat Card 3 -->
                        <div class="glass-card rounded-lg p-md flex flex-col justify-between border-l-4 border-l-[#d32f2f]">
                            <div class="flex justify-between items-start mb-sm">
                                <span class="font-label-md text-secondary">Maintenance Alerts</span>
                                <span class="material-symbols-outlined text-[#d32f2f] bg-[#d32f2f]/10 p-[4px] rounded">build</span>
                            </div>
                            <div>
                                <div class="font-headline-xl text-on-surface">12</div>
                                <div class="font-body-md text-secondary mt-xs flex items-center">
                                    <span class="text-[#d32f2f] font-semibold mr-xs">3 Critical</span> requiring immediate action
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fee Status Donut Chart (Simulated) -->
                    <div class="md:col-span-4 glass-card rounded-lg p-md flex flex-col">
                        <div class="flex justify-between items-center mb-md border-b border-outline-variant pb-sm">
                            <h3 class="font-headline-md text-primary">Fee Collection Status</h3>
                            <button class="text-secondary hover:text-primary"><span class="material-symbols-outlined text-[20px]">more_vert</span></button>
                        </div>
                        <div class="flex-1 flex items-center justify-center relative py-md">
                            <!-- Circular Visualizer -->
                            <div class="w-32 h-32 rounded-full border-[12px] border-[#e0e0e0] border-t-[#2e7d32] border-r-[#2e7d32] border-b-[#fbc02d] relative flex items-center justify-center transform -rotate-45">
                                <div class="transform rotate-45 text-center">
                                    <div class="font-headline-lg text-on-surface leading-none">75%</div>
                                    <div class="font-label-md text-secondary">Collected</div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-sm mt-auto pt-sm border-t border-outline-variant">
                            <div class="text-center">
                                <div class="font-label-md text-secondary uppercase">Paid</div>
                                <div class="font-headline-md text-[#2e7d32]">814</div>
                            </div>
                            <div class="text-center border-l border-outline-variant">
                                <div class="font-label-md text-secondary uppercase">Pending</div>
                                <div class="font-headline-md text-[#fbc02d]">271</div>
                            </div>
                        </div>
                    </div>
                    <!-- Room Assignment Table -->
                    <div class="md:col-span-8 glass-card rounded-lg flex flex-col overflow-hidden">
                        <div class="p-md border-b border-outline-variant flex flex-col sm:flex-row justify-between items-start sm:items-center gap-sm bg-white">
                            <h3 class="font-headline-md text-primary">Student Room Assignments</h3>
                            <!-- Table Actions/Filters -->
                            <div class="flex w-full sm:w-auto gap-sm">
                                <div class="relative flex-1 sm:w-64">
                                    <span class="material-symbols-outlined absolute left-sm top-1/2 transform -translate-y-1/2 text-secondary text-[18px]">search</span>
                                    <input class="w-full pl-8 pr-sm py-[4px] bg-surface border border-outline-variant rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none text-body-md" placeholder="Search ID or Name..." type="text" />
                                </div>
                                <button class="p-[4px] border border-outline-variant rounded text-secondary hover:bg-surface-container"><span class="material-symbols-outlined text-[20px]">filter_list</span></button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse table-zebra">
                                <thead>
                                    <tr class="bg-surface-container text-secondary font-label-md uppercase border-b border-outline-variant">
                                        <th class="p-sm font-semibold pl-md">Student ID</th>
                                        <th class="p-sm font-semibold">Name</th>
                                        <th class="p-sm font-semibold">Block</th>
                                        <th class="p-sm font-semibold">Room</th>
                                        <th class="p-sm font-semibold text-right pr-md">Fee Status</th>
                                    </tr>
                                </thead>
                                <tbody class="font-body-md text-on-surface divide-y divide-outline-variant">
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="p-sm pl-md text-secondary">STD-2023-001</td>
                                        <td class="p-sm font-medium">Aarav Sharma</td>
                                        <td class="p-sm">North Block A</td>
                                        <td class="p-sm">102-A</td>
                                        <td class="p-sm text-right pr-md"><span class="inline-block px-xs py-[2px] bg-[#2e7d32]/10 text-[#2e7d32] rounded-full font-label-md text-[10px] uppercase">Paid</span></td>
                                    </tr>
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="p-sm pl-md text-secondary">STD-2023-045</td>
                                        <td class="p-sm font-medium">Vivaan Gupta</td>
                                        <td class="p-sm">North Block A</td>
                                        <td class="p-sm">102-B</td>
                                        <td class="p-sm text-right pr-md"><span class="inline-block px-xs py-[2px] bg-[#fbc02d]/20 text-[#f57f17] rounded-full font-label-md text-[10px] uppercase">Pending</span></td>
                                    </tr>
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="p-sm pl-md text-secondary">STD-2022-112</td>
                                        <td class="p-sm font-medium">Aditya Singh</td>
                                        <td class="p-sm">South Block C</td>
                                        <td class="p-sm">305-A</td>
                                        <td class="p-sm text-right pr-md"><span class="inline-block px-xs py-[2px] bg-[#2e7d32]/10 text-[#2e7d32] rounded-full font-label-md text-[10px] uppercase">Paid</span></td>
                                    </tr>
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="p-sm pl-md text-secondary">STD-2024-008</td>
                                        <td class="p-sm font-medium">Reyansh Patel</td>
                                        <td class="p-sm">East Block B</td>
                                        <td class="p-sm">210-C</td>
                                        <td class="p-sm text-right pr-md"><span class="inline-block px-xs py-[2px] bg-[#2e7d32]/10 text-[#2e7d32] rounded-full font-label-md text-[10px] uppercase">Paid</span></td>
                                    </tr>
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="p-sm pl-md text-secondary">STD-2023-089</td>
                                        <td class="p-sm font-medium">Krishna Kumar</td>
                                        <td class="p-sm">South Block C</td>
                                        <td class="p-sm">305-B</td>
                                        <td class="p-sm text-right pr-md"><span class="inline-block px-xs py-[2px] bg-[#d32f2f]/10 text-[#d32f2f] rounded-full font-label-md text-[10px] uppercase">Overdue</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-sm border-t border-outline-variant bg-white flex justify-between items-center text-secondary font-body-md">
                            <span>Showing 1-5 of 1,085</span>
                            <div class="flex gap-sm">
                                <button class="px-[8px] py-[4px] border border-outline-variant rounded hover:bg-surface-container disabled:opacity-50" disabled="">Prev</button>
                                <button class="px-[8px] py-[4px] border border-outline-variant rounded hover:bg-surface-container">Next</button>
                            </div>
                        </div>
                    </div>
                    <!-- Right Column: Mess Menu & Maintenance -->
                    <div class="md:col-span-4 flex flex-col gap-lg">
                        <!-- Mess Menu Widget -->
                        <div class="glass-card rounded-lg flex flex-col overflow-hidden">
                            <div class="p-md border-b border-outline-variant bg-white flex justify-between items-center">
                                <h3 class="font-headline-md text-primary flex items-center"><span class="material-symbols-outlined mr-sm">restaurant</span> Mess Schedule</h3>
                                <span class="font-label-md text-secondary">Today</span>
                            </div>
                            <div class="p-md space-y-md bg-white">
                                <!-- Meal Item -->
                                <div class="flex border-l-2 border-[#1976d2] pl-sm">
                                    <div class="w-16 shrink-0">
                                        <div class="font-label-md text-secondary">07:30 AM</div>
                                        <div class="font-body-md font-semibold text-on-surface">Breakfast</div>
                                    </div>
                                    <div class="ml-sm font-body-md text-secondary">
                                        Poha, Jalebi, Boiled Eggs, Milk/Tea
                                    </div>
                                </div>
                                <!-- Meal Item -->
                                <div class="flex border-l-2 border-[#fbc02d] pl-sm relative">
                                    <div class="absolute -left-[5px] top-1/2 w-2 h-2 bg-[#fbc02d] rounded-full transform -translate-y-1/2"></div> <!-- Current Meal Indicator -->
                                    <div class="w-16 shrink-0">
                                        <div class="font-label-md text-secondary">01:00 PM</div>
                                        <div class="font-body-md font-semibold text-on-surface">Lunch</div>
                                    </div>
                                    <div class="ml-sm font-body-md text-secondary">
                                        Dal Tadka, Mix Veg, Roti, Rice, Curd
                                    </div>
                                </div>
                                <!-- Meal Item -->
                                <div class="flex border-l-2 border-outline-variant pl-sm opacity-60">
                                    <div class="w-16 shrink-0">
                                        <div class="font-label-md text-secondary">08:00 PM</div>
                                        <div class="font-body-md font-semibold text-on-surface">Dinner</div>
                                    </div>
                                    <div class="ml-sm font-body-md text-secondary">
                                        Paneer Butter Masala, Roti, Jeera Rice
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Recent Maintenance Requests -->
                        <div class="glass-card rounded-lg flex flex-col overflow-hidden flex-1">
                            <div class="p-md border-b border-outline-variant bg-white flex justify-between items-center">
                                <h3 class="font-headline-md text-primary flex items-center"><span class="material-symbols-outlined mr-sm">engineering</span> Maintenance</h3>
                                <button class="text-primary font-label-md hover:underline">View All</button>
                            </div>
                            <div class="divide-y divide-outline-variant bg-white">
                                <!-- Request Item -->
                                <div class="p-sm hover:bg-surface-container-low transition-colors cursor-pointer flex justify-between items-start">
                                    <div>
                                        <div class="font-body-md font-semibold text-on-surface">Leaking Pipe in Washroom</div>
                                        <div class="font-label-md text-secondary mt-xs">Room 305-C • Reported Today</div>
                                    </div>
                                    <span class="inline-block px-xs py-[2px] bg-[#d32f2f]/10 text-[#d32f2f] rounded font-label-md text-[10px] uppercase">High Priority</span>
                                </div>
                                <!-- Request Item -->
                                <div class="p-sm hover:bg-surface-container-low transition-colors cursor-pointer flex justify-between items-start">
                                    <div>
                                        <div class="font-body-md font-semibold text-on-surface">Ceiling Fan Not Working</div>
                                        <div class="font-label-md text-secondary mt-xs">Room 102-A • Reported Yesterday</div>
                                    </div>
                                    <span class="inline-block px-xs py-[2px] bg-[#fbc02d]/20 text-[#f57f17] rounded font-label-md text-[10px] uppercase">Pending</span>
                                </div>
                                <!-- Request Item -->
                                <div class="p-sm hover:bg-surface-container-low transition-colors cursor-pointer flex justify-between items-start">
                                    <div>
                                        <div class="font-body-md font-semibold text-on-surface">Broken Window Latch</div>
                                        <div class="font-label-md text-secondary mt-xs">Common Area, Block B • 2 days ago</div>
                                    </div>
                                    <span class="inline-block px-xs py-[2px] bg-[#2e7d32]/10 text-[#2e7d32] rounded font-label-md text-[10px] uppercase">Resolved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

<?php include 'includes/footer.php'; ?>
