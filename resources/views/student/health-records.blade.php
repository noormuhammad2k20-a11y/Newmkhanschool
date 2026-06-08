@extends('layouts.app')

@section('title', 'Health Records')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Health Records</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Medical history and clinic visits</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    @if(isset($records) && count($records) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 font-medium border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Issue/Checkup</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($records as $record)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4">{{ $record->issue }}</td>
                        <td class="px-6 py-4">{{ $record->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">medical_services</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Health Records</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">There are no medical records associated with your profile.</p>
        </div>
    @endif
</div>
@endsection
