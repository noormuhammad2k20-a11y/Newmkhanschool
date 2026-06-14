@extends('layouts.app')

@section('title', 'Bank Accounts')

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
                            <span class="text-on-surface">Financial Operations</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Bank Accounts</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Bank Accounts</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Manage school bank accounts and balances</p>
                </div>
                <button onclick="document.getElementById('addBankAccountModal').showModal()" class="btn-primary shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Add Bank Account
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 relative flex items-center gap-3" role="alert">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <div><span class="font-semibold">Success!</span> {{ session('success') }}</div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($accounts as $account)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 flex flex-col relative overflow-hidden shadow-sm hover:shadow-md transition-shadow group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -z-10 group-hover:bg-primary/10 transition-colors"></div>
                <div class="flex justify-between items-start mb-6 z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 text-primary flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-[24px]">account_balance</span>
                        </div>
                        <div>
                            <h3 class="text-title-lg font-bold text-on-surface leading-tight">{{ $account->account_name }}</h3>
                            <p class="text-label-md text-secondary mt-0.5">{{ $account->bank_name }}</p>
                        </div>
                    </div>
                    <div class="flex gap-1 z-10 opacity-80 group-hover:opacity-100 transition-opacity">
                        <button onclick="editAccount({{ $account->toJson() }})" class="text-secondary hover:text-primary p-2 bg-surface hover:bg-primary/10 border border-outline-variant hover:border-primary/30 rounded-lg transition-colors tooltip" data-tip="Edit">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </button>
                        <form method="POST" action="{{ route('accountant.bank-accounts.destroy', $account->id) }}" onsubmit="return confirm('Are you sure you want to delete this bank account?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-secondary hover:text-error p-2 bg-surface hover:bg-red-50 border border-outline-variant hover:border-red-200 rounded-lg transition-colors tooltip" data-tip="Delete">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="bg-surface rounded-xl p-4 mb-6 border border-outline-variant z-10">
                    <p class="text-xs text-secondary font-semibold uppercase tracking-wider mb-1">Account Number</p>
                    <p class="text-body-lg font-mono text-on-surface tracking-widest font-medium">{{ $account->account_number }}</p>
                </div>
                
                <div class="mt-auto border-t border-outline-variant pt-5 flex justify-between items-end z-10">
                    <div>
                        <p class="text-label-md font-medium text-secondary mb-1">Current Balance</p>
                        <p class="text-headline-md font-bold text-emerald-600">{{ number_format($account->current_balance, 2) }}</p>
                    </div>
                    <div class="text-label-sm font-medium text-secondary flex items-center gap-1.5 bg-surface px-2.5 py-1 rounded-md border border-outline-variant">
                        <span class="material-symbols-outlined text-[14px]">location_on</span>
                        {{ $account->branch ?? 'Main Branch' }}
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center bg-surface-container-lowest border border-outline-variant rounded-2xl border-dashed">
                <div class="w-16 h-16 rounded-full bg-surface-container-low text-secondary flex items-center justify-center mx-auto mb-4 border border-outline-variant">
                    <span class="material-symbols-outlined text-4xl">account_balance</span>
                </div>
                <h3 class="text-title-lg font-bold text-on-surface">No Bank Accounts Found</h3>
                <p class="text-body-md text-secondary mt-1">Add your first bank account to start tracking transactions.</p>
                <button onclick="document.getElementById('addBankAccountModal').showModal()" class="btn-primary mt-6">
                    Add Bank Account
                </button>
            </div>
            @endforelse
        </div>
    </div>
</main>

<!-- Add Modal -->
<dialog id="addBankAccountModal" class="bg-surface-container-lowest rounded-xl shadow-2xl border border-outline-variant p-0 w-full max-w-lg backdrop:bg-black/50 backdrop:backdrop-blur-sm transition-all">
    <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-bright rounded-t-xl">
        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">account_balance</span>
            </div>
            Add Bank Account
        </h3>
        <form method="dialog"><button class="text-secondary hover:bg-surface-container p-1 rounded-full transition-colors"><span class="material-symbols-outlined">close</span></button></form>
    </div>
    <form method="POST" action="{{ route('accountant.bank-accounts.store') }}">
        @csrf
        <div class="p-6 space-y-5">
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Account Name (e.g. Operating Fund)</label>
                <input type="text" name="account_name" class="input-field bg-surface" required>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Bank Name</label>
                    <input type="text" name="bank_name" class="input-field bg-surface" required>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Account Number</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">numbers</span>
                        <input type="text" name="account_number" class="input-field pl-10 bg-surface" required>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Branch (Optional)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">location_on</span>
                    <input type="text" name="branch" class="input-field pl-10 bg-surface">
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Initial Balance</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary font-semibold"></span>
                    <input type="number" step="0.01" name="initial_balance" class="input-field pl-8 bg-surface text-lg font-medium" value="0.00" min="0" required>
                </div>
            </div>
        </div>
        <div class="p-6 border-t border-outline-variant flex justify-end gap-3 bg-surface-bright rounded-b-xl">
            <button type="button" onclick="document.getElementById('addBankAccountModal').close()" class="btn-outline px-6">Cancel</button>
            <button type="submit" class="btn-primary px-6">Save Account</button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editBankAccountModal" class="bg-surface-container-lowest rounded-xl shadow-2xl border border-outline-variant p-0 w-full max-w-lg backdrop:bg-black/50 backdrop:backdrop-blur-sm transition-all">
    <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-bright rounded-t-xl">
        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">edit_square</span>
            </div>
            Edit Bank Account
        </h3>
        <form method="dialog"><button class="text-secondary hover:bg-surface-container p-1 rounded-full transition-colors"><span class="material-symbols-outlined">close</span></button></form>
    </div>
    <form id="editForm" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-5">
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Account Name</label>
                <input type="text" name="account_name" id="edit_account_name" class="input-field bg-surface" required>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Bank Name</label>
                    <input type="text" name="bank_name" id="edit_bank_name" class="input-field bg-surface" required>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Account Number</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">numbers</span>
                        <input type="text" name="account_number" id="edit_account_number" class="input-field pl-10 bg-surface" required>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Branch (Optional)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">location_on</span>
                    <input type="text" name="branch" id="edit_branch" class="input-field pl-10 bg-surface">
                </div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex gap-3 text-amber-800">
                <span class="material-symbols-outlined text-amber-600">info</span>
                <span class="text-sm font-medium">Balances can only be modified via Ledger Entries or Fee Transactions.</span>
            </div>
        </div>
        <div class="p-6 border-t border-outline-variant flex justify-end gap-3 bg-surface-bright rounded-b-xl">
            <button type="button" onclick="document.getElementById('editBankAccountModal').close()" class="btn-outline px-6">Cancel</button>
            <button type="submit" class="btn-primary px-6">Update Account</button>
        </div>
    </form>
</dialog>

<script>
    function editAccount(account) {
        document.getElementById('editForm').action = `/accountant/bank-accounts/${account.id}`;
        document.getElementById('edit_account_name').value = account.account_name;
        document.getElementById('edit_bank_name').value = account.bank_name;
        document.getElementById('edit_account_number').value = account.account_number;
        document.getElementById('edit_branch').value = account.branch || '';
        document.getElementById('editBankAccountModal').showModal();
    }
</script>
@endsection
