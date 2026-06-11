@extends('layouts.app')

@section('content')
<div class="px-md py-lg max-w-4xl mx-auto">
    <div class="mb-lg">
        <h2 class="text-headline-lg font-headline-lg text-primary">Edit Inventory Item</h2>
        <p class="text-body-md text-secondary">Update details for {{ $item->name }} ({{ $item->asset_code }})</p>
    </div>

    <form method="POST" action="{{ route('admin.inventory.update', $item->id) }}" class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Asset Code *</label>
                <input type="text" name="asset_code" value="{{ old('asset_code', $item->asset_code) }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                @error('asset_code') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Item Name *</label>
                <input type="text" name="name" value="{{ old('name', $item->name) }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Category *</label>
                <select name="category" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                    <option value="Stationery" {{ $item->category == 'Stationery' ? 'selected' : '' }}>Stationery</option>
                    <option value="Lab" {{ $item->category == 'Lab' ? 'selected' : '' }}>Lab Equipment</option>
                    <option value="Books" {{ $item->category == 'Books' ? 'selected' : '' }}>Books</option>
                    <option value="Electronics" {{ $item->category == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                    <option value="Furniture" {{ $item->category == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                    <option value="Other" {{ $item->category == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Condition *</label>
                <select name="condition_status" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                    <option value="Good" {{ $item->condition_status == 'Good' ? 'selected' : '' }}>Good</option>
                    <option value="Fair" {{ $item->condition_status == 'Fair' ? 'selected' : '' }}>Fair</option>
                    <option value="Poor" {{ $item->condition_status == 'Poor' ? 'selected' : '' }}>Poor</option>
                    <option value="Broken" {{ $item->condition_status == 'Broken' ? 'selected' : '' }}>Broken</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-md">
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Current Quantity</label>
                <!-- Quantity is read-only here. Must be changed via stock-in/out -->
                <input type="number" name="quantity" value="{{ $item->quantity }}" readonly class="w-full rounded-lg border-outline-variant bg-surface-container-high text-secondary cursor-not-allowed">
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Unit (e.g. pcs, boxes)</label>
                <input type="text" name="unit" value="{{ old('unit', $item->unit) }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Min Stock Alert *</label>
                <input type="number" name="min_stock_alert" value="{{ old('min_stock_alert', $item->min_stock_alert) }}" min="0" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Purchase Price</label>
                <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $item->purchase_price) }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Supplier</label>
                <input type="text" name="supplier" value="{{ old('supplier', $item->supplier) }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
        </div>

        <div class="mb-md">
            <label class="block text-label-md text-on-surface-variant mb-xs">Location/Room</label>
            <input type="text" name="location" value="{{ old('location', $item->location) }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
        </div>

        <div class="mb-lg">
            <label class="block text-label-md text-on-surface-variant mb-xs">Description</label>
            <textarea name="description" rows="3" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">{{ old('description', $item->description) }}</textarea>
        </div>

        <div class="flex justify-between items-center pt-md border-t border-outline-variant">
            <a href="{{ route('admin.inventory') }}" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high">Cancel</a>
            <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant">Update Item</button>
        </div>
    </form>
</div>
@endsection
