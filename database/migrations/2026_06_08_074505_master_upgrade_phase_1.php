<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            -- Step 1 & 2 skipped because table already dropped
            -- Step 3: Add missing columns to library_books
            ALTER TABLE library_books
              ADD COLUMN IF NOT EXISTS isbn VARCHAR(20) DEFAULT NULL AFTER author,
              ADD COLUMN IF NOT EXISTS publisher VARCHAR(100) DEFAULT NULL AFTER isbn,
              ADD COLUMN IF NOT EXISTS total_copies INT NOT NULL DEFAULT 1 AFTER publisher,
              ADD COLUMN IF NOT EXISTS available_copies INT NOT NULL DEFAULT 1 AFTER total_copies;

            -- Step 4 & 5 skipped because table already dropped

            -- Fix timetables: replace free-text teacher/subject with proper FK columns
            ALTER TABLE timetables
              ADD COLUMN IF NOT EXISTS teacher_id INT NULL AFTER teacher,
              ADD COLUMN IF NOT EXISTS subject_id_ref INT NULL AFTER subject,
              ADD COLUMN IF NOT EXISTS section_id_ref INT NULL AFTER section_id;

            ALTER TABLE timetables
              ADD CONSTRAINT fk_timetable_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL,
              ADD CONSTRAINT fk_timetable_subject FOREIGN KEY (subject_id_ref) REFERENCES subjects(id) ON DELETE SET NULL,
              ADD CONSTRAINT fk_timetable_section FOREIGN KEY (section_id_ref) REFERENCES sections(id) ON DELETE SET NULL;

            -- Fix exam_schedules: replace free-text class_name/subject with FK columns
            ALTER TABLE exam_schedules
              ADD COLUMN IF NOT EXISTS class_id INT NULL,
              ADD COLUMN IF NOT EXISTS subject_id INT NULL,
              ADD COLUMN IF NOT EXISTS academic_year_id INT NULL;

            ALTER TABLE exam_schedules
              ADD CONSTRAINT fk_exam_class FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL,
              ADD CONSTRAINT fk_exam_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL,
              ADD CONSTRAINT fk_exam_year FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE SET NULL;

            -- Fix hostel_assignments: student_id must be INT FK, not VARCHAR
            ALTER TABLE hostel_assignments MODIFY COLUMN student_id INT NULL;
            ALTER TABLE hostel_assignments
              ADD COLUMN IF NOT EXISTS room_id INT NULL,
              ADD CONSTRAINT fk_hostel_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
              ADD CONSTRAINT fk_hostel_room FOREIGN KEY (room_id) REFERENCES hostel_rooms(id) ON DELETE SET NULL;

            -- Fix payroll: add teacher FK, fix status ENUM
            ALTER TABLE payroll
              ADD COLUMN IF NOT EXISTS teacher_id INT NULL,
              ADD CONSTRAINT fk_payroll_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL;

            ALTER TABLE payroll
              MODIFY COLUMN status ENUM('Paid','Processing','Pending','Failed') DEFAULT 'Pending';

            -- Fix teacher_assignments: remove rows with NULL class_id OR NULL subject_id
            -- Each row must represent one specific teacher → class → subject mapping
            DELETE FROM teacher_assignments WHERE class_id IS NULL OR subject_id IS NULL;
            ALTER TABLE teacher_assignments MODIFY COLUMN class_id INT NOT NULL;
            ALTER TABLE teacher_assignments MODIFY COLUMN subject_id INT NOT NULL;

            -- Fix transport_students: a student should only be on ONE active route
            -- Add unique constraint
            -- Need to ensure no duplicates before adding unique constraint
            DELETE ts1 FROM transport_students ts1
            INNER JOIN transport_students ts2
            WHERE ts1.student_id = ts2.student_id AND ts1.id < ts2.id;
            
            -- Then add the constraint
            ALTER TABLE transport_students ADD UNIQUE KEY IF NOT EXISTS unique_student_route (student_id);

            -- Parent-student relationship
            CREATE TABLE IF NOT EXISTS parent_students (
              id INT AUTO_INCREMENT PRIMARY KEY,
              parent_user_id INT NOT NULL,
              student_id INT NOT NULL,
              relationship VARCHAR(50) NOT NULL DEFAULT 'Parent',
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              FOREIGN KEY (parent_user_id) REFERENCES users(id) ON DELETE CASCADE,
              FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
              UNIQUE KEY unique_parent_student (parent_user_id, student_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

            -- Student leave requests
            CREATE TABLE IF NOT EXISTS student_leave_requests (
              id INT AUTO_INCREMENT PRIMARY KEY,
              student_id INT NOT NULL,
              leave_type VARCHAR(100) NOT NULL,
              start_date DATE NOT NULL,
              end_date DATE NOT NULL,
              reason TEXT DEFAULT NULL,
              status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
              approved_by INT NULL,
              rejection_reason TEXT DEFAULT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
              FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

            -- Assignment submissions
            CREATE TABLE IF NOT EXISTS assignment_submissions (
              id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              assignment_id BIGINT UNSIGNED NOT NULL,
              student_id INT NOT NULL,
              file_path VARCHAR(255) DEFAULT NULL,
              notes TEXT DEFAULT NULL,
              submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              marks_obtained DECIMAL(5,2) DEFAULT NULL,
              teacher_feedback TEXT DEFAULT NULL,
              status ENUM('Submitted','Graded','Late','Pending') NOT NULL DEFAULT 'Pending',
              updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE CASCADE,
              FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
              UNIQUE KEY unique_submission (assignment_id, student_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            -- Notifications
            CREATE TABLE IF NOT EXISTS notifications (
              id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              user_id INT NOT NULL,
              type VARCHAR(100) NOT NULL,
              title VARCHAR(255) NOT NULL,
              body TEXT NOT NULL,
              is_read TINYINT(1) NOT NULL DEFAULT 0,
              action_url VARCHAR(255) DEFAULT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
              KEY idx_user_read (user_id, is_read)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            -- Fee structures (master fee plan per class per year)
            CREATE TABLE IF NOT EXISTS fee_structures (
              id INT AUTO_INCREMENT PRIMARY KEY,
              class_id INT NOT NULL,
              fee_type VARCHAR(100) NOT NULL,
              amount DECIMAL(10,2) NOT NULL,
              frequency ENUM('Monthly','Quarterly','Annually','One-Time') NOT NULL DEFAULT 'Monthly',
              academic_year_id INT NOT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
              FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

            -- Report cards
            CREATE TABLE IF NOT EXISTS report_cards (
              id INT AUTO_INCREMENT PRIMARY KEY,
              student_id INT NOT NULL,
              academic_year_id INT NOT NULL,
              exam_type_id INT NOT NULL,
              total_marks DECIMAL(7,2) DEFAULT NULL,
              obtained_marks DECIMAL(7,2) DEFAULT NULL,
              percentage DECIMAL(5,2) DEFAULT NULL,
              grade VARCHAR(5) DEFAULT NULL,
              rank INT DEFAULT NULL,
              remarks TEXT DEFAULT NULL,
              generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
              FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
              FOREIGN KEY (exam_type_id) REFERENCES exam_types(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

            -- Library issue/return records
            CREATE TABLE IF NOT EXISTS library_issues (
              id INT AUTO_INCREMENT PRIMARY KEY,
              book_id INT NOT NULL,
              student_id INT NOT NULL,
              issued_date DATE NOT NULL,
              due_date DATE NOT NULL,
              return_date DATE DEFAULT NULL,
              fine DECIMAL(7,2) NOT NULL DEFAULT 0.00,
              status ENUM('Issued','Returned','Overdue','Lost') NOT NULL DEFAULT 'Issued',
              issued_by INT DEFAULT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              FOREIGN KEY (book_id) REFERENCES library_books(id) ON DELETE CASCADE,
              FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
              FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

            -- Audit logs for sensitive actions
            CREATE TABLE IF NOT EXISTS audit_logs (
              id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              user_id INT NOT NULL,
              action VARCHAR(100) NOT NULL,
              model_type VARCHAR(100) DEFAULT NULL,
              model_id INT DEFAULT NULL,
              description TEXT DEFAULT NULL,
              ip_address VARCHAR(45) DEFAULT NULL,
              created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

            -- Clear and repopulate permissions
            TRUNCATE TABLE role_permissions;
            DELETE FROM permissions;

            INSERT INTO permissions (name, slug) VALUES
            ('View Dashboard',           'view_dashboard'),
            ('Manage Students',          'manage_students'),
            ('View Students',            'view_students'),
            ('Manage Teachers',          'manage_teachers'),
            ('View Teachers',            'view_teachers'),
            ('Mark Attendance',          'mark_attendance'),
            ('View Attendance',          'view_attendance'),
            ('View Own Attendance',      'view_own_attendance'),
            ('Enter Marks',              'enter_marks'),
            ('View Marks',               'view_marks'),
            ('View Own Marks',           'view_own_marks'),
            ('Manage Fees',              'manage_fees'),
            ('View Fees',                'view_fees'),
            ('View Own Fees',            'view_own_fees'),
            ('Manage Timetable',         'manage_timetable'),
            ('View Timetable',           'view_timetable'),
            ('Create Assignments',       'create_assignments'),
            ('View Assignments',         'view_assignments'),
            ('Submit Assignments',       'submit_assignments'),
            ('Manage Announcements',     'manage_announcements'),
            ('View Announcements',       'view_announcements'),
            ('Send Messages',            'send_messages'),
            ('Manage Library',           'manage_library'),
            ('View Library',             'view_library'),
            ('Manage Payroll',           'manage_payroll'),
            ('Manage Transport',         'manage_transport'),
            ('View Transport',           'view_transport'),
            ('View Health Records',      'view_health_records'),
            ('Manage Health Records',    'manage_health_records'),
            ('Manage Hostel',            'manage_hostel'),
            ('View Own Profile',         'view_own_profile'),
            ('Edit Own Profile',         'edit_own_profile'),
            ('Apply Leave',              'apply_leave'),
            ('Approve Leave',            'approve_leave'),
            ('View Reports',             'view_reports'),
            ('Generate Reports',         'generate_reports'),
            ('System Settings',          'system_settings'),
            ('View Own Children',        'view_own_children'),
            ('Manage Events',            'manage_events'),
            ('View Events',              'view_events'),
            ('Manage Exam Schedule',     'manage_exam_schedule'),
            ('View Exam Schedule',       'view_exam_schedule'),
            ('Download Report Card',     'download_report_card');

            -- Super Admin (id=1): ALL permissions
            INSERT INTO role_permissions (role_id, permission_id)
              SELECT 1, id FROM permissions;

            -- School Admin (id=2): all except system_settings
            INSERT INTO role_permissions (role_id, permission_id)
              SELECT 2, id FROM permissions WHERE slug != 'system_settings';

            -- Teacher (id=3)
            INSERT INTO role_permissions (role_id, permission_id)
              SELECT 3, id FROM permissions WHERE slug IN (
                'view_dashboard','view_students','mark_attendance','view_attendance',
                'enter_marks','view_marks','view_timetable','create_assignments',
                'view_assignments','manage_announcements','view_announcements',
                'send_messages','view_library','view_own_profile','edit_own_profile',
                'apply_leave','approve_leave','view_reports','view_health_records',
                'view_events','view_exam_schedule','manage_exam_schedule'
              );

            -- Student (id=4)
            INSERT INTO role_permissions (role_id, permission_id)
              SELECT 4, id FROM permissions WHERE slug IN (
                'view_dashboard','view_own_attendance','view_own_marks','view_own_fees',
                'view_timetable','view_assignments','submit_assignments','view_announcements',
                'send_messages','view_library','view_own_profile','edit_own_profile',
                'apply_leave','view_transport','view_health_records','view_events',
                'view_exam_schedule','download_report_card'
              );

            -- Parent (id=5)
            INSERT INTO role_permissions (role_id, permission_id)
              SELECT 5, id FROM permissions WHERE slug IN (
                'view_dashboard','view_own_children','view_own_attendance','view_own_marks',
                'view_own_fees','view_timetable','view_announcements','send_messages',
                'view_transport','view_events'
              );

            -- Fix Demo/Test Data Issues
            -- Remove Lorem Ipsum test data from events
            DELETE FROM events WHERE description LIKE '%Lorem%' OR description LIKE '%quibusdam%' OR description LIKE '%Reprehenderit%';

            -- Remove fake exam schedule entries (date from 1974 is test data)
            DELETE FROM exam_schedules WHERE exam_date < '2000-01-01';

            -- Fix transport route with empty route_code
            UPDATE transport_routes SET route_code = CONCAT('R-0', id) WHERE route_code = '' OR route_code IS NULL;

            -- Assign school_id to users who are missing it (for roles 2,3,4,5)
            UPDATE users SET school_id = 1 WHERE school_id IS NULL AND role_id IN (2,3,4,5);
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
