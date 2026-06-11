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

                <div class="flex flex-nowrap gap-6 border-b border-outline-variant mb-xl overflow-x-auto whitespace-nowrap hide-scrollbar pb-1">
                    <button class="tab-btn shrink-0 active px-2 py-3 font-label-md text-body-md text-primary border-b-2 border-primary transition-colors" data-target="tab-dashboard">Dashboard</button>
                    <button class="tab-btn shrink-0 px-2 py-3 font-label-md text-body-md text-secondary hover:text-on-surface transition-colors" data-target="tab-categories">Categories</button>
                    <button class="tab-btn shrink-0 px-2 py-3 font-label-md text-body-md text-secondary hover:text-on-surface transition-colors" data-target="tab-structures">Fee Structures</button>
                    <button class="tab-btn shrink-0 px-2 py-3 font-label-md text-body-md text-secondary hover:text-on-surface transition-colors" data-target="tab-generate">Generate</button>
                </div>



<div id="tab-dashboard" class="tab-content">
                <!-- Metrics Bento Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-xl">
                    <!-- Card 1: Total Collected -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col justify-between h-32 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-secondary-container rounded-bl-full opacity-20 -z-0 pointer-events-none transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Total Collected (YTD)</span>
                            <span class="material-symbols-outlined text-primary bg-primary-fixed p-1.5 rounded-md text-[20px]">account_balance_wallet</span>
                        </div>
                        <div class="relative z-10 flex items-baseline gap-sm mt-auto">
                            <span id="metric-collected" class="font-headline-xl text-headline-xl text-on-surface">₨0</span>
                            <span class="font-label-md text-label-md text-[#137333] bg-[#e6f4ea] px-1.5 py-0.5 rounded flex items-center">
                                <span class="material-symbols-outlined text-[14px]">arrow_upward</span> 12%
                            </span>
                        </div>
                    </div>
                    <!-- Card 2: Pending Dues -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col justify-between h-32 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-error-container rounded-bl-full opacity-20 -z-0 pointer-events-none transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Pending Dues</span>
                            <span class="material-symbols-outlined text-error bg-error-container p-1.5 rounded-md text-[20px]">warning</span>
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
                                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                            </button>
                            <button class="p-1.5 text-secondary hover:bg-surface-container rounded transition-colors">
                                <span class="material-symbols-outlined text-[20px]">download</span>
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
                                            <button type="submit" class="text-error hover:opacity-80" data-confirm-click="Are you sure?"><span class="material-symbols-outlined text-[20px]">delete</span></button>
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
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md mb-xl">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-md">Set Class Fee Structure</h3>
                        <form action="{{ route('admin.fees.structures.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-md items-end">
                            @csrf
                            <div>
                                <label class="block font-label-md text-secondary mb-xs">Class</label>
                                <select name="class_id" required class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-on-surface">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $cls)
                                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-label-md text-secondary mb-xs">Category</label>
                                <select name="fee_category_id" required class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-on-surface">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-label-md text-secondary mb-xs">Amount (₨)</label>
                                <input type="number" name="amount" required min="0" step="0.01" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-on-surface" placeholder="e.g. 5000">
                            </div>
                            <button type="submit" class="bg-primary text-on-primary px-lg py-2.5 rounded-lg font-label-md whitespace-nowrap h-[42px]">Save Structure</button>
                        </form>
                    </div>

                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-surface-container-highest border-b border-outline-variant">
                                <tr>
                                    <th class="px-md py-3 font-label-md text-on-surface-variant uppercase">Class</th>
                                    <th class="px-md py-3 font-label-md text-on-surface-variant uppercase">Fee Category</th>
                                    <th class="px-md py-3 font-label-md text-on-surface-variant uppercase text-right">Amount (₨)</th>
                                    <th class="px-md py-3 font-label-md text-on-surface-variant uppercase text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/50">
                                @forelse($structures as $struct)
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="px-md py-3 font-medium">{{ $struct->class->name }}</td>
                                    <td class="px-md py-3">{{ $struct->category->name }}</td>
                                    <td class="px-md py-3 text-right">₨{{ number_format($struct->amount, 2) }}</td>
                                    <td class="px-md py-3 text-right">
                                        <form action="{{ route('admin.fees.structures.destroy', $struct->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-error hover:opacity-80" data-confirm-click="Are you sure?"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-md py-8 text-center text-secondary">No fee structures defined.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Generate Invoices Tab -->
                <div id="tab-generate" class="tab-content hidden">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm p-8 max-w-2xl mx-auto mt-6">
                        <div class="text-center mb-8 pb-6 border-b border-outline-variant">
                            <div class="w-16 h-16 bg-primary-container text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 rotate-3 hover:rotate-0 transition-transform">
                                <span class="material-symbols-outlined text-[32px]">receipt_long</span>
                            </div>
                            <h2 class="text-2xl font-bold text-on-surface">Bulk Invoice Generation</h2>
                            <p class="text-secondary mt-2">Generate fee invoices for an entire class based on defined fee structures.</p>
                        </div>

                        <form id="bulk-generate-form" action="{{ route('admin.fees.bulk-generate') }}" method="POST" class="space-y-6">
                            @csrf
                            <!-- Class Selection -->
                            <div>
                                <label class="block font-label-md text-on-surface-variant mb-2">Select Class <span class="text-error">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="material-symbols-outlined text-secondary text-[20px] group-focus-within:text-primary transition-colors">meeting_room</span>
                                    </div>
                                    <select name="class_id" required class="form-input w-full bg-surface border border-outline-variant rounded-xl pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none cursor-pointer">
                                        <option value="" disabled selected>-- Choose Class --</option>
                                        @foreach($classes as $cls)
                                            <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="material-symbols-outlined text-secondary text-[20px]">expand_more</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Category Selection -->
                            <div>
                                <label class="block font-label-md text-on-surface-variant mb-2">Select Fee Category <span class="text-error">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="material-symbols-outlined text-secondary text-[20px] group-focus-within:text-primary transition-colors">category</span>
                                    </div>
                                    <select name="fee_category_id" required class="form-input w-full bg-surface border border-outline-variant rounded-xl pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none cursor-pointer">
                                        <option value="" disabled selected>-- Choose Category --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="material-symbols-outlined text-secondary text-[20px]">expand_more</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label class="block font-label-md text-on-surface-variant mb-2">Due Date <span class="text-error">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="material-symbols-outlined text-secondary text-[20px] group-focus-within:text-primary transition-colors">calendar_today</span>
                                    </div>
                                    <input type="date" name="due_date" required min="{{ date('Y-m-d') }}" class="form-input w-full bg-surface border border-outline-variant rounded-xl pl-10 pr-4 py-3 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all cursor-pointer">
                                </div>
                                <p class="text-xs text-secondary mt-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">info</span>
                                    Due date cannot be in the past.
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" id="btn-generate" disabled class="w-full relative flex justify-center items-center gap-2 bg-primary text-on-primary py-3.5 rounded-xl font-label-lg shadow-sm hover:bg-primary-container hover:text-on-primary-container disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-primary disabled:hover:text-on-primary transition-all">
                                    <span class="material-symbols-outlined text-[20px] btn-icon">flash_on</span>
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

        </main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('fees-tbody');
        
        fetch(`/api/fees`)
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    renderFees(response.data);
                }
            });

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
                            <div class="w-8 h-8 rounded bg-secondary-container text-primary flex items-center justify-center font-label-md">${initials}</div>
                            ${tx.first_name} ${tx.last_name || ''}
                        </div>
                    </td>
                    <td class="px-md py-3">${tx.class_name || '-'} ${tx.section_name || ''}</td>
                    <td class="px-md py-3 text-secondary">${new Date(tx.due_date).toLocaleDateString()}</td>
                    <td class="px-md py-3 text-right font-medium">${Number(tx.amount).toLocaleString()}</td>
                    <td class="px-md py-3 text-center">${statusBadge}</td>
                    <td class="px-md py-3 text-right">
                        <button class="text-secondary hover:text-primary opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-[20px]">more_vert</span>
                        </button>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }

        // Tab Switching Logic
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.target;
                
                // Update buttons
                tabBtns.forEach(b => {
                    b.classList.remove('text-primary', 'border-b-2', 'border-primary', 'active');
                    b.classList.add('text-secondary');
                });
                btn.classList.remove('text-secondary');
                btn.classList.add('text-primary', 'border-b-2', 'border-primary', 'active');

                // Update contents
                tabContents.forEach(c => {
                    c.classList.add('hidden');
                });
                document.getElementById(target).classList.remove('hidden');
            });
        });

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

        generateForm.addEventListener('submit', function(e) {
            // UI Loading State
            btnGenerate.disabled = true;
            btnGenerate.querySelector('.btn-icon').classList.add('hidden');
            btnGenerate.querySelector('.btn-text').textContent = 'Generating...';
            btnGenerate.querySelector('.btn-spinner').classList.remove('hidden');
        });
    });
</script>
@endsection
