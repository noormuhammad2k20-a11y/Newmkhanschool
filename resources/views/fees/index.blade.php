@extends('layouts.app')

@section('title', 'Fee Management')

@section('content')
        <!-- Main Canvas -->
        <main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
            <div class="max-w-[1440px] mx-auto">
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-xl gap-md">
                    <div>
                        <h1 class="text-headline-xl font-headline-xl text-on-surface mb-xs">Fee Management</h1>
                        <p class="text-body-lg font-body-lg text-secondary mt-1">Monitor collections, manage structures, and generate invoices.</p>
                    </div>
                </div>

                <div class="flex flex-nowrap gap-2 border-b border-outline-variant mb-xl overflow-x-auto whitespace-nowrap hide-scrollbar pb-3">
                    <button class="tab-btn shrink-0 active px-4 py-2 rounded-lg font-label-md text-body-md bg-primary text-white transition-colors" data-target="tab-dashboard">Dashboard</button>
                    <button class="tab-btn shrink-0 px-4 py-2 rounded-lg font-label-md text-body-md text-secondary hover:bg-surface-container hover:text-on-surface transition-colors" data-target="tab-categories">Categories</button>
                    <button class="tab-btn shrink-0 px-4 py-2 rounded-lg font-label-md text-body-md text-secondary hover:bg-surface-container hover:text-on-surface transition-colors" data-target="tab-structures">Fee Structures</button>
                    <button class="tab-btn shrink-0 px-4 py-2 rounded-lg font-label-md text-body-md text-secondary hover:bg-surface-container hover:text-on-surface transition-colors" data-target="tab-generate">Generate</button>
                </div>



