@extends('layouts.app')

@section('title', 'Payment Receipt')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[800px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('parent.child.fees', $student->id) }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary hover:bg-surface-container-high transition-colors print:hidden">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Payment Receipt</h2>
            </div>
            <div class="flex items-center gap-3 print:hidden">
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">print</span>
                    Print Receipt
                </button>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm print:shadow-none print:border-none print:bg-white relative">
            <!-- Receipt Header -->
            <div class="p-8 border-b border-outline-variant flex flex-col md:flex-row justify-between items-start gap-6 print:border-b-2 print:border-gray-300">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 print:bg-white print:border print:border-emerald-600">
                            <span class="material-symbols-outlined text-[28px]">check_circle</span>
                        </div>
                        <h3 class="text-headline-md font-headline-md text-emerald-700">Payment Successful</h3>
                    </div>
                    <p class="text-body-lg font-body-lg text-secondary">Thank you for your payment. The fee has been successfully processed.</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-1">Receipt Number</p>
                    <p class="text-title-lg font-title-lg text-on-surface font-mono">{{ $fee->transaction_id ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Receipt Details -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4 class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-3">Student Information</h4>
                        <div class="space-y-2">
                            <p class="text-body-lg font-body-lg text-on-surface"><span class="text-secondary inline-block w-24">Name:</span> <strong>{{ $student->first_name }} {{ $student->last_name }}</strong></p>
                            <p class="text-body-lg font-body-lg text-on-surface"><span class="text-secondary inline-block w-24">Class:</span> {{ $student->currentClass->name ?? '' }} {{ $student->currentSection->name ?? '' }}</p>
                            <p class="text-body-lg font-body-lg text-on-surface"><span class="text-secondary inline-block w-24">Reg No:</span> {{ $student->admission_no }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-3">Payment Information</h4>
                        <div class="space-y-2">
                            <p class="text-body-lg font-body-lg text-on-surface"><span class="text-secondary inline-block w-32">Date Paid:</span> {{ \Carbon\Carbon::parse($fee->payment_date)->format('M d, Y h:i A') }}</p>
                            <p class="text-body-lg font-body-lg text-on-surface"><span class="text-secondary inline-block w-32">Payment Method:</span> {{ $fee->payment_method ?? 'Online' }}</p>
                            <p class="text-body-lg font-body-lg text-on-surface"><span class="text-secondary inline-block w-32">Challan No:</span> #{{ $fee->challan_no }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-outline-variant pt-6 mb-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-label-md font-label-md text-secondary uppercase tracking-wider border-b border-outline-variant">
                                <th class="pb-3 font-medium">Description</th>
                                <th class="pb-3 font-medium text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            <tr>
                                <td class="py-4 text-title-md font-title-md text-on-surface">{{ $fee->fee_category }}</td>
                                <td class="py-4 text-right text-title-md font-title-md text-on-surface">Rs {{ number_format($fee->amount, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-outline-variant">
                                <td class="py-4 text-headline-sm font-headline-sm font-bold text-on-surface text-right pr-6">Total Paid:</td>
                                <td class="py-4 text-headline-sm font-headline-sm font-bold text-primary text-right">Rs {{ number_format($fee->amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Watermark -->
            <div class="absolute inset-0 pointer-events-none flex items-center justify-center opacity-5 overflow-hidden print:opacity-10">
                <span class="material-symbols-outlined text-[300px] -rotate-12">verified</span>
            </div>
        </div>
    </div>
</main>

<style>
    @media print {
        body { background: white !important; }
        nav, header { display: none !important; }
        .p-margin-desktop { padding: 0 !important; margin: 0 !important; }
    }
</style>
@endsection
