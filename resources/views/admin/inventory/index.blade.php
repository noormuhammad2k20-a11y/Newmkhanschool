@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="flex justify-between items-center mb-lg">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">Inventory Management</h2>
            <p class="text-body-md text-secondary">Manage school assets, stationery, and equipment.</p>
        </div>
        <div>
            <a href="{{ route('admin.inventory.create') }}" class="inline-flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors">
                <span class="material-symbols-outlined" data-icon="add">add</span>
                Add Item
            </a>
        </div>
    </div>

<div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm p-md mb-md">
        <form method="GET" action="{{ request()->url() }}" class="flex flex-wrap gap-md items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-label-md text-on-surface-variant mb-xs">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Asset Code..." class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
            <div class="w-48">
                <label class="block text-label-md text-on-surface-variant mb-xs">Category</label>
                <select name="category" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                    <option value="All">All Categories</option>
                    <option value="Stationery" {{ request('category') == 'Stationery' ? 'selected' : '' }}>Stationery</option>
                    <option value="Lab" {{ request('category') == 'Lab' ? 'selected' : '' }}>Lab</option>
                    <option value="Books" {{ request('category') == 'Books' ? 'selected' : '' }}>Books</option>
                    <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                    <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-md py-sm bg-surface-container-high text-on-surface rounded-lg font-label-md border border-outline-variant hover:bg-surface-container transition-colors">Filter</button>
            </div>
            @if($lowStockCount > 0 && !request()->routeIs('admin.inventory.low-stock'))
            <div class="ml-auto">
                <a href="{{ route('admin.inventory.low-stock') }}" class="px-md py-sm bg-error-container text-on-error-container rounded-lg font-label-md inline-flex items-center gap-xs hover:opacity-90">
                    <span class="material-symbols-outlined text-[18px]">warning</span>
                    Low Stock Alerts ({{ $lowStockCount }})
                </a>
            </div>
            @endif
        </form>
    </div>

    <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant font-label-md">
                        <th class="p-md border-b border-outline-variant">Asset Code</th>
                        <th class="p-md border-b border-outline-variant">Name</th>
                        <th class="p-md border-b border-outline-variant">Category</th>
                        <th class="p-md border-b border-outline-variant">Qty</th>
                        <th class="p-md border-b border-outline-variant">Condition</th>
                        <th class="p-md border-b border-outline-variant">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-body-md text-on-surface divide-y divide-outline-variant">
                    @forelse($items as $item)
                    <tr class="hover:bg-surface-container-lowest transition-colors">
                        <td class="p-md font-mono text-sm">{{ $item->asset_code }}</td>
                        <td class="p-md font-semibold">{{ $item->name }}</td>
                        <td class="p-md">{{ $item->category }}</td>
                        <td class="p-md">
                            @if($item->quantity <= $item->min_stock_alert)
                                <span class="text-error font-bold" title="Low Stock!">{{ $item->quantity }} {{ $item->unit }}</span>
                            @else
                                {{ $item->quantity }} {{ $item->unit }}
                            @endif
                        </td>
                        <td class="p-md">
                            @php
                                $statusColors = [
                                    'Good' => 'bg-green-100 text-green-800',
                                    'Fair' => 'bg-yellow-100 text-yellow-800',
                                    'Poor' => 'bg-orange-100 text-orange-800',
                                    'Broken' => 'bg-red-100 text-red-800',
                                ];
                                $color = $statusColors[$item->condition_status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $color }}">{{ $item->condition_status }}</span>
                        </td>
                        <td class="p-md flex items-center gap-sm">
                            <a href="{{ route('admin.inventory.show', $item->id) }}" class="text-primary hover:text-on-primary-fixed-variant" title="View"><span class="material-symbols-outlined text-[20px]">visibility</span></a>
                            <a href="{{ route('admin.inventory.edit', $item->id) }}" class="text-secondary hover:text-on-surface" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-md text-center text-secondary">No inventory items found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-md bg-surface-container-lowest border-t border-outline-variant">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
