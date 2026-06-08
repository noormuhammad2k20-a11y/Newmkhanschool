{{-- resources/views/admin/timetables/form.blade.php --}}
<select name="teacher_id" class="form-control">
    <option value="">-- Select Teacher --</option>
    @foreach($teachers as $teacher)
        <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
    @endforeach
</select>

<select name="subject_id_ref" class="form-control">
    <option value="">-- Select Subject --</option>
    @foreach($subjects as $subject)
        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
    @endforeach
</select>
