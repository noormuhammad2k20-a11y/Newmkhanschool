@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-xl gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined">add_business</span>
                </div>
                <h2 class="text-headline-lg font-headline-lg text-primary">Add New Branch</h2>
            </div>
            <p class="text-body-md text-secondary ml-[52px]">Register a new branch school under the main administration.</p>
        </div>
        <div>
            <a href="{{ route('admin.branches.index') }}" class="px-4 py-2 text-secondary hover:text-on-surface hover:bg-surface-container-low rounded-lg transition-colors flex items-center gap-2 font-label-md">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to List
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.branches.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden shadow-sm">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary">domain_add</span>
                <h3 class="text-headline-md font-headline-md text-on-surface">Branch Information</h3>
            </div>
            
            <div class="p-lg grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                <div>
                    <label class="block text-label-md font-medium text-on-surface mb-2">Branch Name <span class="text-error">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 bg-surface text-on-surface py-2 px-3 transition-shadow">
                    @error('name') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-label-md font-medium text-on-surface mb-2">Branch Code <span class="text-error">*</span></label>
                    <input type="text" name="branch_code" value="{{ old('branch_code') }}" required class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 bg-surface text-on-surface py-2 px-3 font-mono transition-shadow">
                    @error('branch_code') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-label-md font-medium text-on-surface mb-2">Principal/Head Name</label>
                    <input type="text" name="principal_name" value="{{ old('principal_name') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 bg-surface text-on-surface py-2 px-3 transition-shadow">
                </div>
                <div>
                    <label class="block text-label-md font-medium text-on-surface mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 bg-surface text-on-surface py-2 px-3 transition-shadow">
                </div>

                <div>
                    <label class="block text-label-md font-medium text-on-surface mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 bg-surface text-on-surface py-2 px-3 transition-shadow">
                </div>
                <div>
                    <label class="block text-label-md font-medium text-on-surface mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 bg-surface text-on-surface py-2 px-3 transition-shadow">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-label-md font-medium text-on-surface mb-2">Full Address</label>
                    <textarea name="address" rows="3" class="w-full rounded-lg border-outline-variant focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 bg-surface text-on-surface py-2 px-3 transition-shadow">{{ old('address') }}</textarea>
                </div>

                <div class="md:col-span-2 pt-4 border-t border-outline-variant border-opacity-50">
                    <label class="block text-label-md font-medium text-on-surface mb-3">Branch Logo (Optional)</label>
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-surface-container-low border border-dashed border-outline-variant rounded-lg flex flex-col items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-[24px] mb-1">image</span>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-secondary
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-primary-container file:text-primary
                                hover:file:bg-primary hover:file:text-white cursor-pointer transition-colors
                            "/>
                            <p class="text-xs text-secondary mt-2">Recommended: Square image (PNG or JPG), max 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-t border-outline-variant bg-surface-bright flex justify-end gap-3">
                <a href="{{ route('admin.branches.index') }}" class="px-6 py-2.5 border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-low transition-colors">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span> Save Branch
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
