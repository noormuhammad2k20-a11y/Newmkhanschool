<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
        <!-- Main Canvas -->
        <main class="flex-1 p-md md:p-lg lg:p-xl max-w-[1440px] mx-auto w-full flex flex-col gap-lg">
            <!-- Page Header & Actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md">
                <div>
                    <h1 class="font-headline-xl text-headline-xl text-on-surface">Asset Inventory</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Manage and track all institutional property and resources.</p>
                </div>
                <div class="flex items-center gap-md">
                    <button class="flex items-center gap-sm px-md py-sm bg-surface-container-lowest border border-outline-variant text-primary font-label-md text-label-md rounded hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-[18px]">swap_horiz</span>
                        Transfer Asset
                    </button>
                    <button class="flex items-center gap-sm px-md py-sm bg-primary text-on-primary font-label-md text-label-md rounded hover:bg-on-primary-fixed-variant transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Log New Asset
                    </button>
                </div>
            </div>
            <!-- Bento Grid: High-Level Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
                <!-- Metric Card 1 -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Total Assets Managed</span>
                        <span class="material-symbols-outlined text-outline">account_balance</span>
                    </div>
                    <div class="mt-md">
                        <span class="font-headline-xl text-headline-xl text-on-surface">12,450</span>
                        <div class="flex items-center gap-xs mt-xs text-primary font-label-md text-label-md">
                            <span class="material-symbols-outlined text-[14px]">trending_up</span>
                            <span>+142 this month</span>
                        </div>
                    </div>
                </div>
                <!-- Metric Card 2 (Alert focus) -->
                <div class="bg-surface-container-lowest border border-error-container rounded-lg p-md flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-error-container opacity-20 rounded-bl-full"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Low Stock Alerts</span>
                        <span class="material-symbols-outlined text-error">warning</span>
                    </div>
                    <div class="mt-md relative z-10">
                        <span class="font-headline-xl text-headline-xl text-error">18</span>
                        <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Items require immediate restocking</p>
                    </div>
                </div>
                <!-- Category Breakdown Card -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md col-span-1 md:col-span-2 flex flex-col">
                    <div class="flex justify-between items-center mb-md pb-sm border-b border-surface-container-highest">
                        <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Inventory by Category</span>
                        <a class="text-primary font-label-md text-label-md hover:underline" href="#">View All</a>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-md flex-1">
                        <div>
                            <div class="flex items-center gap-xs mb-xs">
                                <span class="material-symbols-outlined text-outline text-[16px]">chair</span>
                                <span class="font-label-md text-label-md text-on-surface">Furniture</span>
                            </div>
                            <div class="font-headline-md text-headline-md text-on-surface mb-xs">5,200</div>
                            <div class="w-full bg-surface-container-high h-1 rounded-full overflow-hidden">
                                <div class="bg-surface-tint h-full w-[85%]"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-xs mb-xs">
                                <span class="material-symbols-outlined text-outline text-[16px]">biotech</span>
                                <span class="font-label-md text-label-md text-on-surface">Lab Eq.</span>
                            </div>
                            <div class="font-headline-md text-headline-md text-on-surface mb-xs">1,840</div>
                            <div class="w-full bg-surface-container-high h-1 rounded-full overflow-hidden">
                                <div class="bg-surface-tint h-full w-[60%]"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-xs mb-xs">
                                <span class="material-symbols-outlined text-outline text-[16px]">computer</span>
                                <span class="font-label-md text-label-md text-on-surface">IT Assets</span>
                            </div>
                            <div class="font-headline-md text-headline-md text-on-surface mb-xs">3,110</div>
                            <div class="w-full bg-surface-container-high h-1 rounded-full overflow-hidden">
                                <div class="bg-surface-tint h-full w-[92%]"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-xs mb-xs">
                                <span class="material-symbols-outlined text-error text-[16px]">edit</span>
                                <span class="font-label-md text-label-md text-error">Stationery</span>
                            </div>
                            <div class="font-headline-md text-headline-md text-on-surface mb-xs">2,300</div>
                            <div class="w-full bg-surface-container-high h-1 rounded-full overflow-hidden">
                                <div class="bg-error h-full w-[25%]"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Detailed Asset List (Data Table) -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden flex flex-col mt-md">
                <!-- Table Header/Toolbar -->
                <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                    <h3 class="font-headline-md text-headline-md text-on-surface">Recent Asset Records</h3>
                    <div class="flex gap-sm">
                        <button class="p-sm rounded border border-outline-variant text-secondary hover:bg-surface-container bg-surface-container-lowest flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">filter_list</span>
                        </button>
                        <button class="p-sm rounded border border-outline-variant text-secondary hover:bg-surface-container bg-surface-container-lowest flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">more_vert</span>
                        </button>
                    </div>
                </div>
                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">
                                <th class="p-md font-medium">Asset ID</th>
                                <th class="p-md font-medium">Name &amp; Description</th>
                                <th class="p-md font-medium">Category / Dept</th>
                                <th class="p-md font-medium">Condition</th>
                                <th class="p-md font-medium">Last Audit</th>
                                <th class="p-md font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-body-md">
                            <!-- Row 1 -->
                            <tr class="border-b border-surface-container-highest even:bg-surface-bright hover:bg-surface-container-low transition-colors">
                                <td class="p-md font-label-md text-primary">#IT-2023-089</td>
                                <td class="p-md">
                                    <div class="font-medium text-on-surface">Dell OptiPlex 7090</div>
                                    <div class="text-on-surface-variant text-[12px]">Desktop Computer</div>
                                </td>
                                <td class="p-md text-on-surface-variant">IT Assets / Comp Sci Lab</td>
                                <td class="p-md">
                                    <span class="inline-flex items-center px-sm py-[2px] rounded-full bg-secondary-container text-on-secondary-container font-label-md text-[10px]">
                                        Excellent
                                    </span>
                                </td>
                                <td class="p-md text-on-surface-variant">Oct 12, 2023</td>
                                <td class="p-md text-right">
                                    <button class="text-primary hover:text-on-primary-fixed-variant p-sm rounded hover:bg-surface-container">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                </td>
                            </tr>
                            <!-- Row 2 -->
                            <tr class="border-b border-surface-container-highest even:bg-surface-bright hover:bg-surface-container-low transition-colors">
                                <td class="p-md font-label-md text-primary">#FN-2021-442</td>
                                <td class="p-md">
                                    <div class="font-medium text-on-surface">Ergonomic Teacher Desk</div>
                                    <div class="text-on-surface-variant text-[12px]">Standard Issue Office</div>
                                </td>
                                <td class="p-md text-on-surface-variant">Furniture / Block A Staff Room</td>
                                <td class="p-md">
                                    <span class="inline-flex items-center px-sm py-[2px] rounded-full bg-surface-variant text-on-surface-variant font-label-md text-[10px]">
                                        Fair
                                    </span>
                                </td>
                                <td class="p-md text-on-surface-variant">Sep 05, 2023</td>
                                <td class="p-md text-right">
                                    <button class="text-primary hover:text-on-primary-fixed-variant p-sm rounded hover:bg-surface-container">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                </td>
                            </tr>
                            <!-- Row 3 (Alert State) -->
                            <tr class="border-b border-surface-container-highest even:bg-surface-bright hover:bg-surface-container-low transition-colors">
                                <td class="p-md font-label-md text-primary">#LB-2019-011</td>
                                <td class="p-md">
                                    <div class="font-medium text-on-surface">Digital Microscope (Leica)</div>
                                    <div class="text-on-surface-variant text-[12px]">High-res imagery unit</div>
                                </td>
                                <td class="p-md text-on-surface-variant">Lab Eq. / Biology Lab 2</td>
                                <td class="p-md">
                                    <span class="inline-flex items-center px-sm py-[2px] rounded-full bg-error-container text-on-error-container font-label-md text-[10px]">
                                        Needs Repair
                                    </span>
                                </td>
                                <td class="p-md text-error">Aug 22, 2023</td>
                                <td class="p-md text-right">
                                    <button class="text-primary hover:text-on-primary-fixed-variant p-sm rounded hover:bg-surface-container">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                </td>
                            </tr>
                            <!-- Row 4 -->
                            <tr class="border-b border-surface-container-highest even:bg-surface-bright hover:bg-surface-container-low transition-colors">
                                <td class="p-md font-label-md text-primary">#ST-2024-001</td>
                                <td class="p-md">
                                    <div class="font-medium text-on-surface">A4 Copy Paper Cartons</div>
                                    <div class="text-on-surface-variant text-[12px]">Bulk Supply (50 reams)</div>
                                </td>
                                <td class="p-md text-on-surface-variant">Stationery / Main Store</td>
                                <td class="p-md">
                                    <span class="inline-flex items-center px-sm py-[2px] rounded-full bg-secondary-container text-on-secondary-container font-label-md text-[10px]">
                                        New Stock
                                    </span>
                                </td>
                                <td class="p-md text-on-surface-variant">Nov 01, 2023</td>
                                <td class="p-md text-right">
                                    <button class="text-primary hover:text-on-primary-fixed-variant p-sm rounded hover:bg-surface-container">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Table Pagination/Footer -->
                <div class="p-md border-t border-outline-variant bg-surface-container-lowest flex justify-between items-center font-body-md text-body-md text-on-surface-variant">
                    <span>Showing 1 to 4 of 12,450 entries</span>
                    <div class="flex gap-xs">
                        <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container disabled:opacity-50" disabled="">Prev</button>
                        <button class="px-sm py-xs border border-primary bg-primary-fixed-dim text-primary rounded font-bold">1</button>
                        <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container">2</button>
                        <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container">3</button>
                        <span class="px-sm py-xs">...</span>
                        <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container">Next</button>
                    </div>
                </div>
            </div>
        </main>

<?php include 'includes/footer.php'; ?>
