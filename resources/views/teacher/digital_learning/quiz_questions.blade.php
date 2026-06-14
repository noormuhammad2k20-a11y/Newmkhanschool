@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('teacher.digital_learning.quizzes') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-low hover:bg-surface-container transition-colors text-on-surface">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Manage Questions: {{ $quiz->title }}</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Class: {{ $quiz->class->name ?? 'N/A' }} | Subject: {{ $quiz->subject->name ?? 'N/A' }}</p>
        </div>
        <div class="ml-auto flex items-center gap-2">
            <button onclick="document.getElementById('bulkAddQuestionModal').classList.remove('hidden')" class="flex items-center gap-sm px-md py-sm border border-outline-variant bg-surface-container-low text-on-surface rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[20px]">upload_file</span>
                <span class="font-label-md font-semibold">Bulk Add</span>
            </button>
            <button onclick="document.getElementById('addQuestionModal').classList.remove('hidden')" class="flex items-center gap-sm px-md py-sm bg-primary text-on-primary rounded-full hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-[20px]">add</span>
                <span class="font-label-md font-semibold">Add Question</span>
            </button>
        </div>
    </div>

<div class="space-y-4">
        @forelse($quiz->questions->sortBy('order') as $index =>q)
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 relative group">
                <div class="absolute top-4 right-4 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditQuestionModal({{ json_encode($q) }})" class="text-primary hover:text-primary/80" title="Edit">
                        <span class="material-symbols-outlined">edit</span>
                    </button>
                    <form action="{{ route('teacher.digital_learning.quizzes.questions.destroy', [$quiz->id, $q->id]) }}" method="POST" data-confirm="Delete this question?">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-error hover:text-error/80" title="Delete">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </form>
                </div>
                
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-headline-md text-on-surface"><span class="text-primary font-bold">Q{{ $index + 1 }}.</span> {{ $q->question_text }}</h3>
                    <span class="px-2 py-1 text-xs rounded-full bg-surface-container-high text-on-surface-variant font-label-md">
                        {{ ucwords(str_replace('_', ' ', $q->question_type ?? 'single')) }} Choice
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php $correctArr = explode(',', $q->correct_option); @endphp
                    <div class="p-3 rounded-lg border {{ in_array('a', $correctArr) ? 'bg-green-50 border-green-200' : 'bg-surface-container-low border-outline-variant' }}">
                        <span class="font-bold mr-2 {{ in_array('a', $correctArr) ? 'text-green-700' : 'text-on-surface-variant' }}">A.</span>
                        <span class="text-on-surface">{{ $q->option_a }}</span>
                    </div>
                    <div class="p-3 rounded-lg border {{ in_array('b', $correctArr) ? 'bg-green-50 border-green-200' : 'bg-surface-container-low border-outline-variant' }}">
                        <span class="font-bold mr-2 {{ in_array('b', $correctArr) ? 'text-green-700' : 'text-on-surface-variant' }}">B.</span>
                        <span class="text-on-surface">{{ $q->option_b }}</span>
                    </div>
                    @if($q->question_type !== 'true_false')
                    <div class="p-3 rounded-lg border {{ in_array('c', $correctArr) ? 'bg-green-50 border-green-200' : 'bg-surface-container-low border-outline-variant' }}">
                        <span class="font-bold mr-2 {{ in_array('c', $correctArr) ? 'text-green-700' : 'text-on-surface-variant' }}">C.</span>
                        <span class="text-on-surface">{{ $q->option_c }}</span>
                    </div>
                    <div class="p-3 rounded-lg border {{ in_array('d', $correctArr) ? 'bg-green-50 border-green-200' : 'bg-surface-container-low border-outline-variant' }}">
                        <span class="font-bold mr-2 {{ in_array('d', $correctArr) ? 'text-green-700' : 'text-on-surface-variant' }}">D.</span>
                        <span class="text-on-surface">{{ $q->option_d }}</span>
                    </div>
                    @endif
                </div>
                <div class="mt-4 flex justify-between items-center text-sm font-label-md text-on-surface-variant">
                    <span>Marks: <span class="font-bold text-on-surface">{{ $q->marks }}</span></span>
                    <span>Order: {{ $q->order }}</span>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-surface-container-lowest rounded-xl border border-outline-variant border-dashed">
                <span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-4">quiz</span>
                <p class="font-body-lg text-on-surface-variant">No questions added yet.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Question Modal -->
