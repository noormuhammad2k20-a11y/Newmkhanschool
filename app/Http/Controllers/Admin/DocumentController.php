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

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentGeneratorService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index()
    {
        $documents = IssuedDocument::with(['student', 'template', 'issuedBy'])
            ->orderBy('id', 'desc')
            ->paginate(20);
        return view('admin.documents.index', compact('documents'));
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
            'signature' => '<div style="border-top: 2px solid #333; margin-top: 50px; padding-top: 5px; font-weight: bold; font-family: \'Times New Roman\', serif;">Principal\'s Signature & Stamp</div>'
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

        return view('admin.documents.preview', compact('content', 'student', 'template', 'extra'));
    }

    public function generate(GenerateDocumentRequest $request)
    {
        $student = Student::findOrFail($request->student_id);
        $template = DocumentTemplate::findOrFail($request->template_id);
        
        $content = $request->manual_content; // content passed from the preview step
        
        if (!$content) {
             return redirect()->route('admin.documents.create')->with('error', 'Content missing for generation.');
        }

        // Generate document number
        $docNo = strtoupper(substr($template->slug, 0, 2)) . '-' . now()->format('Ymd') . '-' . str_pad(IssuedDocument::count() + 1, 5, '0', STR_PAD_LEFT);

        $uuid = Str::uuid()->toString();
        $qrCodePath = null;
        $qrCodeBase64 = null;

        if ($template->has_qr) {
            // Generate QR code and save to storage
            $qrCodeData = route('verify.qr', ['uuid' => $uuid]);
            $qrCodeImage = QrCode::format('svg')->size(150)->generate($qrCodeData);
            $qrCodePath = 'documents/qr/' . $uuid . '.svg';
            Storage::disk('public')->put($qrCodePath, $qrCodeImage);
            
            // To embed in PDF directly, we can pass base64
            $qrCodeBase64 = base64_encode($qrCodeImage);
        }

        // Get signature if needed
        $signatureBase64 = null;
        if ($template->has_signature) {
            $school = \App\Models\School::find(auth()->user()->school_id ?? 1) ?? \App\Models\School::first();
            if ($school && $school->principal_signature_path && Storage::disk('public')->exists($school->principal_signature_path)) {
                $sigContent = Storage::disk('public')->get($school->principal_signature_path);
                $mimeType = Storage::disk('public')->mimeType($school->principal_signature_path);
                $signatureBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($sigContent);
            }
        }

        // Pass extra variables for final content replacement
        $extraParams = [
            'purpose' => $request->purpose,
            'academic_year' => $request->academic_year,
            'certificate_no' => $docNo,
            'qr_code' => $qrCodeBase64 ? '<img src="data:image/svg+xml;base64,'.$qrCodeBase64.'" style="width: 110px; height: 110px;" alt="QR Code">' : '',
            'signature' => $signatureBase64 ? '<img src="'.$signatureBase64.'" style="max-width: 180px; max-height: 90px;" alt="Signature"><br><div style="border-top: 2px solid #333; margin-top: 10px; padding-top: 5px; font-weight: bold; font-family: \'Times New Roman\', serif;">Principal\'s Signature & Stamp</div>' : '<br><br><br><br><div style="border-top: 2px solid #333; margin-top: 10px; padding-top: 5px; font-weight: bold; font-family: \'Times New Roman\', serif;">Principal\'s Signature & Stamp</div>'
        ];
        
        // Re-fill the template with final values (since manual_content might still contain placeholders if the user didn't overwrite them)
        // If the user manually edited `{{qr_code}}` out, it won't be replaced, which is fine.
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
