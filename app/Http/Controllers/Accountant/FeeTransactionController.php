<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeeTransactionController extends Controller
{
    public function index()
    {
        return redirect()->route('accountant.dashboard')->with('success', 'Fee Transactions module is coming soon!');
    }
}
