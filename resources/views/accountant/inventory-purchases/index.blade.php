@extends('layouts.app')

@section('title', 'Inventory Purchases')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Breadcrumb & Page Header -->
        <div class="flex flex-col gap-2">
            <nav class="flex text-label-md text-secondary" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('accountant.dashboard') }}" class="inline-flex items-center hover:text-primary transition-colors">
                            <span class="material-symbols-rounded text-[16px] mr-1">home</span>
                            Accountant Portal
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-rounded text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Financial Operations</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-rounded text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Inventory Purchases</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Inventory Purchases</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Record purchases and automatically update ledger</p>
                </div>
                <button onclick="document.getElementById('addPurchaseModal').showModal()" class="btn-primary shadow-sm flex items-center gap-2">
                    <span class="material-symbols-rounded text-[20px]">add</span>
                    Record Purchase
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 relative flex items-center gap-3" role="alert">
            <span class="material-symbols-rounded text-emerald-600">check_circle</span>
            <div><span class="font-semibold">Success!</span> {{ session('success') }}</div>
        </div>
        @endif

        <!-- Table Section -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-xs font-semibold text-secondary uppercase tracking-wider border-b border-outline-variant">
                            <th class="py-4 px-6">Date</th>
                            <th class="py-4 px-6">Supplier</th>
                            <th class="py-4 px-6">Invoice No</th>
                            <th class="py-4 px-6 text-right">Total Amount</th>
                            <th class="py-4 px-6">Payment Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md divide-y divide-outline-variant">
                        @forelse($purchases as $purchase)
                        <tr class="hover:bg-surface-container-lowest transition-colors group">
                            <td class="py-4 px-6 font-medium text-on-surface">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-surface-variant text-on-surface-variant flex items-center justify-center border border-outline-variant">
                                        <span class="material-symbols-rounded text-[16px]">calendar_today</span>
                                    </div>
                                    {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-on-surface text-body-lg">{{ $purchase->supplier_name }}</div>
                            </td>
                            <td class="py-4 px-6 text-secondary font-mono">{{ $purchase->invoice_number }}</td>
                            <td class="py-4 px-6 text-right font-bold text-error text-body-lg">{{ number_format($purchase->total_amount, 2) }}</td>
                            <td class="py-4 px-6">
                                @if($purchase->payment_status == 'Paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                    </span>
                                @elseif($purchase->payment_status == 'Partial')
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
                                <div class="flex justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <button class="text-secondary bg-surface hover:text-primary hover:bg-primary/10 border border-outline-variant hover:border-primary/30 p-2 rounded-lg transition-colors tooltip" data-tip="View Details">
                                        <span class="material-symbols-rounded text-[20px]">visibility</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-secondary">
                                <span class="material-symbols-rounded text-5xl mb-3 text-outline">shopping_cart</span>
                                <p class="text-body-lg font-medium text-on-surface">No inventory purchases recorded yet.</p>
                                <p class="text-body-md mt-1">Click "Record Purchase" to add a new inventory order.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($purchases->hasPages())
            <div class="p-4 bg-surface-bright border-t border-outline-variant">
                {{ $purchases->links() }}
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Add Modal -->
<dialog id="addPurchaseModal" class="bg-surface-container-lowest rounded-xl shadow-2xl border border-outline-variant p-0 w-full max-w-lg backdrop:bg-black/50 backdrop:backdrop-blur-sm transition-all">
    <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-bright rounded-t-xl">
        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                <span class="material-symbols-rounded text-[20px]">shopping_cart</span>
            </div>
            Record Purchase
        </h3>
        <form method="dialog"><button class="text-secondary hover:bg-surface-container p-1 rounded-full transition-colors"><span class="material-symbols-rounded">close</span></button></form>
    </div>
    <form method="POST" action="{{ route('accountant.inventory-purchases.store') }}">
        @csrf
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Purchase Date</label>
                    <input type="date" name="purchase_date" class="input-field bg-surface" value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Invoice Number</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-rounded text-[20px]">tag</span>
                        <input type="text" name="invoice_number" class="input-field pl-10 bg-surface" required>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Supplier Name</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-rounded text-[20px]">store</span>
                    <input type="text" name="supplier_name" class="input-field pl-10 bg-surface" required>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Total Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary font-semibold"></span>
                    <input type="number" step="0.01" name="total_amount" class="input-field pl-8 bg-surface text-lg font-medium" min="0" required>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Payment Status</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-rounded text-[20px]">payments</span>
                    <select name="payment_status" class="input-field pl-10 bg-surface" required>
                        <option value="Paid">Paid</option>
                        <option value="Unpaid">Unpaid</option>
                        <option value="Partial">Partial</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Notes</label>
                <textarea name="notes" class="input-field bg-surface" rows="2"></textarea>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex gap-3 text-blue-800 mt-2">
                <span class="material-symbols-rounded text-blue-600">info</span>
                <span class="text-sm font-medium">Recording this purchase will automatically create a Ledger Entry for expense tracking.</span>
            </div>
        </div>
        <div class="p-6 border-t border-outline-variant flex justify-end gap-3 bg-surface-bright rounded-b-xl">
            <button type="button" onclick="document.getElementById('addPurchaseModal').close()" class="btn-outline px-6">Cancel</button>
            <button type="submit" class="btn-primary px-6">Save Purchase</button>
        </div>
    </form>
</dialog>
@endsection
