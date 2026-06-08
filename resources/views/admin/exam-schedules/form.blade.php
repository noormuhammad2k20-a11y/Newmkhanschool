{{-- resources/views/admin/exam-schedules/form.blade.php --}}
<div class="form-group">
    <label>Class</label>
    <select name="class_id" class="form-control" required>
        <option value="">-- Select Class --</option>
        @foreach($classes as $class)
            <option value="{{ $class->id }}" {{ old('class_id', $examSchedule->class_id ?? '') == $class->id ? 'selected' : '' }}>
                Class {{ $class->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Subject</label>
    <select name="subject_id" class="form-control" required>
        <option value="">-- Select Subject --</option>
        @foreach($subjects as $subject)
            <option value="{{ $subject->id }}" {{ old('subject_id', $examSchedule->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                {{ $subject->name }} ({{ $subject->code ?? '' }})
            </option>
        @endforeach
    </select>
</div>
