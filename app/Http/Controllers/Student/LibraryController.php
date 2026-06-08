<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; // We will use DB facade if no explicit model exists

class LibraryController extends Controller
{
    public function index()
    {
        // Fetch books from library_books or books table if exists
        $books = DB::table('books')->orderBy('title')->paginate(15);
        return view('student.library', compact('books'));
    }
}
