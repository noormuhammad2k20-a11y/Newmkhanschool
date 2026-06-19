@extends('layouts.app')

@section('title', 'Pay Fee')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[800px] mx-auto space-y-xl">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('parent.child.fees', $student->id) }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-rounded">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Complete Payment</h2>
                <p class="text-body-md font-body-md text-secondary mt-1">Paying fee for {{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="p-6 border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <div>
                    <h3 class="text-title-lg font-title-lg text-on-surface">Invoice #{{ $fee->challan_no }}</h3>
                    <p class="text-body-md font-body-md text-secondary">{{ $fee->fee_category }}</p>
                </div>
                <div class="text-right">
                    <p class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-1">Total Amount</p>
                    <p class="text-headline-md font-headline-md font-bold text-primary">Rs {{ number_format($fee->amount, 2) }}</p>
                </div>
            </div>

            <div class="p-6">
                <form action="{{ route('parent.child.fees.process', [$student->id, $fee->id]) }}" method="POST">
                    @csrf
                    
                    <h4 class="text-title-md font-title-md text-on-surface mb-4">Select Payment Method</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <label class="relative flex items-start p-4 cursor-pointer rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary-fixed/20 has-[:checked]:ring-1 has-[:checked]:ring-primary">
                            <input type="radio" name="payment_method" value="JazzCash" class="mt-1" checked>
                            <div class="ml-4 flex-1">
                                <p class="text-title-md font-title-md text-on-surface mb-1">JazzCash</p>
                                <p class="text-body-sm font-body-sm text-secondary">Pay instantly using your JazzCash Mobile Account</p>
                            </div>
                        </label>

                        <label class="relative flex items-start p-4 cursor-pointer rounded-xl border border-outline-variant bg-surface-container-lowest hover:border-primary transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary-fixed/20 has-[:checked]:ring-1 has-[:checked]:ring-primary">
                            <input type="radio" name="payment_method" value="EasyPaisa" class="mt-1">
                            <div class="ml-4 flex-1">
                                <p class="text-title-md font-title-md text-on-surface mb-1">EasyPaisa</p>
                                <p class="text-body-sm font-body-sm text-secondary">Pay securely with your EasyPaisa Account</p>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-start gap-3 p-4 bg-surface-container-low rounded-lg mb-8">
                        <span class="material-symbols-rounded text-secondary">info</span>
                        <p class="text-body-md font-body-md text-secondary">By clicking "Pay Now", you will be redirected to the secure payment gateway to complete your transaction. Your payment details are encrypted and never stored on our servers.</p>
                    </div>

                    <button type="submit" class="w-full py-4 bg-primary text-on-primary rounded-xl font-label-lg hover:bg-primary/90 transition-colors shadow-sm flex justify-center items-center gap-2">
                        <span class="material-symbols-rounded">lock</span>
                        Pay Rs {{ number_format($fee->amount, 2) }} Securely
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
