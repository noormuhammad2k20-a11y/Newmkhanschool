<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $page = (int) $request->get('page', 1);
            $limit = (int) $request->get('limit', 10);
            $offset = ($page - 1) * $limit;

            // Get metrics
            $metricsQuery = "
                SELECT 
                    SUM(CASE WHEN status = 'Paid' THEN amount ELSE 0 END) as total_collected,
                    SUM(CASE WHEN status != 'Paid' THEN amount ELSE 0 END) as total_pending,
                    COUNT(CASE WHEN status != 'Paid' THEN 1 END) as pending_students
                FROM fees
            ";
            $metrics = collect(DB::select($metricsQuery))->first();

            $totalQuery = "SELECT COUNT(*) as count FROM fees";
            $totalCount = collect(DB::select($totalQuery))->first()->count;

            // Get transactions
            $txQuery = "
                SELECT f.*, s.first_name, s.last_name, c.name as class_name, sec.name as section_name
                FROM fees f
                JOIN students s ON f.student_id = s.id
                LEFT JOIN classes c ON s.current_class_id = c.id
                LEFT JOIN sections sec ON s.current_section_id = sec.id
                ORDER BY f.due_date DESC
                LIMIT ? OFFSET ?
            ";
            $transactions = DB::select($txQuery, [$limit, $offset]);

            return response()->json([
                'status' => 'success', 
                'data' => [
                    'metrics' => [
                        'collected' => $metrics->total_collected ?? 0,
                        'pending' => $metrics->total_pending ?? 0,
                        'pending_students' => $metrics->pending_students ?? 0
                    ],
                    'transactions' => $transactions,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total' => $totalCount,
                        'last_page' => ceil($totalCount / max(1, $limit))
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
