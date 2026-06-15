@extends('layouts.app')

@section('title', 'Digital Signatures')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex items-center gap-4 mb-lg">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary hover:bg-surface-container-high transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Digital Signatures</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage official digital signatures for your documents.</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-xl py-md border-b border-outline-variant bg-surface-bright flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center shrink-0 shadow-inner">
                        <span class="material-symbols-outlined text-[20px]">draw</span>
                    </div>
                    <div>
                        <h3 class="text-headline-sm font-bold text-on-surface">Principal's Signature</h3>
                        <p class="text-body-md text-secondary mt-0.5">Authorized signature for certificates and reports.</p>
                    </div>
                </div>
                @if($school && $school->principal_signature_path)
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">verified</span> Active
                </span>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 divide-y lg:divide-y-0 lg:divide-x divide-outline-variant flex-1">
                <!-- Left: Current Signature Display -->
                <div class="lg:col-span-5 p-xl bg-surface flex flex-col justify-center border-r border-outline-variant">
                    <h4 class="text-headline-sm font-semibold text-on-surface mb-4">Current Signature</h4>
                    <div class="w-full aspect-[16/9] lg:aspect-[4/3] bg-surface-container-lowest border-2 border-dashed border-outline-variant rounded-xl flex flex-col items-center justify-center p-lg relative group hover:border-primary/50 transition-colors shadow-sm">
                        @if($school && $school->principal_signature_path)
                            <img src="{{ Storage::url($school->principal_signature_path) }}" alt="Signature" class="max-w-full max-h-full object-contain p-4 drop-shadow-sm">
                        @else
                            <div class="text-center">
                                <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mx-auto mb-4 text-secondary">
                                    <span class="material-symbols-outlined text-[32px]">signature</span>
                                </div>
                                <p class="text-label-lg font-semibold text-secondary">No Signature Found</p>
                                <p class="text-body-md text-outline mt-1.5">Upload a signature to activate.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Upload Form & Guidelines -->
                <div class="lg:col-span-7 p-xl flex flex-col justify-center bg-surface-container-lowest">
                    <form method="POST" action="{{ route('admin.documents.signatures.update') }}" enctype="multipart/form-data" class="max-w-3xl">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-headline-sm font-semibold text-on-surface mb-2">Upload New Signature</label>
                            <p class="text-body-md text-secondary">Ensure the file is clear and meets the requirements below.</p>
                        </div>

                        <div class="relative border-2 border-outline-variant bg-surface rounded-xl p-2 flex items-center focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/10 transition-all shadow-sm mb-8">
                            <input type="file" name="signature" required accept="image/png, image/jpeg, image/jpg"
                                   class="block w-full text-base text-secondary
                                          file:mr-4 file:py-2.5 file:px-5
                                          file:rounded-lg file:border-0
                                          file:text-label-md file:font-semibold
                                          file:bg-primary-container file:text-on-primary-container
                                          hover:file:bg-primary hover:file:text-on-primary file:transition-colors cursor-pointer outline-none">
                            <button type="submit" class="btn-primary py-2.5 px-6 text-label-md shrink-0 whitespace-nowrap absolute right-2 shadow-sm">
                                <span class="material-symbols-outlined text-[18px] mr-1.5">cloud_upload</span> Upload
                            </button>
                        </div>
                        @error('signature') 
                            <p class="text-sm font-medium text-error mt-2 mb-6 flex items-center gap-1.5 bg-error/10 p-3 rounded-lg">
                                <span class="material-symbols-outlined text-[18px]">error</span> {{ $message }}
                            </p> 
                        @enderror

                        <div class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm">
                            <h4 class="text-label-lg font-semibold text-on-surface flex items-center gap-2.5 mb-4 border-b border-outline-variant pb-3">
                                <span class="material-symbols-outlined text-[22px] text-primary">info</span> Upload Guidelines
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[20px] text-emerald-600 shrink-0 mt-0.5">task_alt</span> 
                                    <span class="text-body-md text-secondary">Use a <strong>PNG image</strong> with a transparent background for perfect overlays.</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[20px] text-emerald-600 shrink-0 mt-0.5">task_alt</span> 
                                    <span class="text-body-md text-secondary"><strong>Crop tightly</strong> around the signature to remove whitespace margins.</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[20px] text-emerald-600 shrink-0 mt-0.5">task_alt</span> 
                                    <span class="text-body-md text-secondary">Use <strong>dark ink</strong> (black or navy blue) for sharp visibility on prints.</span>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[20px] text-emerald-600 shrink-0 mt-0.5">task_alt</span> 
                                    <span class="text-body-md text-secondary">File size must not exceed <strong>2MB</strong>.</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
