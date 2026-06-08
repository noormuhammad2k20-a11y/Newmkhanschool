@extends('layouts.app')

@section('title', 'Library Books')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Library</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Browse school library resources</p>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    @if(isset($books) && count($books) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 font-medium border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Author</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($books as $book)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $book->title }}</td>
                        <td class="px-6 py-4">{{ $book->author }}</td>
                        <td class="px-6 py-4">{{ $book->category }}</td>
                        <td class="px-6 py-4">
                            @if($book->status === 'Available')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Available</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Issued</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $books->links() }}
        </div>
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">menu_book</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Books Found</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">The library catalog is currently empty.</p>
        </div>
    @endif
</div>
@endsection
