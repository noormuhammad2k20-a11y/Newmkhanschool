@extends('layouts.app')

@section('title', 'Payroll Management')

@section('content')
<main class="flex-1 overflow-y-auto bg-surface-bright p-margin-desktop w-full">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
            <div>
                <h1 class="text-headline-lg font-headline-lg font-semibold text-on-surface">Payroll Management</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Manage salaries, allowances, and deductions</p>
            </div>
            <button onclick="openModal('generate-payroll-modal')" class="inline-flex items-center gap-sm px-lg py-sm bg-primary text-on-primary font-label-md text-label-md rounded hover:opacity-90 transition-opacity shadow-[0_4px_12px_rgba(26,35,126,0.08)]">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">add_card</span>
                Generate Payroll
            </button>
        </div>

        @if(session('success'))
        <div class="bg-primary-container text-on-primary-container p-md rounded font-body-md">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-error-container text-on-error-container p-md rounded font-body-md">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container border-b border-outline-variant">
                            <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Month/Year</th>
                            <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Employee</th>
                            <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Role</th>
                            <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Basic Pay</th>
                            <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Net Salary</th>
                            <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-md text-body-md text-on-surface">
                        @forelse($payrolls as $payroll)
                        <tr class="border-b border-surface-variant hover:bg-surface-bright transition-colors">
                            <td class="py-sm px-md">{{ $payroll->month_year }}</td>
                            <td class="py-sm px-md font-semibold">
                                {{ $payroll->name }}
                                <span class="block text-label-sm text-secondary">{{ $payroll->emp_id }}</span>
                            </td>
                            <td class="py-sm px-md">{{ $payroll->role }}</td>
                            <td class="py-sm px-md text-right">{{ number_format($payroll->basic_pay, 2) }}</td>
                            <td class="py-sm px-md text-right font-semibold text-primary">{{ number_format($payroll->net_salary, 2) }}</td>
                            <td class="py-sm px-md text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] uppercase font-label-md
                                    @if($payroll->status == 'Paid') bg-secondary-container text-secondary
                                    @elseif($payroll->status == 'Pending') bg-surface-variant text-on-surface-variant
                                    @else bg-error-container text-error @endif">
                                    {{ $payroll->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-xl px-md text-center text-on-surface-variant">No payroll records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-md border-t border-outline-variant bg-surface-container-low">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>
</main>

<!-- Generate Payroll Modal -->
<div id="generate-payroll-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-surface border border-outline-variant rounded-lg shadow-lg w-full max-w-lg flex flex-col">
        <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low rounded-t-lg">
            <h2 class="font-headline-md text-headline-md text-on-surface">Generate Payroll</h2>
            <button onclick="closeModal('generate-payroll-modal')" class="text-on-surface-variant hover:text-error transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.payroll.store') }}" method="POST">
            @csrf
            <div class="p-md space-y-md">
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Month & Year</label>
                    <input type="month" name="month_year" value="{{ date('Y-m') }}" required class="w-full border border-outline-variant rounded px-sm py-2 focus:ring-1 focus:ring-primary focus:border-primary bg-surface-bright text-on-surface">
                </div>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Select Teacher</label>
                    <select name="teacher_id" required class="w-full border border-outline-variant rounded px-sm py-2 focus:ring-1 focus:ring-primary focus:border-primary bg-surface-bright text-on-surface">
                        <option value="">-- Select Teacher --</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->full_name }} ({{ $teacher->employee_number }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-md">
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Basic Pay</label>
                        <input type="number" step="0.01" name="basic_pay" required class="w-full border border-outline-variant rounded px-sm py-2 focus:ring-1 focus:ring-primary focus:border-primary bg-surface-bright text-on-surface" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Allowances</label>
                        <input type="number" step="0.01" name="allowances" value="0" required class="w-full border border-outline-variant rounded px-sm py-2 focus:ring-1 focus:ring-primary focus:border-primary bg-surface-bright text-on-surface">
                    </div>
                </div>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Deductions</label>
                    <input type="number" step="0.01" name="deductions" value="0" required class="w-full border border-outline-variant rounded px-sm py-2 focus:ring-1 focus:ring-primary focus:border-primary bg-surface-bright text-on-surface">
                </div>
            </div>
            <div class="p-md border-t border-outline-variant flex justify-end gap-sm bg-surface-container-low rounded-b-lg">
                <button type="button" onclick="closeModal('generate-payroll-modal')" class="px-md py-sm border border-outline-variant text-on-surface rounded hover:bg-surface-container-highest transition-colors font-label-md">Cancel</button>
                <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded hover:opacity-90 transition-opacity font-label-md">Generate</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }
</script>
@endsection
