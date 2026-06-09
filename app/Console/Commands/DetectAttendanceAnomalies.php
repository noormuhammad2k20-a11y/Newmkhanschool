<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudentAttendance;
use App\Models\AttendanceAnomaly;
use App\Models\Student;
use Carbon\Carbon;

class DetectAttendanceAnomalies extends Command
{
    protected $signature = 'ai:detect-attendance-anomalies';
    protected $description = 'Analyze attendance data for anomalies';

    public function handle()
    {
        $this->info('Starting attendance anomaly detection...');
        
        $students = Student::all();
        
        foreach($students as $student) {
            // Check for 3 absences in the last 5 days
            $recentAbsences = StudentAttendance::where('student_id', $student->id)
                ->where('status', 'absent')
                ->where('date', '>=', now()->subDays(5)->toDateString())
                ->count();
                
            if ($recentAbsences >= 3) {
                // Check if an unresolved anomaly already exists
                $exists = AttendanceAnomaly::where('student_id', $student->id)
                    ->where('anomaly_type', 'consecutive_absent')
                    ->where('resolved', false)
                    ->exists();
                    
                if (!$exists) {
                    AttendanceAnomaly::create([
                        'student_id' => $student->id,
                        'anomaly_type' => 'consecutive_absent',
                        'description' => 'Student has been absent for 3 or more days in the last 5 days.',
                        'severity' => 'high',
                        'school_id' => $student->school_id ?? 1
                    ]);
                }
            }
        }
        
        $this->info('Anomalies detection complete.');
    }
}
