<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashBookController extends Controller
{
    public function index()
    {
        return redirect()->route('accountant.dashboard')->with('success', 'Cash Book module is coming soon!');
    }
}
