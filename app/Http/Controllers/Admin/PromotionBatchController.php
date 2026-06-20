<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromotionBatch;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Services\StudentPromotionService;

class PromotionBatchController extends Controller
{
    protected $promotionService;

    public function __construct(StudentPromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function index()
    {
        $batches = PromotionBatch::with(['fromSession', 'toSession', 'fromClass', 'toClass', 'creator'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        $metrics = [
            'total' => PromotionBatch::count(),
            'pending' => PromotionBatch::where('status', 'pending_approval')->count(),
            'executed' => PromotionBatch::where('status', 'executed')->count(),
            'failed_promotions' => \App\Models\PromotionBatchStudent::where('status', 'failed')->count(),
        ];

        return view('admin.promotions.batches.index', compact('batches', 'metrics'));
    }

    public function aiDashboard()
    {
        $detector = new \App\Services\AcademicCycleDetector();
        $isWindowActive = $detector->isPromotionWindowActive();
        
        $pendingBatches = PromotionBatch::with(['students.student'])
            ->where('status', 'pending_approval')
            ->get();
            
        $metrics = [
            'total_analyzed' => 0,
            'eligible' => 0,
            'conditional' => 0,
            'defaulters' => 0,
        ];
        
        foreach ($pendingBatches as $batch) {
            $metrics['total_analyzed'] += $batch->students->count();
            $metrics['eligible'] += $batch->students->where('category', 'eligible')->count();
            $metrics['conditional'] += $batch->students->where('category', 'conditional')->count();
            $metrics['defaulters'] += $batch->students->where('category', 'defaulter')->count();
        }

        $readinessScore = $metrics['total_analyzed'] > 0 
            ? round(($metrics['eligible'] / $metrics['total_analyzed']) * 100) 
            : 0;

        return view('admin.promotions.ai_dashboard', compact('isWindowActive', 'pendingBatches', 'metrics', 'readinessScore'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $activeYear = AcademicYear::where('is_active', 1)->first();

        return view('admin.promotions.batches.create', compact('academicYears', 'classes', 'activeYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_academic_year_id' => 'required|exists:academic_years,id',
            'to_academic_year_id' => 'required|exists:academic_years,id|different:from_academic_year_id',
            'from_class_id' => 'required|exists:classes,id',
            'to_class_id' => 'required|exists:classes,id',
            'from_section_id' => 'nullable|exists:sections,id',
            'to_section_id' => 'nullable|exists:sections,id',
        ]);

        try {
            $batch = $this->promotionService->generateBatch($validated);
            return redirect()->route('admin.promotions.batches.show', $batch->id)
                ->with('success', 'Batch generated successfully and is pending approval.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate batch: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $batch = PromotionBatch::with(['fromSession', 'toSession', 'fromClass', 'toClass', 'fromSection', 'toSection', 'creator', 'approver', 'students.student'])
            ->findOrFail($id);

        $eligibleCount = $batch->students->where('status', 'pending')->count();
        $failedCount = $batch->students->where('status', 'failed')->count();

        return view('admin.promotions.batches.show', compact('batch', 'eligibleCount', 'failedCount'));
    }

    public function approve($id)
    {
        $batch = PromotionBatch::findOrFail($id);
        
        if ($batch->status !== 'pending_approval') {
            return back()->with('error', 'Only pending batches can be approved.');
        }

        $batch->update(['status' => 'approved', 'approved_by' => auth()->id()]);

        try {
            $this->promotionService->executeBatch($batch->id, auth()->id());
            return redirect()->route('admin.promotions.batches.show', $batch->id)
                ->with('success', 'Batch approved and executed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Execution failed: ' . $e->getMessage());
        }
    }

    public function reject($id)
    {
        $batch = PromotionBatch::findOrFail($id);
        
        if ($batch->status !== 'pending_approval') {
            return back()->with('error', 'Only pending batches can be rejected.');
        }

        $batch->update(['status' => 'rejected']);
        return redirect()->route('admin.promotions.batches.show', $batch->id)
            ->with('success', 'Batch rejected successfully.');
    }
}
