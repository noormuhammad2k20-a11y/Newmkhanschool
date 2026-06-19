@extends('layouts.app')

@section('title', 'Cash Book')

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
                            <span class="text-on-surface">Cash Book</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Cash Book</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Manage general ledger entries and cash flow</p>
                </div>
                <button onclick="document.getElementById('addEntryModal').showModal()" class="btn-primary shadow-sm flex items-center gap-2">
                    <span class="material-symbols-rounded text-[20px]">add</span>
                    Add Entry
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-outline-variant gap-8">
            <a href="{{ route('accountant.bank-accounts.index') }}" class="px-2 py-4 border-b-2 border-transparent font-label-lg text-secondary hover:text-on-surface transition-colors font-medium">Bank Accounts</a>
            <a href="{{ route('accountant.cash-book.index') }}" class="px-2 py-4 border-b-2 font-label-lg transition-colors border-primary text-primary font-medium">Cash Book</a>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 relative flex items-center gap-3" role="alert">
            <span class="material-symbols-rounded text-emerald-600">check_circle</span>
            <div><span class="font-semibold">Success!</span> {{ session('success') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 mb-4 text-sm text-error rounded-xl bg-error-container border border-error relative flex items-start gap-3">
            <span class="material-symbols-rounded text-error">error</span>
            <ul class="list-disc ml-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Stats -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-rounded">arrow_downward</span>
                </div>
                <div>
                    <p class="text-label-md text-secondary uppercase font-semibold">Total Incoming (Credit)</p>
                    <p class="text-headline-md font-bold text-on-surface mt-1">{{ number_format($totalCredit, 2) }}</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-rounded">arrow_upward</span>
                </div>
                <div>
                    <p class="text-label-md text-secondary uppercase font-semibold">Total Outgoing (Debit)</p>
                    <p class="text-headline-md font-bold text-on-surface mt-1">{{ number_format($totalDebit, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <form method="GET" action="{{ route('accountant.cash-book.index') }}" class="flex items-center gap-3">
                <label class="font-label-md text-secondary">Month:</label>
                <input type="month" name="month" value="{{ $month }}" class="input-field py-1 px-3 bg-surface border-outline-variant" onchange="this.form.submit()">
            </form>
        </div>

        <!-- Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-xs font-semibold text-secondary uppercase tracking-wider border-b border-outline-variant">
                            <th class="py-4 px-6">Date</th>
                            <th class="py-4 px-6">Description</th>
                            <th class="py-4 px-6">Linked Account</th>
                            <th class="py-4 px-6 text-right">Debit (Out)</th>
                            <th class="py-4 px-6 text-right">Credit (In)</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md divide-y divide-outline-variant">
                        @forelse($entries as $entry)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="py-4 px-6 text-secondary">{{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}</td>
                            <td class="py-4 px-6 font-medium text-on-surface">{{ $entry->description }}</td>
                            <td class="py-4 px-6 text-secondary">
                                @if($entry->bankAccount)
                                    <span class="inline-flex items-center gap-1"><span class="material-symbols-rounded text-[16px]">account_balance</span> {{ $entry->bankAccount->account_name }}</span>
                                @else
                                    Cash / Manual
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right text-amber-600 font-medium">
                                {{ $entry->type == 'Debit' ? number_format($entry->amount, 2) : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right text-emerald-600 font-medium">
                                {{ $entry->type == 'Credit' ? number_format($entry->amount, 2) : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-secondary">
                                <span class="material-symbols-rounded text-4xl mb-2 text-outline">receipt_long</span>
                                <p class="text-body-lg font-medium">No ledger entries found for this month.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($entries->hasPages())
            <div class="p-4 border-t border-outline-variant bg-surface-bright">
                {{ $entries->links() }}
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Add Entry Modal -->
<dialog id="addEntryModal" class="bg-surface-container-lowest rounded-xl shadow-2xl border border-outline-variant p-0 w-full max-w-lg backdrop:bg-black/50 backdrop:backdrop-blur-sm transition-all">
    <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-bright rounded-t-xl">
        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-rounded text-[20px]">add_notes</span>
            </div>
            Add Ledger Entry
        </h3>
        <form method="dialog"><button class="text-secondary hover:bg-surface-container p-1 rounded-full transition-colors"><span class="material-symbols-rounded">close</span></button></form>
    </div>
    <form method="POST" action="{{ route('accountant.cash-book.store') }}">
        @csrf
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Date</label>
                    <input type="date" name="date" class="input-field bg-surface" value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Entry Type</label>
                    <select name="type" class="input-field bg-surface" required>
                        <option value="Credit">Credit (Income / In)</option>
                        <option value="Debit">Debit (Expense / Out)</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary font-semibold"></span>
                    <input type="number" step="0.01" name="amount" class="input-field pl-8 bg-surface font-medium" placeholder="0.00" min="0.01" required>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Description / Particulars</label>
                <input type="text" name="description" class="input-field bg-surface" placeholder="E.g. Cash Sale, Petty Cash, Vendor Payment" required>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Link Bank Account (Optional)</label>
                <select name="bank_account_id" class="input-field bg-surface">
                    <option value="">-- None (Cash Transaction) --</option>
                    @foreach($bankAccounts as $account)
                        <option value="{{ $account->id }}">{{ $account->account_name }} ({{ $account->bank_name }}) - Bal: {{ number_format($account->current_balance, 2) }}</option>
                    @endforeach
                </select>
                <p class="text-label-sm text-secondary mt-1">If selected, the bank account balance will be automatically updated.</p>
            </div>
        </div>
        <div class="p-6 border-t border-outline-variant flex justify-end gap-3 bg-surface-bright rounded-b-xl">
            <button type="button" onclick="document.getElementById('addEntryModal').close()" class="btn-outline px-6">Cancel</button>
            <button type="submit" class="btn-primary px-6">Save Entry</button>
        </div>
    </form>
</dialog>
@endsection
