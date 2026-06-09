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
-- Payment transactions log
CREATE TABLE IF NOT EXISTS fee_payment_transactions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  fee_id INT NOT NULL,
  student_id INT NOT NULL,
  gateway ENUM('JazzCash','EasyPaisa','Cash','Bank') NOT NULL DEFAULT 'Cash',
  transaction_ref VARCHAR(100) DEFAULT NULL,
  amount DECIMAL(10,2) NOT NULL,
  gateway_response TEXT DEFAULT NULL,
  status ENUM('Pending','Success','Failed','Refunded') NOT NULL DEFAULT 'Pending',
  paid_at TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (fee_id) REFERENCES fees(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  INDEX idx_transaction_ref (transaction_ref),
  INDEX idx_student_status (student_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payment receipts (auto-generated after success)
CREATE TABLE IF NOT EXISTS fee_receipts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  receipt_no VARCHAR(50) NOT NULL UNIQUE,
  transaction_id BIGINT UNSIGNED NOT NULL,
  student_id INT NOT NULL,
  fee_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  pdf_path VARCHAR(255) DEFAULT NULL,
  FOREIGN KEY (transaction_id) REFERENCES fee_payment_transactions(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (fee_id) REFERENCES fees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Promotion history log
CREATE TABLE IF NOT EXISTS student_promotions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  academic_year_id INT NOT NULL,
  from_class_id INT NOT NULL,
  from_section_id INT NOT NULL,
  to_class_id INT NULL,
  to_section_id INT NULL,
  promotion_type ENUM('Promoted','Repeated','Graduated','Withdrawn') NOT NULL DEFAULT 'Promoted',
  promoted_by INT NOT NULL,
  remarks TEXT DEFAULT NULL,
  promoted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
  FOREIGN KEY (from_class_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY (promoted_by) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_student_year (student_id, academic_year_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Promotion rules per class (passing criteria)
CREATE TABLE IF NOT EXISTS promotion_rules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  from_class_id INT NOT NULL,
  to_class_id INT NOT NULL,
  min_percentage DECIMAL(5,2) NOT NULL DEFAULT 40.00,
  min_attendance_pct DECIMAL(5,2) NOT NULL DEFAULT 75.00,
  academic_year_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (from_class_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY (to_class_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
  UNIQUE KEY unique_rule (from_class_id, academic_year_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Schools/Branches table (if not already multi-school)
CREATE TABLE IF NOT EXISTS school_branches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  parent_school_id INT NULL,
  name VARCHAR(200) NOT NULL,
  code VARCHAR(20) NOT NULL UNIQUE,
  address TEXT DEFAULT NULL,
  city VARCHAR(100) DEFAULT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  email VARCHAR(150) DEFAULT NULL,
  principal_name VARCHAR(150) DEFAULT NULL,
  logo VARCHAR(255) DEFAULT NULL,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_parent (parent_school_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Branch admin assignments
CREATE TABLE IF NOT EXISTS branch_admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  school_id INT NOT NULL,
  user_id INT NOT NULL,
  assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (school_id) REFERENCES school_branches(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_branch_admin (school_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Exam papers (master)
CREATE TABLE IF NOT EXISTS online_exams (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT DEFAULT NULL,
  subject_id INT NOT NULL,
  class_id INT NOT NULL,
  academic_year_id INT NOT NULL,
  teacher_id INT NOT NULL,
  exam_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  duration_minutes INT NOT NULL DEFAULT 60,
  total_marks INT NOT NULL DEFAULT 100,
  passing_marks INT NOT NULL DEFAULT 40,
  instructions TEXT DEFAULT NULL,
  status ENUM('Draft','Published','Active','Closed') NOT NULL DEFAULT 'Draft',
  shuffle_questions TINYINT(1) NOT NULL DEFAULT 1,
  show_result_immediately TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
  FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
  FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Questions bank
CREATE TABLE IF NOT EXISTS exam_questions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  exam_id BIGINT UNSIGNED NOT NULL,
  question_text TEXT NOT NULL,
  question_type ENUM('MCQ','True/False','Short') NOT NULL DEFAULT 'MCQ',
  option_a VARCHAR(500) DEFAULT NULL,
  option_b VARCHAR(500) DEFAULT NULL,
  option_c VARCHAR(500) DEFAULT NULL,
  option_d VARCHAR(500) DEFAULT NULL,
  correct_answer VARCHAR(10) DEFAULT NULL,
  marks INT NOT NULL DEFAULT 1,
  order_no INT NOT NULL DEFAULT 0,
  FOREIGN KEY (exam_id) REFERENCES online_exams(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Student exam attempts
CREATE TABLE IF NOT EXISTS exam_attempts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  exam_id BIGINT UNSIGNED NOT NULL,
  student_id INT NOT NULL,
  started_at TIMESTAMP NULL DEFAULT NULL,
  submitted_at TIMESTAMP NULL DEFAULT NULL,
  total_marks INT DEFAULT NULL,
  obtained_marks INT DEFAULT NULL,
  percentage DECIMAL(5,2) DEFAULT NULL,
  status ENUM('Not Started','In Progress','Submitted','Evaluated') NOT NULL DEFAULT 'Not Started',
  ip_address VARCHAR(45) DEFAULT NULL,
  FOREIGN KEY (exam_id) REFERENCES online_exams(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  UNIQUE KEY unique_attempt (exam_id, student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Student answers
CREATE TABLE IF NOT EXISTS exam_answers (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  attempt_id BIGINT UNSIGNED NOT NULL,
  question_id BIGINT UNSIGNED NOT NULL,
  student_answer VARCHAR(1000) DEFAULT NULL,
  is_correct TINYINT(1) DEFAULT NULL,
  marks_awarded INT DEFAULT NULL,
  FOREIGN KEY (attempt_id) REFERENCES exam_attempts(id) ON DELETE CASCADE,
  FOREIGN KEY (question_id) REFERENCES exam_questions(id) ON DELETE CASCADE,
  UNIQUE KEY unique_answer (attempt_id, question_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Document templates (TC, Character Certificate, Bonafide, etc.)
CREATE TABLE IF NOT EXISTS document_templates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE,
  content LONGTEXT NOT NULL,
  variables TEXT DEFAULT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Issued documents log
CREATE TABLE IF NOT EXISTS issued_documents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  template_id INT NOT NULL,
  document_no VARCHAR(50) NOT NULL UNIQUE,
  issued_by INT NOT NULL,
  purpose TEXT DEFAULT NULL,
  pdf_path VARCHAR(255) DEFAULT NULL,
  issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (template_id) REFERENCES document_templates(id) ON DELETE CASCADE,
  FOREIGN KEY (issued_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Teacher leave requests
CREATE TABLE IF NOT EXISTS teacher_leave_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  teacher_id INT NOT NULL,
  leave_type ENUM('Sick','Casual','Annual','Emergency','Maternity','Other') NOT NULL DEFAULT 'Casual',
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  total_days INT NOT NULL DEFAULT 1,
  reason TEXT DEFAULT NULL,
  status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  approved_by INT NULL,
  rejection_reason TEXT DEFAULT NULL,
  substitute_assigned TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
  FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Substitute teacher assignments
CREATE TABLE IF NOT EXISTS substitute_assignments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  leave_request_id INT NOT NULL,
  original_teacher_id INT NOT NULL,
  substitute_teacher_id INT NOT NULL,
  class_id INT NOT NULL,
  subject_id INT NOT NULL,
  date DATE NOT NULL,
  period_time VARCHAR(50) DEFAULT NULL,
  status ENUM('Assigned','Completed','Cancelled') NOT NULL DEFAULT 'Assigned',
  notes TEXT DEFAULT NULL,
  assigned_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (leave_request_id) REFERENCES teacher_leave_requests(id) ON DELETE CASCADE,
  FOREIGN KEY (original_teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
  FOREIGN KEY (substitute_teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
  FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
  FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
  FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Teacher leave balance per year
CREATE TABLE IF NOT EXISTS teacher_leave_balances (
  id INT AUTO_INCREMENT PRIMARY KEY,
  teacher_id INT NOT NULL,
  academic_year_id INT NOT NULL,
  casual_total INT NOT NULL DEFAULT 12,
  casual_used INT NOT NULL DEFAULT 0,
  sick_total INT NOT NULL DEFAULT 10,
  sick_used INT NOT NULL DEFAULT 0,
  annual_total INT NOT NULL DEFAULT 15,
  annual_used INT NOT NULL DEFAULT 0,
  FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
  FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
  UNIQUE KEY unique_balance (teacher_id, academic_year_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO document_templates (name, slug, content, variables) VALUES
('Transfer Certificate', 'transfer-certificate',
'<h2 style=\"text-align:center\">{{school_name}}</h2>
<h3 style=\"text-align:center\">TRANSFER CERTIFICATE</h3>
<p>This is to certify that <strong>{{student_name}}</strong> Son/Daughter of <strong>{{father_name}}</strong>,
bearing Admission No. <strong>{{admission_no}}</strong>, was a student of this institution from
<strong>{{admission_date}}</strong> to <strong>{{leaving_date}}</strong>.</p>
<p>He/She was studying in Class <strong>{{class_name}}</strong> at the time of leaving.</p>
<p>His/Her character and conduct was <strong>Good</strong> during his/her stay in this institution.</p>
<p>No dues are pending against him/her.</p>
<p style=\"margin-top:60px\">Principal Signature: _______________________</p>',
'school_name,student_name,father_name,admission_no,admission_date,leaving_date,class_name'),

('Character Certificate', 'character-certificate',
'<h2 style=\"text-align:center\">{{school_name}}</h2>
<h3 style=\"text-align:center\">CHARACTER CERTIFICATE</h3>
<p>This is to certify that <strong>{{student_name}}</strong> Son/Daughter of <strong>{{father_name}}</strong>,
bearing Admission No. <strong>{{admission_no}}</strong>, studied in Class <strong>{{class_name}}</strong>
during the academic session <strong>{{academic_year}}</strong>.</p>
<p>His/Her character and conduct was <strong>Excellent</strong> throughout his/her academic career.
He/She was an honest, hardworking, and disciplined student.</p>
<p>We wish him/her all the best in his/her future endeavors.</p>
<p style=\"margin-top:60px\">Principal Signature: _______________________</p>',
'school_name,student_name,father_name,admission_no,class_name,academic_year'),

('Bonafide Certificate', 'bonafide-certificate',
'<h2 style=\"text-align:center\">{{school_name}}</h2>
<h3 style=\"text-align:center\">BONAFIDE CERTIFICATE</h3>
<p>This is to certify that <strong>{{student_name}}</strong> Son/Daughter of <strong>{{father_name}}</strong>,
Resident of <strong>{{address}}</strong>, bearing Admission No. <strong>{{admission_no}}</strong>,
is currently a bonafide student of this institution studying in Class <strong>{{class_name}}</strong>,
Session <strong>{{academic_year}}</strong>.</p>
<p>This certificate is issued for the purpose of <strong>{{purpose}}</strong>.</p>
<p style=\"margin-top:60px\">Principal Signature: _______________________</p>',
'school_name,student_name,father_name,address,admission_no,class_name,academic_year,purpose');
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
DROP TABLE IF EXISTS teacher_leave_balances;
DROP TABLE IF EXISTS substitute_assignments;
DROP TABLE IF EXISTS teacher_leave_requests;
DROP TABLE IF EXISTS issued_documents;
DROP TABLE IF EXISTS document_templates;
DROP TABLE IF EXISTS exam_answers;
DROP TABLE IF EXISTS exam_attempts;
DROP TABLE IF EXISTS exam_questions;
DROP TABLE IF EXISTS online_exams;
DROP TABLE IF EXISTS branch_admins;
DROP TABLE IF EXISTS school_branches;
DROP TABLE IF EXISTS promotion_rules;
DROP TABLE IF EXISTS student_promotions;
DROP TABLE IF EXISTS fee_receipts;
DROP TABLE IF EXISTS fee_payment_transactions;
        ");
    }
};
