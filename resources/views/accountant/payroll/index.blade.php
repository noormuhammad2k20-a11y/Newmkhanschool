@extends('layouts.app')

@section('title', 'Payroll Management')

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
                            <span class="text-on-surface">Payroll</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Payroll Management</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Manage staff salaries and payments</p>
                </div>
                <button class="btn-primary shadow-sm flex items-center gap-2" onclick="openModal('generate-payroll-modal')">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Generate Payroll
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 relative flex items-center gap-3" role="alert">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <div><span class="font-semibold">Success!</span> {{ session('success') }}</div>
        </div>
        @endif

        <!-- Table Section -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-xs font-semibold text-secondary uppercase tracking-wider border-b border-outline-variant">
                            <th class="py-4 px-6">Employee</th>
                            <th class="py-4 px-6">Month/Year</th>
                            <th class="py-4 px-6">Basic Pay</th>
                            <th class="py-4 px-6 text-error">Deductions</th>
                            <th class="py-4 px-6 text-emerald-600">Net Salary</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md divide-y divide-outline-variant">
                        @forelse($payrolls as $payroll)
                        <tr class="hover:bg-surface-container-lowest transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant text-on-surface flex items-center justify-center font-bold text-sm uppercase">{{ substr($payroll->name, 0, 2) }}</div>
                                    <div>
                                        <div class="font-semibold text-on-surface text-body-lg">{{ $payroll->name }}</div>
                                        <div class="text-xs text-secondary flex items-center gap-1 mt-0.5">
                                            <span class="material-symbols-outlined text-[14px]">badge</span>
                                            {{ $payroll->role ?? 'Staff' }} - {{ $payroll->emp_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-secondary font-medium">{{ $payroll->month_year }}</td>
                            <td class="py-4 px-6">{{ number_format($payroll->basic_pay, 2) }}</td>
                            <td class="py-4 px-6 text-error font-medium">
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">remove</span>
                                    {{ number_format($payroll->deductions, 2) }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-emerald-600 font-bold text-body-lg">{{ number_format($payroll->net_salary, 2) }}</td>
                            <td class="py-4 px-6">
                                @if($payroll->status == 'Paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    @if($payroll->status != 'Paid')
                                        <form method="POST" action="{{ route('accountant.payroll.mark-paid', $payroll->id) }}">
                                            @csrf
                                            <button class="text-emerald-600 bg-surface hover:text-emerald-700 hover:bg-emerald-50 border border-outline-variant hover:border-emerald-200 p-2 rounded-lg transition-colors tooltip" data-tip="Mark as Paid">
                                                <span class="material-symbols-outlined text-[20px]">payments</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-secondary">
                                <span class="material-symbols-outlined text-5xl mb-3 text-outline">account_balance_wallet</span>
                                <p class="text-body-lg font-medium text-on-surface">No payroll records found.</p>
                                <p class="text-body-md mt-1">Click "Generate Payroll" to create a new batch.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payrolls->hasPages())
            <div class="p-4 bg-surface-bright border-t border-outline-variant">
                {{ $payrolls->links() }}
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Generate Payroll Modal -->
<div id="generate-payroll-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface border border-outline-variant rounded-xl shadow-lg w-full max-w-md flex flex-col">
        <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-container-low rounded-t-xl">
            <h2 class="font-headline-md text-headline-md text-on-surface">Generate Payroll</h2>
            <button onclick="closeModal('generate-payroll-modal')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('accountant.payroll.generate') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <p class="text-body-md text-secondary mb-2">This will generate pending payroll records for all active teachers who haven't been processed yet for the selected month.</p>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-2">Month & Year</label>
                    <input type="month" name="month_year" value="{{ date('Y-m') }}" required class="w-full border border-outline-variant rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary bg-surface-bright text-on-surface transition-shadow">
                </div>
            </div>
            <div class="p-6 border-t border-outline-variant flex justify-end gap-3 bg-surface-container-low rounded-b-xl">
                <button type="button" onclick="closeModal('generate-payroll-modal')" class="px-4 py-2 border border-outline-variant text-on-surface rounded-lg hover:bg-surface-container-highest transition-colors font-label-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded-lg hover:opacity-90 transition-opacity font-label-md">Generate Payroll</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }
</script>
@endsection
