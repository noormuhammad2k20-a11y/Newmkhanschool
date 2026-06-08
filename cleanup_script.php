<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try { DB::statement('ALTER TABLE timetables DROP FOREIGN KEY fk_timetable_teacher'); } catch (\Exception $e) {}
try { DB::statement('ALTER TABLE timetables DROP FOREIGN KEY fk_timetable_subject'); } catch (\Exception $e) {}
try { DB::statement('ALTER TABLE timetables DROP FOREIGN KEY fk_timetable_section'); } catch (\Exception $e) {}
try { DB::statement('ALTER TABLE exam_schedules DROP FOREIGN KEY fk_exam_class'); } catch (\Exception $e) {}
try { DB::statement('ALTER TABLE exam_schedules DROP FOREIGN KEY fk_exam_subject'); } catch (\Exception $e) {}
try { DB::statement('ALTER TABLE exam_schedules DROP FOREIGN KEY fk_exam_year'); } catch (\Exception $e) {}
try { DB::statement('ALTER TABLE hostel_assignments DROP FOREIGN KEY fk_hostel_student'); } catch (\Exception $e) {}
try { DB::statement('ALTER TABLE hostel_assignments DROP FOREIGN KEY fk_hostel_room'); } catch (\Exception $e) {}
try { DB::statement('ALTER TABLE payroll DROP FOREIGN KEY fk_payroll_teacher'); } catch (\Exception $e) {}

Schema::dropIfExists('parent_students');
Schema::dropIfExists('student_leave_requests');
Schema::dropIfExists('assignment_submissions');
Schema::dropIfExists('notifications');
Schema::dropIfExists('fee_structures');
Schema::dropIfExists('report_cards');
Schema::dropIfExists('library_issues');
Schema::dropIfExists('audit_logs');

echo "Cleanup done\n";
