@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="mb-lg">
        <h2 class="text-headline-lg font-headline-lg text-primary">Digital Signatures</h2>
        <p class="text-body-md text-secondary">Manage official digital signatures for your documents.</p>
    </div>

    @if(session('success'))
        <div class="mb-md p-md bg-emerald-100 text-emerald-800 rounded-lg border border-emerald-200 flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
        <!-- Principal's Signature Card -->
        <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="bg-surface-container-low px-md py-sm border-b border-outline-variant flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">draw</span>
                <h3 class="font-headline-md text-on-surface">Principal's Signature</h3>
            </div>
            
            <div class="p-lg flex-1 flex flex-col">
                <div class="mb-lg flex-1 flex flex-col items-center justify-center border-2 border-dashed border-outline-variant rounded-lg bg-surface-container-lowest p-xl">
                    @if($school && $school->principal_signature_path)
                        <img src="{{ Storage::url($school->principal_signature_path) }}" alt="Signature" class="max-h-[150px] object-contain">
                        <span class="mt-md text-label-sm text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">verified</span> Active Signature
                        </span>
                    @else
                        <div class="text-center text-secondary">
                            <span class="material-symbols-outlined text-[48px] opacity-50 mb-2">signature</span>
                            <p class="font-body-md">No digital signature uploaded yet.</p>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.documents.signatures.update') }}" enctype="multipart/form-data" class="mt-auto">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-label-md font-label-md text-secondary">Upload New Signature</label>
                        <p class="text-label-sm text-secondary mb-3">Please upload a clear PNG/JPG with a transparent or white background.</p>
                        
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <input type="file" name="signature" required accept="image/png, image/jpeg, image/jpg"
                                       class="block w-full text-sm text-secondary
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-lg file:border-0
                                              file:text-label-md file:font-semibold
                                              file:bg-primary-container file:text-on-primary-container
                                              hover:file:bg-primary hover:file:text-on-primary file:transition-colors cursor-pointer">
                            </div>
                            <button type="submit" class="px-md py-2 bg-primary text-on-primary hover:bg-primary-hover rounded-lg font-label-md transition-colors shadow-sm flex items-center gap-1 shrink-0">
                                <span class="material-symbols-outlined text-[18px]">upload</span> Upload
                            </button>
                        </div>
                        @error('signature') 
                            <p class="text-sm text-error mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">error</span> {{ $message }}
                            </p> 
                        @enderror
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col justify-center text-center">
             <div class="w-16 h-16 bg-primary-fixed rounded-full flex items-center justify-center mx-auto mb-md text-primary">
                 <span class="material-symbols-outlined text-[32px]">verified_user</span>
             </div>
             <h3 class="font-headline-md text-on-surface mb-sm">Secure Authentication</h3>
             <p class="text-body-md text-secondary mb-md">
                 Digital signatures uploaded here are securely attached to certificates and documents generated by your branch. Combined with QR verification, this prevents tampering and guarantees authenticity.
             </p>
             <ul class="text-sm text-secondary text-left inline-block mx-auto space-y-2 bg-surface-container-low p-md rounded-lg">
                 <li class="flex items-start gap-2"><span class="material-symbols-outlined text-emerald-600 text-[18px]">check</span> Use transparent PNGs for the best look on any template.</li>
                 <li class="flex items-start gap-2"><span class="material-symbols-outlined text-emerald-600 text-[18px]">check</span> Ensure the signature is well-lit and cropped tightly.</li>
                 <li class="flex items-start gap-2"><span class="material-symbols-outlined text-emerald-600 text-[18px]">check</span> Maximum allowed file size is 2MB.</li>
             </ul>
        </div>
    </div>
</div>
@endsection
