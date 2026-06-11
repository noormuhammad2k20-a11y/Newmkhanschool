@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="flex justify-between items-center mb-lg">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">{{ $item->name }}</h2>
            <p class="text-body-md text-secondary">Asset Code: {{ $item->asset_code }}</p>
        </div>
        <div class="flex gap-sm">
            <a href="{{ route('admin.inventory.stock-in.form', $item->id) }}" class="px-md py-sm bg-primary-container text-on-primary-container rounded-lg font-label-md hover:opacity-90">Stock In</a>
            <a href="{{ route('admin.inventory.stock-out.form', $item->id) }}" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high">Stock Out</a>
            <a href="{{ route('admin.inventory') }}" class="px-md py-sm text-secondary hover:underline self-center">Back</a>
        </div>
    </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-lg mb-lg">
        <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm">
            <h3 class="font-headline-md border-b border-outline-variant pb-sm mb-md">Item Details</h3>
            <ul class="space-y-sm text-body-md">
                <li><span class="text-secondary">Category:</span> {{ $item->category }}</li>
                <li><span class="text-secondary">Current Stock:</span> <strong class="{{ $item->quantity <= $item->min_stock_alert ? 'text-error' : '' }}">{{ $item->quantity }} {{ $item->unit }}</strong></li>
                <li><span class="text-secondary">Condition:</span> {{ $item->condition_status }}</li>
                <li><span class="text-secondary">Min Alert:</span> {{ $item->min_stock_alert }}</li>
                <li><span class="text-secondary">Location:</span> {{ $item->location ?? 'N/A' }}</li>
                <li><span class="text-secondary">Supplier:</span> {{ $item->supplier ?? 'N/A' }}</li>
            </ul>
        </div>
        
        <div class="lg:col-span-2 bg-surface border border-outline-variant rounded-xl p-md shadow-sm">
            <h3 class="font-headline-md border-b border-outline-variant pb-sm mb-md">Transaction History</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="text-secondary border-b border-outline-variant">
                            <th class="py-sm">Date</th>
                            <th class="py-sm">Type</th>
                            <th class="py-sm">Qty</th>
                            <th class="py-sm">Reason / Ref</th>
                            <th class="py-sm">By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                        <tr class="border-b border-outline-variant last:border-0 hover:bg-surface-container-lowest">
                            <td class="py-sm">{{ $tx->created_at->format('d M Y H:i') }}</td>
                            <td class="py-sm">
                                @if($tx->type == 'in')
                                    <span class="text-green-600 font-bold">IN +</span>
                                @elseif($tx->type == 'out')
                                    <span class="text-red-600 font-bold">OUT -</span>
                                @else
                                    <span class="text-orange-600 font-bold">ADJ</span>
                                @endif
                            </td>
                            <td class="py-sm font-semibold">{{ $tx->quantity }}</td>
                            <td class="py-sm">{{ $tx->reason }} <div class="text-xs text-secondary">{{ $tx->reference_no }}</div></td>
                            <td class="py-sm">{{ $tx->performer->first_name ?? 'System' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-sm text-secondary text-center">No transactions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-md">{{ $transactions->links() }}</div>
        </div>
    </div>
</div>
@endsection
