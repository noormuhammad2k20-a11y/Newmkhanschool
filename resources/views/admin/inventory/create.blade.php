@extends('layouts.app')

@section('content')
<div class="px-md py-lg max-w-4xl mx-auto">
    <div class="mb-lg">
        <h2 class="text-headline-lg font-headline-lg text-primary">Add New Inventory Item</h2>
        <p class="text-body-md text-secondary">Register a new asset or item in the school inventory.</p>
    </div>

    <form method="POST" action="{{ route('admin.inventory.store') }}" class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Asset Code *</label>
                <input type="text" name="asset_code" value="{{ old('asset_code') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                @error('asset_code') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Item Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Category *</label>
                <select name="category" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                    <option value="Stationery">Stationery</option>
                    <option value="Lab">Lab Equipment</option>
                    <option value="Books">Books</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Condition *</label>
                <select name="condition_status" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
                    <option value="Good">Good</option>
                    <option value="Fair">Fair</option>
                    <option value="Poor">Poor</option>
                    <option value="Broken">Broken</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-md">
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Initial Quantity *</label>
                <input type="number" name="quantity" value="{{ old('quantity', 0) }}" min="0" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Unit (e.g. pcs, boxes)</label>
                <input type="text" name="unit" value="{{ old('unit', 'pcs') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Min Stock Alert *</label>
                <input type="number" name="min_stock_alert" value="{{ old('min_stock_alert', 5) }}" min="0" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Purchase Price (Optional)</label>
                <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
            <div>
                <label class="block text-label-md text-on-surface-variant mb-xs">Supplier (Optional)</label>
                <input type="text" name="supplier" value="{{ old('supplier') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            </div>
        </div>

        <div class="mb-md">
            <label class="block text-label-md text-on-surface-variant mb-xs">Location/Room</label>
            <input type="text" name="location" value="{{ old('location') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary" placeholder="e.g. Science Lab A">
        </div>

        <div class="mb-lg">
            <label class="block text-label-md text-on-surface-variant mb-xs">Description</label>
            <textarea name="description" rows="3" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">{{ old('description') }}</textarea>
        </div>

        <div class="flex justify-end gap-sm">
            <a href="{{ route('admin.inventory') }}" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high">Cancel</a>
            <button type="submit" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant">Save Item</button>
        </div>
    </form>
</div>
@endsection
