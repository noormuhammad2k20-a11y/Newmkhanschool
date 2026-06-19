@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="mb-lg flex justify-between items-center">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">Certificate Templates Manager</h2>
            <p class="text-body-md text-secondary">Manage and customize document templates for your branch.</p>
        </div>
    </div>

<div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="py-sm px-md text-label-md font-label-md text-secondary uppercase tracking-wider">Template Name</th>
                        <th class="py-sm px-md text-label-md font-label-md text-secondary uppercase tracking-wider">Design Type</th>
                        <th class="py-sm px-md text-label-md font-label-md text-secondary uppercase tracking-wider">QR Code</th>
                        <th class="py-sm px-md text-label-md font-label-md text-secondary uppercase tracking-wider">Digital Signature</th>
                        <th class="py-sm px-md text-label-md font-label-md text-secondary uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @foreach($templates as $template)
                    <tr class="hover:bg-surface-container-lowest transition-colors">
                        <td class="py-md px-md">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary-container flex items-center justify-center shrink-0">
                                    <span class="material-symbols-rounded">design_services</span>
                                </div>
                                <span class="font-headline-md text-on-surface">{{ $template->name }}</span>
                            </div>
                        </td>
                        <td class="py-md px-md">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-container text-on-secondary-container capitalize">
                                {{ $template->design_type }}
                            </span>
                        </td>
                        <td class="py-md px-md">
                            @if($template->has_qr)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    <span class="material-symbols-rounded text-[14px]">qr_code_2</span> Enabled
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-container-high text-secondary">
                                    <span class="material-symbols-rounded text-[14px]">block</span> Disabled
                                </span>
                            @endif
                        </td>
                        <td class="py-md px-md">
                            @if($template->has_signature)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    <span class="material-symbols-rounded text-[14px]">draw</span> Enabled
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-surface-container-high text-secondary">
                                    <span class="material-symbols-rounded text-[14px]">block</span> Disabled
                                </span>
                            @endif
                        </td>
                        <td class="py-md px-md text-right">
                            <a href="{{ route('admin.documents.templates.edit', $template->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-fixed text-on-primary-fixed hover:bg-primary-fixed-dim rounded-lg text-label-md font-label-md transition-colors">
                                <span class="material-symbols-rounded text-[16px]">edit</span> Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($templates->isEmpty())
            <div class="p-xl text-center text-secondary">
                <span class="material-symbols-rounded text-[48px] opacity-50 mb-2">inventory_2</span>
                <p>No templates found.</p>
            </div>
        @endif
    </div>
</div>
@endsection