<div id="tab-dashboard" class="tab-content">
                <!-- Metrics Bento Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-xl">
                    <!-- Card 1: Total Collected -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col justify-between h-32 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-secondary-container rounded-bl-full opacity-20 -z-0 pointer-events-none transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Total Collected (YTD)</span>
                            <span class="material-symbols-rounded text-primary bg-primary-fixed p-1.5 rounded-md text-[20px]">account_balance_wallet</span>
                        </div>
                        <div class="relative z-10 flex items-baseline gap-sm mt-auto">
                            <span id="metric-collected" class="font-headline-xl text-headline-xl text-on-surface">₨0</span>
                            <span class="font-label-md text-label-md text-[#137333] bg-[#e6f4ea] px-1.5 py-0.5 rounded flex items-center">
                                <span class="material-symbols-rounded text-[14px]">arrow_upward</span> 12%
                            </span>
                        </div>
                    </div>
                    <!-- Card 2: Pending Dues -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col justify-between h-32 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-error-container rounded-bl-full opacity-20 -z-0 pointer-events-none transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Pending Dues</span>
                            <span class="material-symbols-rounded text-error bg-error-container p-1.5 rounded-md text-[20px]">warning</span>
                        </div>
                        <div class="relative z-10 flex items-baseline gap-sm mt-auto">
                            <span id="metric-pending" class="font-headline-xl text-headline-xl text-on-surface">₨0</span>
                            <span id="metric-pending-students" class="font-label-md text-label-md text-secondary">across 0 students</span>
                        </div>
                    </div>
                    <!-- Card 3: Monthly Trend -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col justify-between h-32">
                        <div class="flex justify-between items-start mb-sm">
                            <span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Collection Trend</span>
                            <span class="font-label-md text-label-md text-on-surface-variant">Last 6 Months</span>
                        </div>
                        <div class="flex items-end justify-between h-12 mt-auto gap-xs px-xs">
                            <!-- CSS Bar Chart Representation -->
                            <div class="w-full bg-secondary-fixed rounded-t-sm h-[40%] hover:bg-primary transition-colors cursor-pointer relative group">
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block bg-inverse-surface text-inverse-on-surface font-label-md text-[10px] py-0.5 px-1 rounded whitespace-nowrap">Jan</div>
                            </div>
                            <div class="w-full bg-secondary-fixed rounded-t-sm h-[60%] hover:bg-primary transition-colors cursor-pointer relative group"></div>
                            <div class="w-full bg-secondary-fixed rounded-t-sm h-[30%] hover:bg-primary transition-colors cursor-pointer relative group"></div>
                            <div class="w-full bg-secondary-fixed rounded-t-sm h-[80%] hover:bg-primary transition-colors cursor-pointer relative group"></div>
                            <div class="w-full bg-secondary-fixed rounded-t-sm h-[50%] hover:bg-primary transition-colors cursor-pointer relative group"></div>
                            <div class="w-full bg-primary rounded-t-sm h-[100%] cursor-pointer relative group">
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 bg-inverse-surface text-inverse-on-surface font-label-md text-[10px] py-0.5 px-1 rounded whitespace-nowrap">Jun</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Transactions Data Card -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden flex flex-col">
                    <div class="px-md py-sm border-b border-outline-variant flex justify-between items-center bg-surface">
                        <h3 class="font-headline-md text-headline-md text-on-surface">Recent Transactions</h3>
                        <div class="flex gap-sm">
                            <button class="p-1.5 text-secondary hover:bg-surface-container rounded transition-colors">
                                <span class="material-symbols-rounded text-[20px]">filter_list</span>
                            </button>
                            <button class="p-1.5 text-secondary hover:bg-surface-container rounded transition-colors">
                                <span class="material-symbols-rounded text-[20px]">download</span>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead class="bg-surface-container-highest border-b border-outline-variant">
                                <tr>
                                    <th class="px-md py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider w-[120px]">Challan No.</th>
                                    <th class="px-md py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Student Name</th>
                                    <th class="px-md py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider w-[100px]">Class</th>
                                    <th class="px-md py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider w-[150px]">Date</th>
                                    <th class="px-md py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right w-[150px]">Amount (₨)</th>
                                    <th class="px-md py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-center w-[120px]">Status</th>
                                    <th class="px-md py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right w-[80px]">Action</th>
                                </tr>
                            </thead>
                            <tbody id="fees-tbody" class="font-body-md text-body-md text-on-surface bg-surface-container-lowest divide-y divide-outline-variant/50">
                                <tr><td colspan="7" class="px-md py-8 text-center text-secondary">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div id="pagination-container" class="px-md py-sm border-t border-outline-variant flex justify-between items-center bg-surface flex-wrap gap-2"></div>
                </div>
                </div> <!-- End Dashboard Tab -->

                <!-- Categories Tab -->
                <div id="tab-categories" class="tab-content hidden">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md mb-xl">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-md">Add Fee Category</h3>
                        <form action="{{ route('admin.fees.categories.store') }}" method="POST" class="flex flex-col md:flex-row gap-md items-end">
                            @csrf
                            <div class="flex-1 w-full">
                                <label class="block font-label-md text-secondary mb-xs">Category Name</label>
                                <input type="text" name="name" required class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-on-surface" placeholder="e.g. Tuition Fee">
                            </div>
                            <div class="flex-1 w-full">
                                <label class="block font-label-md text-secondary mb-xs">Description (Optional)</label>
                                <input type="text" name="description" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-on-surface" placeholder="Brief description">
                            </div>
                            <button type="submit" class="bg-primary text-on-primary px-lg py-2.5 rounded-lg font-label-md whitespace-nowrap">Add Category</button>
                        </form>
                    </div>

                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-surface-container-highest border-b border-outline-variant">
                                <tr>
                                    <th class="px-md py-3 font-label-md text-on-surface-variant uppercase">Name</th>
                                    <th class="px-md py-3 font-label-md text-on-surface-variant uppercase">Description</th>
                                    <th class="px-md py-3 font-label-md text-on-surface-variant uppercase text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/50">
                                @forelse($categories as $cat)
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="px-md py-3">{{ $cat->name }}</td>
                                    <td class="px-md py-3 text-secondary">{{ $cat->description ?: '-' }}</td>
                                    <td class="px-md py-3 text-right">
                                        <form action="{{ route('admin.fees.categories.destroy', $cat->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-error hover:opacity-80" data-confirm-click="Are you sure?"><span class="material-symbols-rounded text-[20px]">delete</span></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="px-md py-8 text-center text-secondary">No categories found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Fee Structures Tab -->
                <div id="tab-structures" class="tab-content hidden">
                    <form action="{{ route('admin.fees.structures.bulk') }}" method="POST" id="fee-matrix-form">
                        @csrf
                        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden flex flex-col mb-xl shadow-sm">
                            <!-- Professional Toolbar -->
                            <div class="px-md py-md border-b border-outline-variant flex flex-col md:flex-row justify-between items-start md:items-center bg-surface gap-md">
                                <div>
                                    <h3 class="font-headline-md text-headline-md text-on-surface flex items-center gap-2">
                                        <span class="material-symbols-rounded text-primary bg-primary-container p-1.5 rounded-md text-[20px]">table_chart</span>
                                        Fee Matrix
                                    </h3>
                                    <p class="text-body-sm text-secondary mt-1">Manage all class fee structures centrally. Empty fields are treated as zero.</p>
                                </div>
                                <div class="flex flex-wrap gap-sm">
                                    <button type="button" class="flex items-center gap-1.5 px-4 py-2 text-primary border border-primary rounded-lg font-label-md hover:bg-primary-container transition-colors bg-transparent" onclick="document.querySelector('[data-target=\'tab-categories\']').click()">
                                        <span class="material-symbols-rounded text-[18px]">add_circle</span> Add Category
                                    </button>
                                    <button type="button" class="flex items-center gap-1.5 px-4 py-2 text-secondary border border-outline-variant rounded-lg font-label-md hover:bg-surface-container hover:text-on-surface transition-colors bg-transparent">
                                        <span class="material-symbols-rounded text-[18px]">content_copy</span> Apply Template
                                    </button>
                                    <button type="submit" class="flex items-center gap-1.5 bg-primary text-on-primary px-5 py-2 rounded-lg font-label-md hover:bg-primary/90 transition-colors shadow-sm">
                                        <span class="material-symbols-rounded text-[18px]">save</span> Bulk Update
                                    </button>
                                </div>
                            </div>

                            <!-- Spreadsheet Matrix -->
                            <div class="overflow-x-auto w-full">
                                <table class="w-full text-left border-collapse min-w-max">
                                    <thead class="bg-surface-container-highest border-b border-outline-variant">
                                        <tr>
                                            <th class="px-md py-4 font-label-md text-on-surface-variant uppercase tracking-wider bg-surface-container-highest sticky left-0 z-20 border-r border-outline-variant w-[200px] shadow-[2px_0_4px_rgba(0,0,0,0.02)]">
                                                Class \ Category
                                            </th>
                                            @foreach($categories as $category)
                                                <th class="px-md py-4 font-label-md text-on-surface-variant uppercase tracking-wider text-right min-w-[140px] border-r border-outline-variant last:border-r-0">
                                                    {{ $category->name }}
                                                </th>
                                            @endforeach
                                            <th class="px-md py-4 font-label-md text-primary uppercase tracking-wider text-right w-[160px] bg-primary-container/10">
                                                Total Fee
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-outline-variant/50">
                                        @forelse($classes as $cls)
                                            <tr class="hover:bg-surface-container/50 transition-colors group">
                                                <td class="px-md py-3 font-medium text-on-surface bg-surface-container-lowest sticky left-0 z-10 border-r border-outline-variant group-hover:bg-surface-container/50 shadow-[2px_0_4px_rgba(0,0,0,0.02)]">
                                                    {{ $cls->name }}
                                                </td>
                                                @php $rowTotal = 0; @endphp
                                                @foreach($categories as $category)
                                                    @php
                                                        // Find existing amount if any
                                                        $existingStruct = $structures->first(function($s) use ($cls, $category) {
                                                            return $s->class_id == $cls->id && $s->fee_category_id == $category->id;
                                                        });
                                                        $amount = $existingStruct ? $existingStruct->amount : '';
                                                        $rowTotal += (float)$amount;
                                                    @endphp
                                                    <td class="px-sm py-2 border-r border-outline-variant last:border-r-0 relative group/cell">
                                                        <div class="flex items-center bg-transparent border border-transparent hover:border-outline-variant focus-within:border-primary focus-within:ring-1 focus-within:ring-primary rounded-md transition-all overflow-hidden">
                                                            <span class="text-secondary pl-2 text-sm select-none">₨</span>
                                                            <input type="number" 
                                                                   name="fees[{{ $cls->id }}][{{ $category->id }}]" 
                                                                   value="{{ $amount }}" 
                                                                   min="0" step="0.01" 
                                                                   class="fee-input w-full bg-transparent border-none px-2 py-1.5 text-right text-on-surface text-body-md focus:outline-none focus:ring-0 placeholder:text-on-surface-variant/30" 
                                                                   placeholder="0"
                                                                   data-row="{{ $cls->id }}">
                                                        </div>
                                                    </td>
                                                @endforeach
                                                <td class="px-md py-3 text-right font-semibold text-primary bg-primary-container/5">
                                                    <span class="text-xs text-primary/70 mr-1">₨</span>
                                                    <span class="row-total" id="total-{{ $cls->id }}">{{ number_format($rowTotal, 2) }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($categories) + 2 }}" class="px-md py-12 text-center">
                                                    <div class="flex flex-col items-center justify-center text-secondary">
                                                        <span class="material-symbols-rounded text-[48px] mb-2 opacity-50">meeting_room</span>
                                                        <p class="text-body-lg">No classes available.</p>
                                                        <p class="text-body-sm mt-1">Please add classes in the Academic section first.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Generate Invoices Tab -->
                <div id="tab-generate" class="tab-content hidden">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg mb-xl overflow-hidden">
                        
                        <div class="px-xl py-lg border-b border-outline-variant bg-surface flex items-start md:items-center gap-md">
                            <div class="w-12 h-12 bg-primary-fixed text-primary rounded-xl flex items-center justify-center shrink-0">
                                <span class="material-symbols-rounded text-[24px]">receipt_long</span>
                            </div>
                            <div>
                                <h3 class="font-headline-sm text-headline-sm text-on-surface">Bulk Invoice Generation</h3>
                                <p class="text-body-md font-body-md text-secondary mt-1">Generate fee invoices for an entire class based on defined fee structures.</p>
                            </div>
                        </div>

                        <div class="p-xl bg-surface-container-lowest">
                            <form id="bulk-generate-form" action="{{ route('admin.fees.bulk-generate') }}" method="POST" class="flex flex-col gap-lg">
                                @csrf
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                                    <!-- Class Selection -->
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-xs">Select Class <span class="text-error">*</span></label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="material-symbols-rounded text-secondary text-[20px] group-focus-within:text-primary transition-colors">meeting_room</span>
                                            </div>
                                            <select name="class_id" required class="form-input w-full bg-surface border border-outline-variant rounded-lg pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none cursor-pointer text-body-md">
                                                <option value="" disabled selected>-- Choose Class --</option>
                                                @foreach($classes as $cls)
                                                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="material-symbols-rounded text-secondary text-[20px]">expand_more</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Category Selection -->
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-xs">Select Fee Category <span class="text-error">*</span></label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="material-symbols-rounded text-secondary text-[20px] group-focus-within:text-primary transition-colors">category</span>
                                            </div>
                                            <select name="fee_category_id" required class="form-input w-full bg-surface border border-outline-variant rounded-lg pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none cursor-pointer text-body-md">
                                                <option value="" disabled selected>-- Choose Category --</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="material-symbols-rounded text-secondary text-[20px]">expand_more</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Due Date -->
                                    <div>
                                        <label class="block font-label-md text-on-surface-variant mb-xs">Due Date <span class="text-error">*</span></label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="material-symbols-rounded text-secondary text-[20px] group-focus-within:text-primary transition-colors">calendar_today</span>
                                            </div>
                                            <input type="date" name="due_date" required min="{{ date('Y-m-d') }}" class="form-input w-full bg-surface border border-outline-variant rounded-lg pl-10 pr-4 py-2.5 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer text-body-md">
                                        </div>
                                        <p class="text-[12px] text-secondary mt-1.5 flex items-center gap-1 font-body-sm">
                                            <span class="material-symbols-rounded text-[14px]">info</span>
                                            Due date cannot be in the past.
                                        </p>
                                    </div>
                                </div>

                                <!-- Separator -->
                                <div class="w-full h-px bg-outline-variant mt-sm"></div>

                                <!-- Actions -->
                                <div class="flex justify-end pt-sm">
                                    <button type="submit" id="btn-generate" disabled class="relative flex justify-center items-center gap-2 bg-primary text-on-primary px-xl py-2.5 rounded-lg font-label-md shadow-sm hover:bg-primary-container hover:text-on-primary-container disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                                        <span class="material-symbols-rounded text-[20px] btn-icon">flash_on</span>
                                        <span class="btn-text">Generate Invoices</span>
                                        <!-- Spinner -->
                                        <svg class="animate-spin hidden w-5 h-5 text-current btn-spinner absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>


