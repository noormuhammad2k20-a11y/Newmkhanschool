@extends('layouts.app')

@section('title', 'Fee Collection')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Breadcrumb & Page Header -->
        <div class="flex flex-col gap-2">
            <nav class="flex text-label-md text-secondary" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('accountant.dashboard') }}" class="inline-flex items-center hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[16px] mr-1">home</span>
                            Accountant Portal
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Fee Management</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Fee Collection</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Fee Collection</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Manage and collect student fees</p>
                </div>
                <button onclick="document.getElementById('generateChallansModal').showModal()" class="btn-primary shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">receipt</span>
                    Generate Challans
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 relative flex items-center gap-3" role="alert">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <div><span class="font-semibold">Success!</span> {{ session('success') }}</div>
        </div>
        @endif

        <!-- Filters Section -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
            <form method="GET" action="{{ route('accountant.fees.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[240px]">
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Search Student / Challan</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" class="input-field pl-10 bg-surface focus:bg-surface-container-lowest transition-colors w-full" placeholder="Search by name, ID, or challan number...">
                    </div>
                </div>
                <div class="w-56">
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Status</label>
                    <select name="status" class="input-field bg-surface focus:bg-surface-container-lowest transition-colors w-full">
                        <option value="">All Statuses</option>
                        <option value="Unpaid" {{ request('status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <button type="submit" class="btn-outline">
                    Filter Results
                </button>
            </form>
        </div>

        <!-- Table Section -->
        <div id="fees-table-container" class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-xs font-semibold text-secondary uppercase tracking-wider border-b border-outline-variant">
                            <th class="py-4 px-6">Challan No</th>
                            <th class="py-4 px-6">Student</th>
                            <th class="py-4 px-6">Category</th>
                            <th class="py-4 px-6">Due Date</th>
                            <th class="py-4 px-6 text-right">Amount</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md divide-y divide-outline-variant">
                        @forelse($fees as $fee)
                        <tr class="hover:bg-surface-container-lowest transition-colors group">
                            <td class="py-4 px-6 text-on-surface font-semibold tracking-wide">{{ $fee->challan_no }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 border border-primary/20 text-primary flex items-center justify-center font-bold text-sm uppercase">{{ substr($fee->student->first_name, 0, 1) }}{{ substr($fee->student->last_name ?? '', 0, 1) }}</div>
                                    <div>
                                        <div class="font-medium text-on-surface">{{ $fee->student->first_name }} {{ $fee->student->last_name }}</div>
                                        <div class="text-xs text-secondary mt-0.5">{{ $fee->student->currentClass->name ?? '' }} - {{ $fee->student->currentSection->name ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-secondary">
                                @php
                                    $catName = $fee->category->name ?? $fee->fee_category ?? 'General';
                                    $catLower = strtolower($catName);
                                    $badgeClass = 'bg-slate-50 text-slate-700 border-slate-200'; // Default
                                    
                                    if (str_contains($catLower, 'tuition')) {
                                        $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                    } elseif (str_contains($catLower, 'transport')) {
                                        $badgeClass = 'bg-purple-50 text-purple-700 border-purple-200';
                                    } elseif (str_contains($catLower, 'library')) {
                                        $badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                    } elseif (str_contains($catLower, 'exam') || str_contains($catLower, 'test')) {
                                        $badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
                                    } elseif (str_contains($catLower, 'admission')) {
                                        $badgeClass = 'bg-teal-50 text-teal-700 border-teal-200';
                                    } elseif (str_contains($catLower, 'sport') || str_contains($catLower, 'activity')) {
                                        $badgeClass = 'bg-pink-50 text-pink-700 border-pink-200';
                                    } elseif (str_contains($catLower, 'lab') || str_contains($catLower, 'computer')) {
                                        $badgeClass = 'bg-cyan-50 text-cyan-700 border-cyan-200';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border {{ $badgeClass }}">
                                    {{ $catName }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-1.5 text-secondary {{ \Carbon\Carbon::parse($fee->due_date)->isPast() && $fee->status != 'Paid' ? 'text-error font-medium' : '' }}">
                                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                    {{ \Carbon\Carbon::parse($fee->due_date)->format('d M, Y') }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="font-bold text-on-surface">{{ number_format($fee->amount, 2) }}</div>
                                @if($fee->paid_amount > 0)
                                <div class="text-xs text-emerald-600 font-medium mt-1">Paid: {{ number_format($fee->paid_amount, 2) }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($fee->status == 'Paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                    </span>
                                @elseif($fee->status == 'Partial')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Partial
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-error border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-error"></span> Unpaid
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('accountant.fees.receipt', $fee->id) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors" title="Print Receipt">
                                        <span class="material-symbols-outlined text-[18px]">print</span>
                                    </a>
                                    @if($fee->status != 'Paid')
                                        <button onclick="openCollectModal({{ $fee->id }}, {{ $fee->amount - $fee->paid_amount }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-md transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">payments</span>
                                            Collect
                                        </button>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 rounded-md">
                                            <span class="material-symbols-outlined text-[16px]">check</span>
                                            Cleared
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-secondary">
                                <span class="material-symbols-outlined text-5xl mb-3 text-outline">receipt_long</span>
                                <p class="text-body-lg font-medium text-on-surface">No fee records found</p>
                                <p class="text-body-md mt-1">Try adjusting your filters or search query.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($fees->hasPages())
            <div class="p-4 bg-surface-bright border-t border-outline-variant">
                {{ $fees->links() }}
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Collect Payment Modal -->
<dialog id="collectPaymentModal" class="bg-surface-container-lowest rounded-2xl shadow-xl border border-outline-variant p-0 w-full max-w-md backdrop:bg-on-surface/50 backdrop:backdrop-blur-sm transition-all m-auto">
    <div class="p-6 border-b border-outline-variant flex justify-between items-center">
        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-[24px] text-emerald-600">payments</span>
            Collect Fee Payment
        </h3>
        <button type="button" onclick="document.getElementById('collectPaymentModal').close()" class="text-secondary hover:bg-surface-container-high p-2 rounded-full transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>
    <form id="collectPaymentForm" method="POST" action="">
        @csrf
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Amount to Collect</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface font-bold text-lg"></span>
                    <input type="number" step="0.01" name="amount" id="collectAmount" class="input-field !pl-9 bg-surface text-lg font-bold" required>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Payment Method</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px] pointer-events-none">account_balance_wallet</span>
                    <select name="gateway" class="input-field !pl-10 !pr-10 bg-surface appearance-none" required>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Online">Online Gateway</option>
                    </select>
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px] pointer-events-none">expand_more</span>
                </div>
            </div>
        </div>
        <div class="p-6 pt-0 flex justify-end gap-3">
            <button type="button" onclick="document.getElementById('collectPaymentModal').close()" class="btn-outline px-6">Cancel</button>
            <button type="submit" class="btn-primary px-6">Record Payment</button>
        </div>
    </form>
</dialog>

<!-- Generate Challans Modal -->
<dialog id="generateChallansModal" class="bg-surface-container-lowest rounded-2xl shadow-xl border border-outline-variant p-0 w-full max-w-md backdrop:bg-on-surface/50 backdrop:backdrop-blur-sm transition-all m-auto">
    <div class="p-6 border-b border-outline-variant flex justify-between items-center">
        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-[24px] text-primary">receipt</span>
            Generate Challans
        </h3>
        <button type="button" onclick="document.getElementById('generateChallansModal').close()" class="text-secondary hover:bg-surface-container-high p-2 rounded-full transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>
    <form method="POST" action="{{ route('accountant.fees.generate-challans') }}">
        @csrf
        <div class="p-6">
            <p class="text-body-md text-secondary">Are you sure you want to generate fee challans for the current month? This will create unpaid fee records for all active students based on their assigned fee structures.</p>
        </div>
        <div class="p-6 pt-0 flex justify-end gap-3">
            <button type="button" onclick="document.getElementById('generateChallansModal').close()" class="btn-outline px-6">Cancel</button>
            <button type="submit" class="btn-primary px-6">Generate</button>
        </div>
    </form>
</dialog>

<script>
function openCollectModal(feeId, dueAmount) {
    const modal = document.getElementById('collectPaymentModal');
    const form = document.getElementById('collectPaymentForm');
    const amountInput = document.getElementById('collectAmount');
    
    // Bulletproof URL generation for Laravel to avoid posting to the current URL
    const baseUrl = "{{ url('/accountant/fees') }}";
    form.action = `${baseUrl}/${feeId}/collect`;
    
    amountInput.value = dueAmount;
    amountInput.max = dueAmount;
    
    modal.showModal();
}

document.getElementById('collectPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Processing...';
    submitBtn.disabled = true;

    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Close modal
            document.getElementById('collectPaymentModal').close();
            
            // Refresh table via AJAX
            refreshTable();
            
            // The global interceptor in layouts.app handles the top toast notification automatically.
        } else {
            alert(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the payment.');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

function refreshTable() {
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newTable = doc.getElementById('fees-table-container');
        if(newTable) {
            document.getElementById('fees-table-container').innerHTML = newTable.innerHTML;
        }
    });
}
</script>
@endsection
