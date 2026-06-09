@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-surface p-lg">
    <div class="max-w-max-width mx-auto">
        
        <div class="flex justify-between items-end mb-lg">
            <div>
                <h2 class="font-headline-xl text-headline-xl font-bold text-on-surface">Roles & Permissions</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-sm">Manage access control and permissions for different system roles.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-[#d3e2ed] border border-[#bac9d3] text-[#0f1d25] px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
                <span class="block sm:inline font-body-md">{{ session('success') }}</span>
            </div>
        @endif

        <div class="space-y-lg">
            @foreach($roles as $role)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                    <h3 class="font-headline-md text-headline-md font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">shield_person</span>
                        {{ $role->name }}
                    </h3>
                    @if($role->name === 'Super Admin')
                        <span class="bg-error-container text-error px-3 py-1 rounded-full text-label-sm font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">warning</span> Careful: Core Role
                        </span>
                    @endif
                </div>
                <form action="{{ route('admin.roles.permissions.update', $role->id) }}" method="POST" class="p-lg">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
                        @foreach($permissions as $permission)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-outline hover:bg-surface-container-low cursor-pointer transition-colors group">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                                class="w-5 h-5 text-primary border-outline rounded focus:ring-primary focus:ring-2">
                            <div class="flex flex-col">
                                <span class="font-label-md text-on-surface group-hover:text-primary transition-colors">{{ str_replace('_', ' ', Str::title($permission->name)) }}</span>
                                <span class="text-secondary font-body-sm">{{ $permission->name }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    <div class="mt-lg pt-md border-t border-outline-variant text-right">
                        <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary font-label-md py-2 px-6 rounded-full shadow transition-colors flex items-center justify-center gap-2 ml-auto">
                            <span class="material-symbols-outlined text-[20px]">save</span> Save {{ $role->name }} Permissions
                        </button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>

    </div>
</main>
@endsection