<script>
    (function() {
        const tbody = document.getElementById('fees-tbody');
        let currentPage = 1;
        const limit = 10;
        
        window.loadFees = function(page = 1) {
            fetch(`/api/fees?page=${page}&limit=${limit}`)
                .then(res => res.json())
                .then(response => {
                    if (response.status === 'success') {
                        currentPage = page;
                        renderFees(response.data);
                        renderPagination(response.data.pagination);
                    }
                });
        }

        loadFees();

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-PK', { style: 'currency', currency: 'PKR', maximumSignificantDigits: 3 }).format(amount);
        }

        function renderFees(data) {
            document.getElementById('metric-collected').textContent = formatCurrency(data.metrics.collected);
            document.getElementById('metric-pending').textContent = formatCurrency(data.metrics.pending);
            document.getElementById('metric-pending-students').textContent = `across ${data.metrics.pending_students} students`;

            if (data.transactions.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="px-md py-8 text-center text-secondary">No transactions found.</td></tr>`;
                return;
            }

            let html = '';
            data.transactions.forEach((tx, index) => {
                const bgClass = index % 2 !== 0 ? 'bg-surface-container-low' : '';
                const initials = (tx.first_name[0] + (tx.last_name ? tx.last_name[0] : '')).toUpperCase();
                
                let statusBadge = '';
                if (tx.status === 'Paid') {
                    statusBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold tracking-wide bg-[#e6f4ea] text-[#137333]">Paid</span>`;
                } else if (tx.status === 'Overdue') {
                    statusBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold tracking-wide bg-error-container text-on-error-container">Overdue</span>`;
                } else {
                    statusBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-semibold tracking-wide bg-surface-variant text-on-surface-variant">Pending</span>`;
                }

                html += `
                <tr class="${bgClass} hover:bg-surface-container transition-colors group">
                    <td class="px-md py-3 font-medium text-secondary">#CH-${9000 + tx.id}</td>
                    <td class="px-md py-3">
                        <div class="flex items-center gap-sm">
                            <div class="w-8 h-8 rounded bg-secondary-container text-primary flex items-center justify-center font-label-md">${initials}</div>${tx.first_name} ${tx.last_name || ''}
                        </div>
                    </td>
                    <td class="px-md py-3">${tx.class_name || '-'} ${tx.section_name || ''}</td>
                    <td class="px-md py-3 text-secondary">${new Date(tx.due_date).toLocaleDateString()}</td>
                    <td class="px-md py-3 text-right font-medium">${Number(tx.amount).toLocaleString()}</td>
                    <td class="px-md py-3 text-center">${statusBadge}</td>
                    <td class="px-md py-3 text-right">
                        <button class="text-secondary hover:text-primary opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-rounded text-[20px]">more_vert</span>
                        </button>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }

        function renderPagination(pagination) {
            const container = document.getElementById('pagination-container');
            if (!container || !pagination) return;
            
            if (pagination.total === 0) {
                container.innerHTML = '';
                return;
            }
            
            const start = (pagination.current_page - 1) * pagination.per_page + 1;
            const end = Math.min(start + pagination.per_page - 1, pagination.total);
            
            let html = `
                <div class="font-body-sm text-secondary">
                    Showing ${start} to ${end} of ${pagination.total} entries
                </div>
                <div class="flex gap-1">
                    <button class="px-3 py-1 border border-outline-variant rounded-md text-secondary hover:bg-surface-container disabled:opacity-50 transition-colors" 
                        ${pagination.current_page === 1 ? 'disabled' : ''} onclick="loadFees(${pagination.current_page - 1})">
                        Previous
                    </button>
            `;
            
            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === pagination.current_page) {
                    html += `<button class="px-3 py-1 bg-primary text-on-primary rounded-md shadow-sm" onclick="loadFees(${i})">${i}</button>`;
                } else if (i === 1 || i === pagination.last_page || Math.abs(i - pagination.current_page) <= 1) {
                    html += `<button class="px-3 py-1 border border-outline-variant rounded-md text-secondary hover:bg-surface-container transition-colors" onclick="loadFees(${i})">${i}</button>`;
                } else if (Math.abs(i - pagination.current_page) === 2) {
                    html += `<span class="px-2 py-1 text-secondary">...</span>`;
                }
            }
            
            html += `
                    <button class="px-3 py-1 border border-outline-variant rounded-md text-secondary hover:bg-surface-container disabled:opacity-50 transition-colors" 
                        ${pagination.current_page === pagination.last_page ? 'disabled' : ''} onclick="loadFees(${pagination.current_page + 1})">
                        Next
                    </button>
                </div>
            `;
            
            container.innerHTML = html;
        }

        // Tab Switching Logic
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        function activateTab(target) {
            // Update buttons
            tabBtns.forEach(b => {
                b.classList.remove('bg-primary', 'text-white', 'active');
                b.classList.add('text-secondary', 'hover:bg-surface-container', 'hover:text-on-surface');
            });
            const activeBtn = document.querySelector(`.tab-btn[data-target="${target}"]`);
            if (activeBtn) {
                activeBtn.classList.remove('text-secondary', 'hover:bg-surface-container', 'hover:text-on-surface');
                activeBtn.classList.add('bg-primary', 'text-white', 'active');
            }

            // Update contents
            tabContents.forEach(c => {
                c.classList.add('hidden');
            });
            const activeContent = document.getElementById(target);
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
            
            // Save state
            sessionStorage.setItem('activeFeeTab', target);
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                activateTab(btn.dataset.target);
            });
        });

        // Restore active tab
        const savedTab = sessionStorage.getItem('activeFeeTab');
        if (savedTab) {
            activateTab(savedTab);
        }

        // Form Validation & Interaction Logic for Bulk Generate
        const generateForm = document.getElementById('bulk-generate-form');
        const generateInputs = generateForm.querySelectorAll('.form-input');
        const btnGenerate = document.getElementById('btn-generate');

        function checkFormValidity() {
            let isValid = true;
            generateInputs.forEach(input => {
                if (!input.value) {
                    isValid = false;
                }
            });
            btnGenerate.disabled = !isValid;
        }

        generateInputs.forEach(input => {
            input.addEventListener('change', checkFormValidity);
            input.addEventListener('input', checkFormValidity);
        });

        if (generateForm) {
            generateForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const classSelect = generateForm.querySelector('select[name="class_id"]');
                const categorySelect = generateForm.querySelector('select[name="fee_category_id"]');
                const selectedCategoryName = categorySelect.options[categorySelect.selectedIndex].text;
                
                let countMessage = 'Are you sure you want to generate invoices for the selected class?';
                
                // If it's tuition fees, check how many students have is_tuition=1
                if (selectedCategoryName.toLowerCase().includes('tuition')) {
                    try {
                        const res = await fetch(`/api/students?class_id=${classSelect.value}&is_tuition=1`);
                        const data = await res.json();
                        if (data.status === 'success') {
                            const count = data.data.length;
                            countMessage = `Are you sure? ${count} Tuition Student(s) found in the selected class. Invoices will ONLY be generated for them.`;
                        }
                    } catch (err) {
                        console.error('Could not fetch student count', err);
                    }
                } else {
                    try {
                        const res = await fetch(`/api/students?class_id=${classSelect.value}`);
                        const data = await res.json();
                        if (data.status === 'success') {
                            const count = data.data.length;
                            countMessage = `Are you sure? ${count} Student(s) found in the selected class. Invoices will be generated for all of them.`;
                        }
                    } catch (err) {
                        console.error('Could not fetch student count', err);
                    }
                }

                const confirmed = await window.UI.confirm('Confirm Bulk Generation', countMessage, 'Generate', 'primary');
                
                if (confirmed) {
                    if (btnGenerate) {
                        btnGenerate.disabled = true;
                        const icon = btnGenerate.querySelector('.btn-icon');
                        if (icon) icon.classList.add('hidden');
                        const text = btnGenerate.querySelector('.btn-text');
                        if (text) text.textContent = 'Generating...';
                        const spinner = btnGenerate.querySelector('.btn-spinner');
                        if (spinner) spinner.classList.remove('hidden');
                    }
                    generateForm.submit();
                }
            });
        }

        // Fee Matrix Calculations
        const feeInputs = document.querySelectorAll('.fee-input');
        
        function updateRowTotal(rowId) {
            const inputs = document.querySelectorAll(`.fee-input[data-row="${rowId}"]`);
            let total = 0;
            inputs.forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val)) {
                    total += val;
                }
            });
            const totalEl = document.getElementById(`total-${rowId}`);
            if (totalEl) {
                totalEl.textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }

        feeInputs.forEach(input => {
            input.addEventListener('input', function() {
                updateRowTotal(this.dataset.row);
            });
        });

        // Matrix Form Submit State
        const matrixForm = document.getElementById('fee-matrix-form');
        if (matrixForm) {
            matrixForm.addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    setTimeout(() => {
                        btn.disabled = true;
                        btn.innerHTML = `<span class="material-symbols-rounded text-[18px] animate-spin">refresh</span> Updating...`;
                    }, 10);
                }
            });
        }
    })();
</script>
        </main>
@endsection
