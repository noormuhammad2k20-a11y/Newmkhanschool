<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use App\Models\IssuedDocument;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Services\DocumentGeneratorService;
use App\Http\Requests\Admin\GenerateDocumentRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentGeneratorService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index(Request $request)
    {
        // Statistics
        $totalDocuments = IssuedDocument::count();
        $thisMonthDocuments = IssuedDocument::whereMonth('issued_at', now()->month)
            ->whereYear('issued_at', now()->year)
            ->count();

        // Per-template stats
        $templateStats = DB::table('issued_documents')
            ->join('document_templates', 'issued_documents.template_id', '=', 'document_templates.id')
            ->select('document_templates.name', DB::raw('count(*) as total'))
            ->groupBy('document_templates.name')
            ->pluck('total', 'name')
            ->toArray();

        // Templates for the generator form
        $templates = DocumentTemplate::where('is_active', 1)->get();
        $academicYear = AcademicYear::where('is_active', 1)->first();

        // Classes for the student search
        $classes = \App\Models\SchoolClass::all();
        $sections = \App\Models\Section::all();

        // Recent documents
        $documents = IssuedDocument::with(['student.currentClass', 'template', 'issuedBy'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        // Recent activity — last 8 documents
        $recentActivity = IssuedDocument::with(['student.currentClass', 'template', 'issuedBy'])
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        // Student search (AJAX or page-based)
        $searchStudents = collect();
        $studentSearch = $request->input('student_search');
        $searchClassId = $request->input('search_class_id');
        if ($studentSearch || $searchClassId) {
            $sq = Student::with(['currentClass', 'currentSection']);
            if ($studentSearch) {
                $sq->where(function($q) use ($studentSearch) {
                    $q->where('first_name', 'like', "%{$studentSearch}%")
                      ->orWhere('last_name', 'like', "%{$studentSearch}%")
                      ->orWhere('admission_no', 'like', "%{$studentSearch}%");
                });
            }
            if ($searchClassId) {
                $sq->where('current_class_id', $searchClassId);
            }
            $searchStudents = $sq->take(20)->get();
        }

        return view('admin.documents.index', compact(
            'totalDocuments',
            'thisMonthDocuments',
            'templateStats',
            'templates',
            'academicYear',
            'classes',
            'sections',
            'documents',
            'recentActivity',
            'searchStudents',
            'studentSearch',
            'searchClassId'
        ));
    }

    public function ajaxSearch(Request $request)
    {
        $query = $request->get('query');
        $class_id = $request->get('class_id');
        $section_id = $request->get('section_id');

        if (empty($query) && empty($class_id)) {
            return response()->json([]);
        }

        $students = Student::with(['currentClass', 'currentSection']);
        
        if (!empty($query)) {
            $students->where(function($q) use ($query) {
                $q->where('first_name', 'like', "%$query%")
                  ->orWhere('last_name', 'like', "%$query%")
                  ->orWhere('admission_no', 'like', "%$query%");
            });
        }
        
        if (!empty($class_id)) {
            $students->where('current_class_id', $class_id);
        }
        
        if (!empty($section_id)) {
            $students->where('current_section_id', $section_id);
        }

        return response()->json($students->take(200)->get()->map(function($student) {
            return [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'admission_no' => $student->admission_no,
                'class_name' => $student->currentClass ? $student->currentClass->name : 'N/A',
                'section_name' => $student->currentSection ? $student->currentSection->name : ''
            ];
        }));
    }

    public function create(Request $request)
    {
        $search = $request->input('search');
        $class_id = $request->input('class_id');
        $section_id = $request->input('section_id');
        $gender = $request->input('gender');
        $status = $request->input('status', 'Regular');

        $classes = \App\Models\SchoolClass::all();
        $sections = \App\Models\Section::all();

        $query = Student::with(['currentClass', 'currentSection']);
        
        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('admission_no', 'like', "%{$search}%");
            });
        }
        
        if ($class_id) {
            $query->where('current_class_id', $class_id);
        }
        
        if ($section_id) {
            $query->where('current_section_id', $section_id);
        }
        
        if ($gender) {
            $query->where('gender', $gender);
        }

        $students = $query->paginate(12)->appends($request->all());

        return view('admin.documents.create', compact('students', 'search', 'classes', 'sections', 'class_id', 'section_id', 'gender', 'status'));
    }

    public function selectTemplate($studentId)
    {
        $student = Student::with(['currentClass', 'currentSection'])->findOrFail($studentId);
        $templates = DocumentTemplate::where('is_active', 1)->get();
        $academicYear = AcademicYear::where('is_active', 1)->first();

        return view('admin.documents.select-template', compact('student', 'templates', 'academicYear'));
    }

    public function preview(GenerateDocumentRequest $request)
    {
        $student = Student::with(['currentClass', 'currentSection'])->findOrFail($request->student_id);
        $template = DocumentTemplate::findOrFail($request->template_id);
        
        $extra = [
            'purpose' => $request->purpose,
            'academic_year' => $request->academic_year,
            'certificate_no' => 'PREVIEW-001',
            'qr_code' => '<div style="border: 2px solid #eee; padding: 5px; display: inline-block; background: #fff; width: 110px; height: 110px; display: flex; align-items: center; justify-content: center; color: #999;">QR Code</div>',
            'signature' => '<div style="color: #999; font-style: italic; margin-bottom: 5px;">[Signature Area]</div>'
        ];

        // If manual content is provided (user edited inline), use it
        if ($request->manual_content) {
            $content = $request->manual_content;
        } else {
            // Fill initial template
            $content = $this->documentService->fillTemplate($template, $student, $extra);
            
            // If AI Enhancement is requested
            if ($request->ai_enhance) {
                $content = $this->documentService->aiEnhance($content, $template->name);
            }
        }

        // If AJAX request, return JSON for inline preview
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'content' => $content,
                'student' => [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'admission_no' => $student->admission_no,
                    'class' => $student->currentClass?->name ?? 'N/A'
                ],
                'template' => [
                    'id' => $template->id,
                    'name' => $template->name
                ]
            ]);
        }

        return view('admin.documents.preview', compact('content', 'student', 'template', 'extra'));
    }

    public function generate(GenerateDocumentRequest $request)
    {
        $student = Student::findOrFail($request->student_id);
        $template = DocumentTemplate::findOrFail($request->template_id);
        
        $content = $request->manual_content; // content passed from the preview step

        // Generate document number
        $docNo = strtoupper(substr($template->slug, 0, 2)) . '-' . now()->format('Ymd') . '-' . str_pad(IssuedDocument::count() + 1, 5, '0', STR_PAD_LEFT);
        
        // Generate UUID for QR verification
        $uuid = Str::uuid()->toString();
        $qrCodePath = null;
        $qrCodeBase64 = null;
        $qrCodeHtml = '';

        if ($template->has_qr) {
            $qrCodeData = route('verify.qr', ['uuid' => $uuid]);
            $qrCodeImage = QrCode::format('svg')->size(150)->generate($qrCodeData);
            $qrCodePath = 'documents/qr/' . $uuid . '.svg';
            Storage::disk('public')->put($qrCodePath, $qrCodeImage);
            $qrCodeBase64 = base64_encode($qrCodeImage);
            $qrCodeHtml = '<img src="data:image/svg+xml;base64,'.$qrCodeBase64.'" style="width: 110px; height: 110px;" alt="QR Code">';
        }

        // Get signature if needed
        $signatureBase64 = null;
        $signatureHtml = '';
        if ($template->has_signature) {
            $school = \App\Models\School::find(auth()->user()->school_id ?? 1) ?? \App\Models\School::first();
            if ($school && $school->principal_signature_path && Storage::disk('public')->exists($school->principal_signature_path)) {
                $sigContent = Storage::disk('public')->get($school->principal_signature_path);
                $mimeType = Storage::disk('public')->mimeType($school->principal_signature_path);
                $signatureBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($sigContent);
                $signatureHtml = '<img src="'.$signatureBase64.'" style="max-width: 180px; max-height: 90px; margin-bottom: 5px;" alt="Signature">';
            }
        }

        if (!$content) {
            $extra = [
                'purpose' => $request->purpose,
                'academic_year' => $request->academic_year,
                'certificate_no' => $docNo,
                'qr_code' => $qrCodeHtml,
                'signature' => $signatureHtml,
            ];
            
            $content = $this->documentService->fillTemplate($template, $student, $extra);
            
            // If AI Enhancement is requested
            if ($request->ai_enhance) {
                $content = $this->documentService->aiEnhance($content, $template->name);
            }
        }

        // Legacy fallback: replace any remaining {{variable}} placeholders in DB-content templates
        $extraParams = [
            'certificate_no' => $docNo,
            'qr_code' => $qrCodeHtml,
            'signature' => $signatureHtml,
            'purpose' => $request->purpose,
            'academic_year' => $request->academic_year,
        ];
        
        foreach ($extraParams as $key => $value) {
            $content = str_replace('{{'.$key.'}}', $value, $content);
        }

        // Render PDF view
        $pdfHtml = view('admin.documents.pdf', [
            'content' => $content,
            'template' => $template,
            'student' => $student
        ])->render();

        $filename = "{$template->slug}_{$student->admission_no}_{$docNo}";
        $path = $this->documentService->generatePDF($pdfHtml, $filename);

        $issued = IssuedDocument::create([
            'uuid' => $uuid,
            'student_id' => $student->id,
            'template_id' => $template->id,
            'document_no' => $docNo,
            'issued_by' => auth()->id(),
            'purpose' => $request->purpose,
            'pdf_path' => $path,
            'qr_code_path' => $qrCodePath
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Document generated successfully.',
                'document' => [
                    'id' => $issued->id,
                    'document_no' => $docNo,
                    'download_url' => route('admin.documents.download', $issued->id),
                    'print_html' => base64_encode($pdfHtml)
                ]
            ]);
        }

        return redirect()->route('admin.documents.index')->with('success', 'Document generated successfully.');
    }

    public function download($id)
    {
        $document = IssuedDocument::findOrFail($id);
        if ($document->pdf_path && Storage::disk('public')->exists($document->pdf_path)) {
            return Storage::disk('public')->download($document->pdf_path);
        }
        return back()->with('error', 'Document file not found.');
    }

    public function destroy($id)
    {
        $document = IssuedDocument::findOrFail($id);
        if ($document->pdf_path && Storage::disk('public')->exists($document->pdf_path)) {
            Storage::disk('public')->delete($document->pdf_path);
        }
        if ($document->qr_code_path && Storage::disk('public')->exists($document->qr_code_path)) {
            Storage::disk('public')->delete($document->qr_code_path);
        }
        $document->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Document deleted successfully.']);
        }

        return back()->with('success', 'Document deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:issued_documents,id'
        ]);

        $documents = IssuedDocument::whereIn('id', $request->ids)->get();
        $count = 0;

        foreach ($documents as $document) {
            if ($document->pdf_path && Storage::disk('public')->exists($document->pdf_path)) {
                Storage::disk('public')->delete($document->pdf_path);
            }
            if ($document->qr_code_path && Storage::disk('public')->exists($document->qr_code_path)) {
                Storage::disk('public')->delete($document->qr_code_path);
            }
            $document->delete();
            $count++;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "$count documents deleted successfully."]);
        }

        return back()->with('success', "$count documents deleted successfully.");
    }

    public function destroyAll(Request $request)
    {
        $documents = IssuedDocument::all();
        $count = 0;

        foreach ($documents as $document) {
            if ($document->pdf_path && Storage::disk('public')->exists($document->pdf_path)) {
                Storage::disk('public')->delete($document->pdf_path);
            }
            if ($document->qr_code_path && Storage::disk('public')->exists($document->qr_code_path)) {
                Storage::disk('public')->delete($document->qr_code_path);
            }
            $document->delete();
            $count++;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "All ($count) documents deleted successfully."]);
        }

        return back()->with('success', "All ($count) documents deleted successfully.");
    }

    public function studentHistory($studentId)
    {
        $student = Student::with(['currentClass', 'currentSection'])->findOrFail($studentId);
        $documents = IssuedDocument::with(['template', 'issuedBy'])
            ->where('student_id', $studentId)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'student' => [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'admission_no' => $student->admission_no,
                'class' => $student->currentClass?->name ?? 'N/A',
                'section' => $student->currentSection?->name ?? ''
            ],
            'documents' => $documents->map(function($doc) {
                return [
                    'id' => $doc->id,
                    'document_no' => $doc->document_no,
                    'template_name' => $doc->template->name ?? 'Unknown',
                    'purpose' => $doc->purpose,
                    'issued_at' => $doc->issued_at ? $doc->issued_at->format('d M Y, h:i A') : 'N/A',
                    'issued_by' => $doc->issuedBy->name ?? 'System',
                    'download_url' => route('admin.documents.download', $doc->id)
                ];
            })
        ]);
    }

    public function templates()
    {
        $templates = DocumentTemplate::all();
        return view('admin.documents.templates.index', compact('templates'));
    }

    public function editTemplate($id)
    {
        $template = DocumentTemplate::findOrFail($id);
        return view('admin.documents.templates.edit', compact('template'));
    }

    public function updateTemplate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'design_type' => 'required|string',
            'content' => 'required|string',
            'has_qr' => 'boolean',
            'has_signature' => 'boolean',
        ]);

        $template = DocumentTemplate::findOrFail($id);
        $template->update([
            'name' => $request->name,
            'design_type' => $request->design_type,
            'content' => $request->content,
            'has_qr' => $request->has('has_qr'),
            'has_signature' => $request->has('has_signature'),
        ]);

        return redirect()->route('admin.documents.templates')->with('success', 'Template updated successfully.');
    }

    public function signatures()
    {
        $school = \App\Models\School::find(auth()->user()->school_id ?? 1) ?? \App\Models\School::first();
        return view('admin.documents.signatures', compact('school'));
    }

    public function updateSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $school = \App\Models\School::find(auth()->user()->school_id ?? 1) ?? \App\Models\School::first();
        
        if (!$school) {
            return redirect()->back()->with('error', 'School configuration not found. Please setup the school first.');
        }

        if ($school->principal_signature_path) {
            Storage::disk('public')->delete($school->principal_signature_path);
        }

        $path = $request->file('signature')->store('signatures', 'public');
        $school->principal_signature_path = $path;
        $school->save();

        return redirect()->back()->with('success', 'Digital signature uploaded successfully.');
    }
}
