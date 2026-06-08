<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        try {
            $events = DB::table('events')->orderBy('start_date', 'desc')->get();
            return response()->json(['status' => 'success', 'data' => $events]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        if (!$request->has(['title', 'start_date'])) {
            return response()->json(['status' => 'error', 'message' => 'Missing required fields.'], 400);
        }
        
        try {
            $id = DB::table('events')->insertGetId([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'location' => $request->input('location'),
                'type' => $request->input('type', 'Event'),
                'image_url' => $request->input('image_url')
            ]);
            return response()->json(['status' => 'success', 'message' => 'Event created successfully.', 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
