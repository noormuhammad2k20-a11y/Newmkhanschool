@extends('layouts.app')

@section('title', 'Expenses Management')

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
                            <span class="text-on-surface">Expenses</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Expenses</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Record and track school expenditures</p>
                </div>
                <button onclick="document.getElementById('addExpenseModal').showModal()" class="btn-primary shadow-sm flex items-center gap-2">
                    <span class="material-symbols-rounded text-[20px]">add</span>
                    Record Expense
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
                            <th class="py-4 px-6">Category</th>
                            <th class="py-4 px-6">Voucher No</th>
                            <th class="py-4 px-6">Description</th>
                            <th class="py-4 px-6">Amount</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md divide-y divide-outline-variant">
                        @forelse($expenses as $expense)
                        <tr class="hover:bg-surface-container-lowest transition-colors group">
                            <td class="py-4 px-6 font-medium text-on-surface">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-surface-variant text-on-surface-variant flex items-center justify-center border border-outline-variant">
                                        <span class="material-symbols-rounded text-[16px]">calendar_today</span>
                                    </div>
                                    {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-surface text-on-surface-variant border border-outline-variant">
                                    {{ $expense->category->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-secondary font-medium tracking-wide">{{ $expense->voucher_no ?? 'N/A' }}</td>
                            <td class="py-4 px-6 text-secondary truncate max-w-xs">{{ $expense->description }}</td>
                            <td class="py-4 px-6 font-bold text-error text-body-lg">
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-rounded text-[16px]">arrow_downward</span>
                                    {{ number_format($expense->amount, 2) }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <button onclick="editExpense({{ $expense->toJson() }})" class="text-secondary bg-surface hover:text-primary hover:bg-primary/10 border border-outline-variant hover:border-primary/30 p-2 rounded-lg transition-colors tooltip" data-tip="Edit">
                                        <span class="material-symbols-rounded text-[20px]">edit</span>
                                    </button>
                                    <form method="POST" action="{{ route('accountant.expenses.destroy', $expense->id) }}" onsubmit="return confirm('Delete this expense? This will also remove the ledger entry.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-secondary bg-surface hover:text-error hover:bg-red-50 border border-outline-variant hover:border-red-200 p-2 rounded-lg transition-colors tooltip" data-tip="Delete">
                                            <span class="material-symbols-rounded text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-secondary">
                                <span class="material-symbols-rounded text-5xl mb-3 text-outline">receipt_long</span>
                                <p class="text-body-lg font-medium text-on-surface">No expenses recorded yet.</p>
                                <p class="text-body-md mt-1">Click "Record Expense" to add a new expenditure.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($expenses->hasPages())
            <div class="p-4 bg-surface-bright border-t border-outline-variant">
                {{ $expenses->links() }}
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Add Modal -->
<dialog id="addExpenseModal" class="bg-surface-container-lowest rounded-xl shadow-2xl border border-outline-variant p-0 w-full max-w-lg backdrop:bg-black/50 backdrop:backdrop-blur-sm transition-all">
    <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-bright rounded-t-xl">
        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                <span class="material-symbols-rounded text-[20px]">receipt_long</span>
            </div>
            Record Expense
        </h3>
        <form method="dialog"><button class="text-secondary hover:bg-surface-container p-1 rounded-full transition-colors"><span class="material-symbols-rounded">close</span></button></form>
    </div>
    <form method="POST" action="{{ route('accountant.expenses.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Date</label>
                    <input type="date" name="expense_date" class="input-field bg-surface" value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Category</label>
                    <select name="expense_category_id" class="input-field bg-surface" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary font-semibold"></span>
                    <input type="number" step="0.01" name="amount" class="input-field pl-8 bg-surface text-lg font-medium" min="0" required>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Voucher / Receipt No (Optional)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-rounded text-[20px]">tag</span>
                    <input type="text" name="voucher_no" class="input-field pl-10 bg-surface">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Payment Mode</label>
                    <select name="payment_mode" class="input-field bg-surface" required>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Card">Card</option>
                        <option value="Online">Online</option>
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Paid To</label>
                    <input type="text" name="paid_to" class="input-field bg-surface" placeholder="Vendor/Person Name" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Status</label>
                    <select name="status" class="input-field bg-surface" required>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Attach Receipt (Optional)</label>
                    <input type="file" name="receipt" class="input-field bg-surface" accept=".pdf,.jpeg,.jpg,.png">
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Description</label>
                <textarea name="description" class="input-field bg-surface" rows="3" required></textarea>
            </div>
        </div>
        <div class="p-6 border-t border-outline-variant flex justify-end gap-3 bg-surface-bright rounded-b-xl">
            <button type="button" onclick="document.getElementById('addExpenseModal').close()" class="btn-outline px-6">Cancel</button>
            <button type="submit" class="btn-primary px-6">Save Expense</button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editExpenseModal" class="bg-surface-container-lowest rounded-xl shadow-2xl border border-outline-variant p-0 w-full max-w-lg backdrop:bg-black/50 backdrop:backdrop-blur-sm transition-all">
    <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-bright rounded-t-xl">
        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                <span class="material-symbols-rounded text-[20px]">edit_square</span>
            </div>
            Edit Expense
        </h3>
        <form method="dialog"><button class="text-secondary hover:bg-surface-container p-1 rounded-full transition-colors"><span class="material-symbols-rounded">close</span></button></form>
    </div>
    <form id="editForm" method="POST" action="" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Date</label>
                    <input type="date" name="expense_date" id="edit_date" class="input-field bg-surface" required>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Category</label>
                    <select name="expense_category_id" id="edit_category" class="input-field bg-surface" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary font-semibold"></span>
                    <input type="number" step="0.01" name="amount" id="edit_amount" class="input-field pl-8 bg-surface text-lg font-medium" min="0" required>
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Voucher / Receipt No</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-rounded text-[20px]">tag</span>
                    <input type="text" name="voucher_no" id="edit_voucher" class="input-field pl-10 bg-surface">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Payment Mode</label>
                    <select name="payment_mode" id="edit_payment_mode" class="input-field bg-surface" required>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Card">Card</option>
                        <option value="Online">Online</option>
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Paid To</label>
                    <input type="text" name="paid_to" id="edit_paid_to" class="input-field bg-surface" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Status</label>
                    <select name="status" id="edit_status" class="input-field bg-surface" required>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Attach Receipt (Optional)</label>
                    <input type="file" name="receipt" class="input-field bg-surface" accept=".pdf,.jpeg,.jpg,.png">
                </div>
            </div>
            <div>
                <label class="block text-label-md font-label-md text-on-surface mb-2">Description</label>
                <textarea name="description" id="edit_desc" class="input-field bg-surface" rows="3" required></textarea>
            </div>
        </div>
        <div class="p-6 border-t border-outline-variant flex justify-end gap-3 bg-surface-bright rounded-b-xl">
            <button type="button" onclick="document.getElementById('editExpenseModal').close()" class="btn-outline px-6">Cancel</button>
            <button type="submit" class="btn-primary px-6">Update Expense</button>
        </div>
    </form>
</dialog>

<script>
    function editExpense(expense) {
        document.getElementById('editForm').action = `/accountant/expenses/${expense.id}`;
        document.getElementById('edit_date').value = expense.expense_date;
        document.getElementById('edit_category').value = expense.expense_category_id;
        document.getElementById('edit_amount').value = expense.amount;
        document.getElementById('edit_voucher').value = expense.voucher_no || '';
        document.getElementById('edit_payment_mode').value = expense.payment_mode || 'Cash';
        document.getElementById('edit_paid_to').value = expense.paid_to || '';
        document.getElementById('edit_status').value = expense.status || 'Pending';
        document.getElementById('edit_desc').value = expense.description || '';
        document.getElementById('editExpenseModal').showModal();
    }
</script>
@endsection
