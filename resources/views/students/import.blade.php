@extends('layouts.app')

@section('title', 'Import Students')

@section('content')
<main class="flex-grow p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto w-full">
    <!-- Page Header -->
    <div class="mb-lg flex flex-col md:flex-row md:items-end justify-between gap-md">
        <div>
            <div class="flex items-center gap-sm text-secondary mb-xs">
                <a class="text-label-md font-label-md hover:underline" href="{{ route('students.index') }}">Students</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-label-md font-label-md text-on-surface">Bulk Import</span>
            </div>
            <h1 class="text-headline-lg-mobile md:text-headline-xl font-headline-lg-mobile md:font-headline-xl text-on-surface">Import Students</h1>
            <p class="text-body-md font-body-md text-secondary mt-1">Upload a CSV file to add multiple students at once.</p>
        </div>
        <div class="flex gap-sm">
            <a href="#" onclick="downloadTemplate(event)" class="px-md py-sm border border-outline-variant rounded bg-surface-container-lowest text-on-surface text-label-md font-label-md hover:bg-surface-container-low transition-colors flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Download CSV Template
            </a>
        </div>
    </div>

    <!-- Instructions / Rules -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md mb-lg flex flex-col gap-sm shadow-sm">
        <h3 class="text-headline-sm font-headline-sm text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl">info</span>
            Import Instructions
        </h3>
        <ul class="list-disc pl-5 text-body-md font-body-md text-secondary space-y-2">
            <li>Ensure your file is in <strong>.csv</strong> format.</li>
            <li>First name, last name, and admission number are required.</li>
            <li>Use the standard template provided above to ensure correct column mapping.</li>
            <li>Date of birth should be in YYYY-MM-DD format.</li>
            <li>Gender should be 'Male', 'Female', or 'Other'.</li>
            <li>If a student with the same admission number exists, the record will be skipped.</li>
        </ul>
    </div>

    <!-- Upload Area -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl shadow-sm flex flex-col items-center text-center">
        <div class="w-16 h-16 bg-surface-container rounded-full flex items-center justify-center mb-md text-primary">
            <span class="material-symbols-outlined text-3xl">upload_file</span>
        </div>
        <h2 class="text-headline-md font-headline-md text-on-surface mb-xs">Upload your CSV file</h2>
        <p class="text-body-md font-body-md text-secondary mb-lg">Drag and drop your file here, or click to browse</p>
        
        <form action="#" method="POST" enctype="multipart/form-data" class="w-full max-w-md" id="importForm">
            @csrf
            <div class="relative border-2 border-dashed border-outline-variant rounded-xl p-xl hover:border-primary hover:bg-surface-container-low transition-colors group cursor-pointer" onclick="document.getElementById('csv_file').click()">
                <input type="file" name="csv_file" id="csv_file" accept=".csv" class="hidden" onchange="handleFileSelect(event)">
                <div class="flex flex-col items-center pointer-events-none" id="upload-state-empty">
                    <span class="material-symbols-outlined text-secondary text-4xl mb-sm group-hover:text-primary transition-colors">add_circle</span>
                    <span class="text-label-md font-label-md text-on-surface">Select File</span>
                </div>
                <div class="hidden flex flex-col items-center pointer-events-none" id="upload-state-selected">
                    <span class="material-symbols-outlined text-primary text-4xl mb-sm">description</span>
                    <span class="text-label-md font-label-md text-on-surface" id="selected-filename">filename.csv</span>
                </div>
            </div>
            
            <div class="mt-lg">
                <button type="submit" class="w-full px-lg py-md bg-primary text-on-primary rounded-xl font-label-lg text-label-lg hover:bg-primary-dark transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" id="submitBtn" disabled>
                    Import Students
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    function downloadTemplate(e) {
        e.preventDefault();
        const headers = ["first_name", "last_name", "admission_no", "roll_no", "gender", "dob", "blood_group", "religion", "email", "phone", "address", "father_name", "mother_name"];
        const exampleRow = ["John", "Doe", "ADM-1001", "1", "Male", "2010-05-15", "O+", "Christianity", "john.doe@example.com", "1234567890", "123 Main St, City", "Robert Doe", "Jane Doe"];
        
        let csvContent = "data:text/csv;charset=utf-8," 
            + headers.join(",") + "\n"
            + exampleRow.join(",");
            
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "student_import_template.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function handleFileSelect(e) {
        const file = e.target.files[0];
        const emptyState = document.getElementById('upload-state-empty');
        const selectedState = document.getElementById('upload-state-selected');
        const filenameLabel = document.getElementById('selected-filename');
        const submitBtn = document.getElementById('submitBtn');

        if (file) {
            if (!file.name.endsWith('.csv')) {
                alert('Please select a valid CSV file.');
                e.target.value = '';
                return;
            }
            emptyState.classList.add('hidden');
            selectedState.classList.remove('hidden');
            filenameLabel.textContent = file.name;
            submitBtn.disabled = false;
        } else {
            emptyState.classList.remove('hidden');
            selectedState.classList.add('hidden');
            submitBtn.disabled = true;
        }
    }

    document.getElementById('importForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = 'Importing... <span class="material-symbols-outlined animate-spin text-sm ml-2">sync</span>';
        
        // Simulate upload / process since API endpoint isn't fully defined yet
        setTimeout(() => {
            alert('Students imported successfully!');
            window.location.href = "{{ route('students.index') }}";
        }, 1500);
    });
</script>
@endsection
