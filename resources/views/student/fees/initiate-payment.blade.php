@extends('layouts.app')

@section('title', 'Make Fee Payment')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background py-8">
    <div class="max-w-[1000px] mx-auto">

<!-- Main Checkout Container -->
        <div class="bg-surface border border-outline-variant rounded-3xl shadow-md overflow-hidden">
            
            <!-- Header -->
            <div class="px-8 py-6 md:px-10 border-b border-outline-variant bg-surface-bright flex items-center gap-5">
                <a href="{{ route('student.fees') }}" class="w-11 h-11 shrink-0 rounded-full bg-surface border border-outline-variant flex items-center justify-center text-on-surface hover:bg-surface-container hover:shadow-sm transition-all group">
                    <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-headline-sm font-headline-sm text-on-surface tracking-tight">Pay Fee Challan <span class="text-primary font-bold">#{{ $fee->challan_no }}</span></h2>
                    <p class="text-body-md text-secondary mt-0.5">Complete your transaction securely via mobile account</p>
                </div>
            </div>

            <!-- Content Area: Split Layout -->
            <div class="flex flex-col md:flex-row">
                
                <!-- Left Column: Payment Process -->
                <div class="w-full md:w-[60%] p-8 md:p-10">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">1</div>
                        <h3 class="text-title-lg font-bold text-on-surface">Choose Method</h3>
                    </div>

                    <!-- Payment Method Selection Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <label class="cursor-pointer group">
                            <input type="radio" name="payment_method" value="jazzcash" class="peer sr-only" onchange="showForm('jazzcash')">
                            <div class="p-5 rounded-2xl border-2 border-outline-variant peer-checked:border-[#dc2626] peer-checked:bg-[#dc2626]/5 group-hover:border-outline peer-checked:group-hover:border-[#dc2626] transition-all flex flex-col items-center gap-3 text-center h-full relative">
                                <!-- Checkmark indicator for selected state -->
                                <div class="absolute top-3 right-3 w-5 h-5 rounded-full bg-[#dc2626] text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity scale-50 peer-checked:scale-100">
                                    <span class="material-symbols-outlined text-[14px] font-bold">check</span>
                                </div>
                                <div class="w-14 h-14 rounded-full bg-surface border border-outline-variant flex items-center justify-center text-[#dc2626] shadow-sm">
                                    <span class="material-symbols-outlined text-[28px]">account_balance_wallet</span>
                                </div>
                                <div>
                                    <h4 class="text-title-md font-bold text-on-surface">JazzCash</h4>
                                    <p class="text-[11px] uppercase tracking-wider font-semibold text-secondary mt-0.5">Mobile Account</p>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer group">
                            <input type="radio" name="payment_method" value="easypaisa" class="peer sr-only" onchange="showForm('easypaisa')">
                            <div class="p-5 rounded-2xl border-2 border-outline-variant peer-checked:border-[#10b981] peer-checked:bg-[#10b981]/5 group-hover:border-outline peer-checked:group-hover:border-[#10b981] transition-all flex flex-col items-center gap-3 text-center h-full relative">
                                <div class="absolute top-3 right-3 w-5 h-5 rounded-full bg-[#10b981] text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity scale-50 peer-checked:scale-100">
                                    <span class="material-symbols-outlined text-[14px] font-bold">check</span>
                                </div>
                                <div class="w-14 h-14 rounded-full bg-surface border border-outline-variant flex items-center justify-center text-[#10b981] shadow-sm">
                                    <span class="material-symbols-outlined text-[28px]">payments</span>
                                </div>
                                <div>
                                    <h4 class="text-title-md font-bold text-on-surface">EasyPaisa</h4>
                                    <p class="text-[11px] uppercase tracking-wider font-semibold text-secondary mt-0.5">Mobile Account</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">2</div>
                        <h3 class="text-title-lg font-bold text-on-surface">Payment Details</h3>
                    </div>

                    <!-- JazzCash Form -->
                    <div id="form-jazzcash" class="hidden">
                        <div class="p-4 mb-6 rounded-xl bg-[#dc2626]/5 border border-[#dc2626]/20 flex items-start gap-3">
                            <span class="material-symbols-outlined text-[#dc2626] shrink-0 mt-0.5">info</span>
                            <div class="text-sm text-on-surface">
                                <p class="font-bold text-[#dc2626] mb-1">Important Step!</p>
                                <p class="text-secondary leading-relaxed">After clicking 'Proceed', you will receive a prompt on your mobile screen. Enter your MPIN to authorize the transaction.</p>
                            </div>
                        </div>
                        <form action="{{ route('student.fees.jazzcash', $fee->id) }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-label-sm uppercase tracking-wider font-bold text-on-surface mb-2">JazzCash Mobile Number</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined">call</span>
                                    <input type="text" name="mobile_number" placeholder="03XXXXXXXXX" required pattern="^03[0-9]{9}$" class="w-full h-12 pl-12 pr-4 border-2 border-outline-variant rounded-xl bg-surface text-on-surface focus:outline-none focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all text-body-lg font-medium tracking-wide">
                                </div>
                            </div>
                            <div>
                                <label class="block text-label-sm uppercase tracking-wider font-bold text-on-surface mb-2">Last 6 digits of CNIC</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined">badge</span>
                                    <input type="text" name="cnic_last6" placeholder="XXXXXX" required pattern="[0-9]{6}" maxlength="6" class="w-full h-12 pl-12 pr-4 border-2 border-outline-variant rounded-xl bg-surface text-on-surface focus:outline-none focus:border-[#dc2626] focus:ring-4 focus:ring-[#dc2626]/10 transition-all text-body-lg font-medium tracking-widest">
                                </div>
                            </div>
                            <button type="submit" class="w-full h-14 bg-[#dc2626] hover:bg-[#b91c1c] active:scale-[0.99] text-white font-bold text-lg rounded-xl transition-all flex items-center justify-center gap-2 mt-4 shadow-lg shadow-[#dc2626]/30 hover:shadow-[#dc2626]/40">
                                <span class="material-symbols-outlined text-[20px]">lock</span> Pay ₨ {{ number_format(max(0, $fee->amount + $fee->fine - $fee->discount - $fee->paid_amount), 2) }} securely
                            </button>
                        </form>
                    </div>

                    <!-- EasyPaisa Form -->
                    <div id="form-easypaisa" class="hidden">
                        <div class="p-4 mb-6 rounded-xl bg-[#10b981]/5 border border-[#10b981]/20 flex items-start gap-3">
                            <span class="material-symbols-outlined text-[#10b981] shrink-0 mt-0.5">info</span>
                            <div class="text-sm text-on-surface">
                                <p class="font-bold text-[#10b981] mb-1">EasyPaisa Verification</p>
                                <p class="text-secondary leading-relaxed">Ensure your phone is unlocked. You will receive an in-app notification or USSD prompt to approve this payment.</p>
                            </div>
                        </div>
                        <form action="{{ route('student.fees.easypaisa', $fee->id) }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-label-sm uppercase tracking-wider font-bold text-on-surface mb-2">EasyPaisa Mobile Number</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined">call</span>
                                    <input type="text" name="mobile_number" placeholder="03XXXXXXXXX" required pattern="^03[0-9]{9}$" class="w-full h-12 pl-12 pr-4 border-2 border-outline-variant rounded-xl bg-surface text-on-surface focus:outline-none focus:border-[#10b981] focus:ring-4 focus:ring-[#10b981]/10 transition-all text-body-lg font-medium tracking-wide">
                                </div>
                            </div>
                            <button type="submit" class="w-full h-14 bg-[#10b981] hover:bg-[#059669] active:scale-[0.99] text-white font-bold text-lg rounded-xl transition-all flex items-center justify-center gap-2 mt-4 shadow-lg shadow-[#10b981]/30 hover:shadow-[#10b981]/40">
                                <span class="material-symbols-outlined text-[20px]">lock</span> Pay ₨ {{ number_format(max(0, $fee->amount + $fee->fine - $fee->discount - $fee->paid_amount), 2) }} securely
                            </button>
                        </form>
                    </div>
                    
                    <!-- Initial Placeholder for Forms -->
                    <div id="form-placeholder" class="bg-surface-container-lowest border-2 border-outline-variant border-dashed rounded-2xl p-8 text-center flex flex-col items-center justify-center text-secondary h-[220px]">
                        <span class="material-symbols-outlined text-[40px] opacity-30 mb-2">mouse</span>
                        <p class="text-body-md font-medium text-on-surface-variant">Click a payment provider above to<br>enter your account details.</p>
                    </div>

                </div>

                <!-- Right Column: Summary Sidebar -->
                <div class="w-full md:w-[40%] bg-surface-container-lowest border-t md:border-t-0 md:border-l border-outline-variant p-8 md:p-10 relative">
                    
                    <!-- Top Fade out overlay for aesthetics -->
                    <div class="absolute top-0 inset-x-0 h-4 bg-gradient-to-b from-black/5 to-transparent opacity-0 md:opacity-50"></div>

                    <h3 class="text-title-lg font-bold text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">receipt_long</span> Summary
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex flex-col gap-1 pb-4 border-b border-outline-variant">
                            <span class="text-[11px] uppercase tracking-wider font-bold text-secondary">Student Name</span>
                            <span class="text-title-md font-bold text-on-surface">{{ $fee->student->user->name }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center pb-4 border-b border-outline-variant">
                            <span class="text-body-md text-secondary">Fee Category</span>
                            <span class="text-body-md font-medium text-on-surface">{{ $fee->fee_category }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center pb-4 border-b border-outline-variant">
                            <span class="text-body-md text-secondary">Due Date</span>
                            <span class="text-body-md font-medium text-on-surface">{{ \Carbon\Carbon::parse($fee->due_date)->format('d M, Y') }}</span>
                        </div>
                        
                        <div class="pt-2 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-body-md text-secondary">Base Amount</span>
                                <span class="text-body-md font-medium text-on-surface">₨ {{ number_format($fee->amount, 2) }}</span>
                            </div>
                            
                            @if($fee->fine > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-body-md text-error">Fine</span>
                                <span class="text-body-md font-bold text-error">+ ₨ {{ number_format($fee->fine, 2) }}</span>
                            </div>
                            @endif
                            
                            @if($fee->discount > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-body-md text-[#10b981]">Discount</span>
                                <span class="text-body-md font-bold text-[#10b981]">- ₨ {{ number_format($fee->discount, 2) }}</span>
                            </div>
                            @endif
                            
                            @if($fee->paid_amount > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-body-md text-primary">Already Paid</span>
                                <span class="text-body-md font-bold text-primary">- ₨ {{ number_format($fee->paid_amount, 2) }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Total -->
                        <div class="mt-6 pt-6 border-t-2 border-outline-variant border-dashed">
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="block text-label-md font-bold text-secondary uppercase tracking-wider mb-1">Total to Pay</span>
                                    <span class="text-xs text-secondary">Includes all taxes and fees</span>
                                </div>
                                <span class="text-headline-sm font-black text-primary">₨ {{ number_format(max(0, $fee->amount + $fee->fine - $fee->discount - $fee->paid_amount), 2) }}</span>
                            </div>
                        </div>

                        <!-- Security badges -->
                        <div class="mt-8 pt-6 flex items-center justify-center gap-6 opacity-60">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-secondary uppercase tracking-wider">
                                <span class="material-symbols-outlined text-[16px]">lock</span> SSL Secured
                            </div>
                            <div class="flex items-center gap-1.5 text-xs font-bold text-secondary uppercase tracking-wider">
                                <span class="material-symbols-outlined text-[16px]">verified_user</span> Safe Pay
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<script>
    function showForm(method) {
        document.getElementById('form-placeholder').classList.add('hidden');
        if(method === 'jazzcash') {
            document.getElementById('form-jazzcash').classList.remove('hidden');
            document.getElementById('form-easypaisa').classList.add('hidden');
        } else if(method === 'easypaisa') {
            document.getElementById('form-easypaisa').classList.remove('hidden');
            document.getElementById('form-jazzcash').classList.add('hidden');
        }
    }
</script>
@endsection
