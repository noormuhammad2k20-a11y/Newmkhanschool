@extends('layouts.app')

@section('content')
<div class="px-md py-lg max-w-2xl mx-auto">
    <div class="mb-lg">
        <h2 class="text-headline-lg font-headline-lg text-primary">Issue Stock (Stock Out)</h2>
        <p class="text-body-md text-secondary">Issue stock for <strong>{{ $item->name }}</strong> (Available: {{ $item->quantity }} {{ $item->unit }})</p>
    </div>

<form method="POST" action="{{ route('admin.inventory.stock-out', $item->id) }}" class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm">
        @csrf
        
        <div class="mb-md">
            <label class="block text-label-md text-on-surface-variant mb-xs">Quantity to Issue *</label>
            <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" max="{{ $item->quantity }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
            @error('quantity') <span class="text-error text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-md">
            <label class="block text-label-md text-on-surface-variant mb-xs">Reason/Issued To *</label>
            <input type="text" name="reason" value="{{ old('reason', 'Issued to Staff/Class') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary" placeholder="e.g. Issued to Science Lab">
            @error('reason') <span class="text-error text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mb-lg">
            <label class="block text-label-md text-on-surface-variant mb-xs">Reference No. (Req No. / Receipt)</label>
            <input type="text" name="reference_no" value="{{ old('reference_no') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring-primary">
        </div>

        <div class="flex justify-end gap-sm">
            <a href="{{ route('admin.inventory.show', $item->id) }}" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high">Cancel</a>
            <button type="submit" class="px-md py-sm bg-red-600 text-white rounded-lg font-label-md hover:bg-red-700">Issue Stock</button>
        </div>
    </form>
</div>
@endsection