<div id="addQuestionModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-outline-variant flex justify-between items-center">
            <h2 class="font-headline-md text-on-surface">Add Question</h2>
            <button onclick="document.getElementById('addQuestionModal').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('teacher.digital_learning.quizzes.questions.store', $quiz->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Question Type <span class="text-error">*</span></label>
                    <select name="question_type" id="question_type" onchange="toggleQuestionType()" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="single">Single Choice</option>
                        <option value="multiple">Multiple Choice</option>
                        <option value="true_false">True / False</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-1">Question Text <span class="text-error">*</span></label>
                <textarea name="question_text" required rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="optionsContainer">
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Option A <span class="text-error">*</span></label>
                    <input type="text" name="option_a" id="option_a" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Option B <span class="text-error">*</span></label>
                    <input type="text" name="option_b" id="option_b" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div class="option-cd">
                    <label class="block font-label-md text-on-surface mb-1">Option C <span class="text-error">*</span></label>
                    <input type="text" name="option_c" id="option_c" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div class="option-cd">
                    <label class="block font-label-md text-on-surface mb-1">Option D <span class="text-error">*</span></label>
                    <input type="text" name="option_d" id="option_d" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Correct Option <span class="text-error">*</span></label>
                    
                    <div id="singleChoiceSelect">
                        <select name="correct_option" id="correct_option_single" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="a">Option A</option>
                            <option value="b">Option B</option>
                            <option class="option-cd-opt" value="c">Option C</option>
                            <option class="option-cd-opt" value="d">Option D</option>
                        </select>
                    </div>

                    <div id="multipleChoiceSelect" class="hidden space-y-2 mt-2">
                        <label class="flex items-center gap-2"><input type="checkbox" name="correct_option[]" value="a" class="rounded border-outline-variant text-primary focus:ring-primary"> Option A</label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="correct_option[]" value="b" class="rounded border-outline-variant text-primary focus:ring-primary"> Option B</label>
                        <label class="flex items-center gap-2 option-cd"><input type="checkbox" name="correct_option[]" value="c" class="rounded border-outline-variant text-primary focus:ring-primary"> Option C</label>
                        <label class="flex items-center gap-2 option-cd"><input type="checkbox" name="correct_option[]" value="d" class="rounded border-outline-variant text-primary focus:ring-primary"> Option D</label>
                    </div>
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Marks <span class="text-error">*</span></label>
                    <input type="number" name="marks" min="1" value="1" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Order</label>
                    <input type="number" name="order" value="{{ count($quiz->questions) + 1 }}" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
            </div>

            <div class="flex justify-end gap-sm mt-6">
                <button type="button" onclick="document.getElementById('addQuestionModal').classList.add('hidden')" class="px-4 py-2 font-label-md text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 font-label-md bg-primary text-on-primary hover:bg-primary/90 rounded-lg transition-colors">Add Question</button>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Add Question Modal -->
<div id="bulkAddQuestionModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-outline-variant flex justify-between items-center">
            <h2 class="font-headline-md text-on-surface">Bulk Add Questions</h2>
            <button onclick="document.getElementById('bulkAddQuestionModal').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('teacher.digital_learning.quizzes.questions.bulk_store', $quiz->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            
            <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant">
                <h3 class="font-label-lg font-bold text-on-surface mb-2">Instructions</h3>
                <ol class="list-decimal list-inside text-sm text-on-surface-variant space-y-1">
                    <li>Download the sample CSV file.</li>
                    <li>Fill in your questions without changing the column headers.</li>
                    <li>For true/false, leave options C and D blank.</li>
                    <li>Upload the completed file below.</li>
                </ol>
                <div class="mt-4">
                    <a href="{{ asset('samples/sample_questions.csv') }}" download class="text-primary hover:underline font-label-md flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">download</span> Download Sample CSV
                    </a>
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-1">Upload CSV File <span class="text-error">*</span></label>
                <input type="file" name="csv_file" accept=".csv" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div class="flex justify-end gap-sm mt-6">
                <button type="button" onclick="document.getElementById('bulkAddQuestionModal').classList.add('hidden')" class="px-4 py-2 font-label-md text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 font-label-md bg-primary text-on-primary hover:bg-primary/90 rounded-lg transition-colors">Upload Questions</button>
            </div>
        </form>
    </div>
</div>


<script>
    function openEditQuestionModal(question) {
        alert('Edit functionality can be implemented using a similar modal populated via JS fetch or redirecting to an edit view.');
    }

    function toggleQuestionType() {
        const type = document.getElementById('question_type').value;
        const optCds = document.querySelectorAll('.option-cd');
        const optCdOpts = document.querySelectorAll('.option-cd-opt');
        
        const optA = document.getElementById('option_a');
        const optB = document.getElementById('option_b');
        const optC = document.getElementById('option_c');
        const optD = document.getElementById('option_d');

        const singleSelect = document.getElementById('singleChoiceSelect');
        const multiSelect = document.getElementById('multipleChoiceSelect');
        
        if (type === 'true_false') {
            optCds.forEach(el => el.classList.add('hidden'));
            optCdOpts.forEach(el => el.classList.add('hidden'));
            optC.removeAttribute('required');
            optD.removeAttribute('required');
            optA.value = "True";
            optA.readOnly = true;
            optB.value = "False";
            optB.readOnly = true;
            
            singleSelect.classList.remove('hidden');
            multiSelect.classList.add('hidden');
            document.getElementById('correct_option_single').name = 'correct_option';
        } else {
            optCds.forEach(el => el.classList.remove('hidden'));
            optCdOpts.forEach(el => el.classList.remove('hidden'));
            optC.setAttribute('required', 'required');
            optD.setAttribute('required', 'required');
            optA.readOnly = false;
            optB.readOnly = false;
            if (optA.value === "True") optA.value = "";
            if (optB.value === "False") optB.value = "";

            if (type === 'multiple') {
                singleSelect.classList.add('hidden');
                multiSelect.classList.remove('hidden');
                document.getElementById('correct_option_single').name = '';
            } else {
                singleSelect.classList.remove('hidden');
                multiSelect.classList.add('hidden');
                document.getElementById('correct_option_single').name = 'correct_option';
            }
        }
    }

    // Initialize
    toggleQuestionType();
</script>
@endsection
