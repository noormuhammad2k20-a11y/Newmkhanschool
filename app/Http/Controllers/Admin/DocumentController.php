<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use App\Models\IssuedDocument;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = IssuedDocument::with(['student','template','issuedBy'])
            ->latest()->paginate(20);
        $templates = DocumentTemplate::where('is_active',1)->get();
        $students  = Student::with('currentClass')->where('status','Active')->get();
        return view('admin.documents.index', compact('documents','templates','students'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'student_id'  => 'required|exists:students,id',
            'template_id' => 'required|exists:document_templates,id',
            'purpose'     => 'nullable|string|max:500',
        ]);

        $student      = Student::with(['currentClass','currentSection'])->findOrFail($request->student_id);
        $template     = DocumentTemplate::findOrFail($request->template_id);
        $academicYear = AcademicYear::where('is_active',1)->first();

        // Replace template variables with real data
        $variables = [
            '{{school_name}}'    => config('app.school_name','MKhan School'),
            '{{student_name}}'   => $student->first_name . ' ' . $student->last_name,
            '{{father_name}}'    => $student->father_name,
            '{{admission_no}}'   => $student->admission_no,
            '{{class_name}}'     => $student->currentClass?->name ?? '',
            '{{admission_date}}' => $student->admission_date ? date('d M Y', strtotime($student->admission_date)) : '',
            '{{leaving_date}}'   => now()->format('d M Y'),
            '{{address}}'        => $student->address ?? '',
            '{{academic_year}}' => $academicYear ? ($academicYear->start_date . ' - ' . $academicYear->end_date) : '',
            '{{purpose}}'        => $request->purpose ?? 'official use',
            '{{date}}'           => now()->format('d M Y'),
        ];

        $content = str_replace(
            array_keys($variables),
            array_values($variables),
            $template->content
        );

        // Generate document number
        $docNo = strtoupper(substr($template->slug,0,2)) . '-' . now()->format('Ymd') . '-' . str_pad(IssuedDocument::count()+1,5,'0',STR_PAD_LEFT);

        $issued = IssuedDocument::create([
            'student_id'  => $student->id,
            'template_id' => $template->id,
            'document_no' => $docNo,
            'issued_by'   => auth()->id(),
            'purpose'     => $request->purpose,
        ]);

        // Generate PDF
        $pdf = Pdf::loadView('admin.documents.pdf', [
            'content'   => $content,
            'document'  => $issued,
            'student'   => $student,
            'template'  => $template,
            'issued_at' => now()->format('d M Y'),
        ])->setPaper('a4','portrait');

        return $pdf->download("{$template->slug}_{$student->admission_no}_{$docNo}.pdf");
    }

    public function templates()
    {
        $templates = DocumentTemplate::all();
        return view('admin.documents.templates', compact('templates'));
    }

    public function editTemplate($id)
    {
        $template = DocumentTemplate::findOrFail($id);
        return view('admin.documents.edit-template', compact('template'));
    }

    public function updateTemplate(Request $request, $id)
    {
        $template = DocumentTemplate::findOrFail($id);
        $request->validate(['content' => 'required|string']);
        $template->update(['content' => $request->content]);
        return back()->with('success','Template updated.');
    }
}
