@extends('layouts.app')

@section('title', 'Fee Management')

@section('content')
        <main class="flex-1 overflow-y-auto bg-background p-margin-desktop w-full max-w-[1440px] mx-auto">
            <div class="max-w-max-width mx-auto">
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
                    <div>
                        <h2 class="font-headline-xl text-headline-xl text-on-surface mb-xs">Fee Management</h2>
                        <p class="font-body-md text-body-md text-secondary">Monitor collections, dues, and generate payment challans.</p>
                    </div>
                    <button class="bg-primary text-on-primary px-lg py-2.5 rounded-lg font-label-md text-label-md hover:opacity-90 transition-opacity flex items-center gap-sm shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                        Generate Challan
                    </button>
                </div>
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
                            <span id="metric-collected" class="font-headline-xl text-headline-xl text-on-surface">₹0</span>
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
                            <span id="metric-pending" class="font-headline-xl text-headline-xl text-on-surface">₹0</span>
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
                                    <th class="px-md py-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right w-[150px]">Amount (₹)</th>
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
            return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumSignificantDigits: 3 }).format(amount);
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
    });
</script>
@endsection
