# NewMkhanSchool — Full System Audit & Fix Prompt
**Project:** `noormuhammad2k20-a11y/Newmkhanschool`  
**Stack:** Laravel · PHP 8.2 · MariaDB 10.4 · Blade · dompdf (DejaVu Sans) · Chart.js · Gemini AI  
**Constraint:** ⚠️ Existing theme, CSS, colors, fonts, layout — **DO NOT CHANGE**. Only backend logic & functionality.

---

## 🔑 Seed Data Reference (All Passwords: `password`)

### Users & Roles
| Role | ID | Email | Notes |
|------|----|-------|-------|
| Super Admin | 1 | `admin@school.com` | role_id=1, school_id=NULL |
| **School Admin** | ❌ | **MISSING** | **No user with role_id=2 exists** |
| Teacher (Ali Khan) | 141 | `ali.khan@school.com` | teacher_id=1, EMP0001 |
| Teacher (Fatima Ahmed) | 142 | `fatima.ahmed@school.com` | teacher_id=2, EMP0002 |
| Teacher (Usman Tariq) | 143 | `usman.tariq@school.com` | teacher_id=3, EMP0003 |
| Teacher (Aisha Syed) | 144 | `aisha.syed@school.com` | teacher_id=4, EMP0004 |
| Teacher (Bilal Malik) | 145 | `bilal.malik@school.com` | teacher_id=5, EMP0005 |
| Student (Omar) | 146 | `student0@school.com` | student_id=1, ADM0001, Class 1 |
| Parent of Omar | 147 | `parent0@school.com` | parent_user_id=147 → student_id=1 |
| Student (Hasan) | 148 | `student1@school.com` | student_id=2, ADM0002, Class 1 |
| Parent of Hasan | 149 | `parent1@school.com` | parent_user_id=149 → student_id=2 |
| Student (Zainab) | 158 | `student6@school.com` | student_id=7, ADM0007, Class 2 |
| Parent of Zainab | 159 | `parent6@school.com` | parent_user_id=159 → student_id=7 |
| Student (Sana) | 170 | `student12@school.com` | student_id=13, ADM0013, Class 3 |
| Parent of Sana | 171 | `parent12@school.com` | parent_user_id=171 → student_id=13 |
| Accountant | 182 | `accountant@school.com` | role_id=6, school_id=1 |

### Classes, Sections & Subjects
| Class | Section | Students | Subjects (IDs) |
|-------|---------|----------|----------------|
| Class 1 (id=1) | Section A (id=1) | student_id 1–6 (ADM0001–ADM0006) | English(1), Maths(2), Science(3), Urdu(4), Computer(5), Sindhi(16), Chemistry(17), Biology(18) |
| Class 2 (id=2) | Section B (id=2) | student_id 7–12 (ADM0007–ADM0012) | English(6), Maths(7), Science(8), Urdu(9), Computer(10) |
| Class 3 (id=3) | Section C (id=3) | student_id 13–18 (ADM0013–ADM0018) | English(11), Maths(12), Science(13), Urdu(14), Computer(15) |

### Teacher Assignments
| Teacher | Assigned Classes & Subjects |
|---------|-----------------------------|
| Ali Khan (teacher_id=1) | Class 1→English(1), Class 2→English(6), Class 3→English(11) |
| Fatima Ahmed (teacher_id=2) | Class 1→Maths(2), Class 2→Maths(7), Class 3→Maths(12) |
| Usman Tariq (teacher_id=3) | Class 1→Science(3), Class 2→Science(8), Class 3→Science(13) |
| Aisha Syed (teacher_id=4) | Class 1→Urdu(4,9,14), Class 2→Urdu(4,9,14), Class 3→Urdu(4,9,14) |
| Bilal Malik (teacher_id=5) | Class 1→Computer(5), Class 2→Computer(10), Class 3→Computer(15) |

### Academic Year
- `academic_year_id=1`, year=`2025-2026`, `is_active=1`

---

## 🚨 Pre-Identified Bugs From DB Analysis

Before running the full audit, these critical issues are already confirmed from the database dump — fix them as part of the audit:

| # | Table | Issue | Impact |
|---|-------|-------|--------|
| B1 | `users` | **No School Admin user exists** (role_id=2 has zero rows) | School Admin portal completely inaccessible |
| B2 | `report_cards` | **Table is completely empty** — no report cards generated for any student | Report card PDF broken for all portals |
| B3 | `marks` | **All `exam_schedule_id` = NULL** in every marks row (90+ records) | FK link to exam_schedules broken |
| B4 | `fees` | fee_id=7: status=`'Paid'` but paid_amount=`0.00` (also fee_id=11: paid_amount=`8000` for a `4000` fee) | Fee dashboard shows wrong data |
| B5 | `teacher_leave_requests` | Record id=1 has dates `1988-12-26` to `1992-12-12` = 1448 days. No date validation | Leave system accepts invalid dates |
| B6 | `issued_documents` | `uuid` = NULL in ALL 9 issued documents | QR code generation broken |
| B7 | `teacher_module_access` | Only teacher_id=3 (Usman) has module access. Teachers 1,2,4,5 have NO module entries | Teachers may be blocked from their own modules |
| B8 | `payrolls` | Only 1 payroll record (Aisha Syed, Pending). Teachers 1,2,3,5 have zero payroll records | Payroll portal shows incomplete/empty data |
| B9 | `teachers` | All 5 teachers have `qualification=NULL`, `specialization=NULL` | Teacher profiles incomplete |
| B10 | `students` | All 18 students have `photo=NULL`, `exam_roll=NULL`, `address=NULL` | ID cards will have blank photo/roll fields |
| B11 | `ledger_entries` | Table is empty | Accountant ledger shows nothing |
| B12 | `expenses` | Table is empty | Expense tracking shows nothing |
| B13 | `bank_accounts` | Table is empty | Bank account management broken |

---

## 📋 PHASE 1 — Database Integrity & Seed Fix

### 1.1 Create Missing School Admin User
```sql
-- Create School Admin user (role_id=2)
INSERT INTO `users` (`name`, `email`, `password_hash`, `role_id`, `school_id`, `status`, `created_at`)
VALUES ('School Administrator', 'schooladmin@school.com', '$2y$12$WQ.WD5ZaXNQREkCLuhcEhOlAjU6gagveAzoBRy12qMObWqxQcnwvi', 2, 1, 'active', NOW());
```
**Verify:** Login with `schooladmin@school.com` / `password` → should reach School Admin dashboard.

### 1.2 Fix marks.exam_schedule_id NULL Issue
- Check if `exam_schedules` has data linked to these marks
- If `exam_schedule_id` FK is required, either:
  - Make it nullable in migration (preferred for existing data), OR
  - Link all NULL marks to the correct exam_schedule based on `class_id` + `subject_id` + `academic_year_id`
- **Verify the `marks` model fillable includes `exam_schedule_id`**

### 1.3 Fix Fee Data Inconsistencies
```sql
-- Fix fee_id=7: status says Paid but paid_amount=0
UPDATE `fees` SET `paid_amount` = 4000.00 WHERE `id` = 7 AND `status` = 'Paid' AND `paid_amount` = 0.00;

-- Fix fee_id=25: paid_amount=4000 for 2000 fee — check if overpayment or data error
-- Fix fee_id=9,11: paid_amount double the amount — investigate
SELECT id, amount, paid_amount, status FROM fees WHERE paid_amount > amount;
SELECT id, amount, paid_amount, status FROM fees WHERE status = 'Paid' AND paid_amount = 0;
```

### 1.4 Populate Empty Financial Tables
Insert seed data into:
- `ledger_entries` — 5–10 sample entries (fees collected, expenses, salary payments)
- `expenses` — 3–5 seed expenses (utilities, stationery, maintenance)
- `bank_accounts` — 1 seed bank account for school_id=1

### 1.5 Fix issued_documents UUID
```sql
-- Generate UUIDs for all issued documents that have NULL uuid
UPDATE `issued_documents` SET `uuid` = UUID() WHERE `uuid` IS NULL;
```
**Also check:** Does the document issuance controller properly set `uuid` on new records?

### 1.6 Fix Teacher Module Access
```sql
-- Give all teachers default module access
INSERT INTO `teacher_module_access` (`teacher_id`, `module_name`, `created_at`, `updated_at`)
SELECT t.id, m.module_name, NOW(), NOW()
FROM `teachers` t
CROSS JOIN (
  SELECT 'dashboard' AS module_name UNION SELECT 'attendance' UNION SELECT 'classes'
  UNION SELECT 'marks' UNION SELECT 'assignments' UNION SELECT 'timetable'
  UNION SELECT 'leave' UNION SELECT 'digital_notes' UNION SELECT 'quizzes'
) m
WHERE NOT EXISTS (
  SELECT 1 FROM `teacher_module_access` tma
  WHERE tma.teacher_id = t.id AND tma.module_name = m.module_name
);
```

### 1.7 Fix Teacher Leave Date Validation
- Find validation in `TeacherLeaveRequest` store/update — ensure `end_date >= start_date` and both dates are ≥ today or recent
- Fix the bogus record: update teacher_leave_request id=1 dates to sensible values or delete it

### 1.8 Fill Teacher & Student Incomplete Data
```sql
-- Update teacher qualifications/specialization
UPDATE `teachers` SET `qualification`='B.Ed', `specialization`='General Education', `experience`=5 WHERE `id`=1;
UPDATE `teachers` SET `qualification`='M.A', `specialization`='Mathematics', `experience`=8 WHERE `id`=2;
UPDATE `teachers` SET `qualification`='B.Sc', `specialization`='Science', `experience`=3 WHERE `id`=3;
UPDATE `teachers` SET `qualification`='M.A', `specialization`='Urdu Literature', `experience`=10 WHERE `id`=4;
UPDATE `teachers` SET `qualification`='B.CS', `specialization`='Computer Science', `experience`=6 WHERE `id`=5;

-- Update student exam rolls and addresses
UPDATE `students` SET `exam_roll`=CONCAT('2026-', LPAD(id, 4, '0')), `address`='Hyderabad, Sindh' WHERE `school_id`=1;
```

### 1.9 Generate Report Cards from Existing Marks
```sql
-- Generate report_cards from existing marks data
INSERT INTO `report_cards` (`student_id`, `academic_year_id`, `exam_type_id`, `total_obtained`, `total_max`, `percentage`, `grade`, `remarks`, `created_at`, `updated_at`)
SELECT 
  m.student_id,
  m.academic_year_id,
  m.exam_type_id,
  SUM(m.marks_obtained) AS total_obtained,
  SUM(m.total_marks) AS total_max,
  ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) AS percentage,
  CASE 
    WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 90 THEN 'A+'
    WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 80 THEN 'A'
    WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 70 THEN 'B'
    WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 60 THEN 'C'
    WHEN ROUND((SUM(m.marks_obtained)/SUM(m.total_marks))*100, 2) >= 50 THEN 'D'
    ELSE 'F'
  END AS grade,
  'Auto-generated from marks' AS remarks,
  NOW(), NOW()
FROM `marks` m
GROUP BY m.student_id, m.academic_year_id, m.exam_type_id
ON DUPLICATE KEY UPDATE
  total_obtained=VALUES(total_obtained),
  total_max=VALUES(total_max),
  percentage=VALUES(percentage),
  grade=VALUES(grade),
  updated_at=NOW();
```

### 1.10 Add Missing Payroll Records
```sql
INSERT INTO `payrolls` (`teacher_id`, `emp_id`, `name`, `role`, `basic_pay`, `allowances`, `deductions`, `net_salary`, `status`, `month_year`, `created_at`, `updated_at`) VALUES
(1, 'EMP0001', 'Ali Khan', 'Teacher', 60000.00, 7000.00, 3000.00, 64000.00, 'Paid', '2026-06', NOW(), NOW()),
(2, 'EMP0002', 'Fatima Ahmed', 'Teacher', 65000.00, 7000.00, 4000.00, 68000.00, 'Paid', '2026-06', NOW(), NOW()),
(3, 'EMP0003', 'Usman Tariq', 'Teacher', 58000.00, 6000.00, 3000.00, 61000.00, 'Paid', '2026-06', NOW(), NOW()),
(5, 'EMP0005', 'Bilal Malik', 'Teacher', 62000.00, 7000.00, 3500.00, 65500.00, 'Paid', '2026-06', NOW(), NOW());
```

---

## 📋 PHASE 2 — Portal-by-Portal Deep Audit

---

### 🔵 PORTAL 1: Admin / Super Admin Portal

**Login:** `admin@school.com` / `password`

#### 2.1 Dashboard
- [ ] Dashboard loads without error
- [ ] Stats cards show correct counts: total students (18), teachers (5), classes (3)
- [ ] Total fees collected shows correct sum from `fees` where status=`Paid`
- [ ] Recent activity / audit log visible
- [ ] Chart.js charts render (attendance summary, fee collection graph)

#### 2.2 Student Management
- [ ] Student list shows all 18 students with correct class/section
- [ ] Search/filter by class works
- [ ] View individual student profile: Omar (ADM0001), Hasan (ADM0002)
- [ ] Student edit form saves correctly
- [ ] Student photo upload works (currently all NULL)
- [ ] Soft delete / restore student works (`deleted_at` column)
- [ ] Student promotion module works using `promotion_rules` table

#### 2.3 Teacher Management
- [ ] Teacher list shows all 5 teachers
- [ ] Teacher profile page shows qualification, specialization (now filled)
- [ ] Assign subjects to teacher → `teacher_assignments` table updated
- [ ] Teacher module access control: toggle modules ON/OFF per teacher → `teacher_module_access` updated
- [ ] Teacher soft delete works

#### 2.4 Class & Subject Management
- [ ] 3 classes visible: Class 1, Class 2, Class 3
- [ ] 18 subjects visible (including Sindhi/16, Chemistry/17, Biology/18 added later)
- [ ] Add new class → reflects in student enrollment dropdowns
- [ ] Add new subject → reflects in marks entry, teacher assignment

#### 2.5 Attendance Management
- [ ] View student attendance for all students
- [ ] Date-wise attendance report works
- [ ] Student attendance chart (Chart.js) works per student
- [ ] Teacher attendance records viewable (only teacher_id=1 marked P on 2026-06-12)

#### 2.6 Leave Management (Admin view)
- [ ] View all student leave requests: 3 pending requests (student 7 × 2, student 2 × 1)
- [ ] Approve/Reject student leave request → status updates in DB
- [ ] View all teacher leave requests: 1 record (Aisha Syed, Approved)
- [ ] Teacher leave approval/rejection works
- [ ] Approved leave correctly reflects in attendance (no absent marking during approved leave)

#### 2.7 Exam & Marks Management
- [ ] Exam schedules list visible (check if class_id/subject_id linked correctly)
- [ ] Exam types: Annual, Mid-Term, etc.
- [ ] Admin can view all marks across all students/subjects
- [ ] Marks entry (if admin has permission) works and saves
- [ ] Marks edit/update reflects correct percentage/grade recalculation

#### 2.8 Fee Management (Admin view)
- [ ] Fee list shows all 41 fee records with correct status
- [ ] Fee filter by: Paid/Pending/Overdue works
- [ ] Fee filter by class works
- [ ] Paid vs Unpaid summary count is correct
- [ ] Create new fee category works → `fee_categories` updated
- [ ] Fee structure per class setup works → `fee_structures` updated
- [ ] Manual fee collection (cash payment entry) works
- [ ] Fee receipt generation works → `fee_receipts` table updated
- [ ] Challan generation PDF works

#### 2.9 Document Generation (Admin)
- [ ] Transfer Certificate (TC) generation for student ADM0001 → PDF downloads
- [ ] Character Certificate generation for ADM0003 → PDF downloads
- [ ] `issued_documents` record created with proper `uuid` set (not NULL)
- [ ] QR code generated and embedded in PDF
- [ ] Document template list shows both templates (id=1 TC, id=2 CC)

#### 2.10 ID Card Generation
- [ ] ID card generates for student ADM0001 (Omar) → PDF/HTML output
- [ ] ID card includes: name, class, admission no, photo (or placeholder), school name, QR
- [ ] Batch ID card generation for all students in a class works

#### 2.11 Report Card (Admin)
- [ ] Report card generates for student ADM0001 (Omar) after Phase 1 SQL runs
- [ ] Report card PDF via dompdf works (DejaVu Sans font)
- [ ] Report card shows: all subjects, marks_obtained/total_marks, percentage, grade, rank
- [ ] Batch report card generation for a class works

#### 2.12 Timetable
- [ ] Timetable version list shows published version
- [ ] Class-wise timetable displays correctly
- [ ] Create/Edit timetable entry works

#### 2.13 Events & Announcements
- [ ] Events list shows seeded events
- [ ] Announcement creation works with role_visibility targeting
- [ ] Announcements visible to correct roles only

#### 2.14 Library
- [ ] Library books list shows seeded books
- [ ] Issue book to student works → `library_issues` updated
- [ ] Return book works → available_copies incremented

#### 2.15 Inventory
- [ ] Inventory list shows all seeded assets
- [ ] Low stock alert (min_stock_alert) triggers correctly
- [ ] Inventory purchase record adds to quantity

#### 2.16 Transport
- [ ] Route list shows seeded routes
- [ ] Assign student to transport route works → `transport_students` updated

#### 2.17 Hostel
- [ ] Hostel room list shows seeded rooms
- [ ] Assign student to hostel room works → `hostel_assignments` updated

#### 2.18 Digital Learning System (Admin)
- [ ] Digital notes list (teacher-uploaded) visible to admin
- [ ] Quiz list visible
- [ ] Quiz results viewable

---

### 🟢 PORTAL 2: Teacher Portal

**Login options:** `ali.khan@school.com` / `password` (teacher_id=1, Class 1 English)  
Also test: `usman.tariq@school.com` / `password` (teacher_id=3, only one with module_access)

#### 2.19 Teacher Dashboard
- [ ] Teacher dashboard loads
- [ ] Shows assigned classes: Class 1 (Ali Khan → English), Class 2 → English, Class 3 → English
- [ ] Shows today's schedule from timetable
- [ ] Pending marks count, assignment submissions count visible

#### 2.20 Module Access Control
- [ ] `teacher_module_access` gate: if teacher has no module entry, are they blocked or allowed?
- [ ] After Phase 1 fix (all teachers get module access), verify Ali Khan can access all modules
- [ ] Admin can toggle module access per teacher → immediately affects teacher session

#### 2.21 Attendance Marking (Teacher)
- [ ] Teacher sees class list for assigned classes
- [ ] Bulk attendance marking for Class 1 students (IDs 1–6) works
- [ ] Attendance saves to `student_attendances` with `marked_by=teacher user_id`
- [ ] Re-marking same date shows "already marked" warning or allows update
- [ ] Teacher can view attendance history by date range

#### 2.22 Marks Entry
- [ ] Marks entry form loads for Ali Khan → Class 1 → English (subject_id=1)
- [ ] Student list (6 students: Omar, Hasan, Muhammad, Hamza, Khalid, Zaid) loads
- [ ] Enter marks for each student → saves to `marks` table
- [ ] Fields saved correctly: marks_obtained, total_marks, percentage (auto-calc), grade (auto-calc), gpa, is_pass, remarks
- [ ] Marks update (edit existing marks) works without creating duplicates
- [ ] **CRITICAL:** Does marks entry check for duplicate (student_id + subject_id + exam_type_id + academic_year_id)?
- [ ] Grade auto-calculation logic: A(≥80), B(≥70), C(≥60), D(≥50), F(<50) — verify matches seed data
- [ ] After saving marks, verify `report_cards` regenerates/updates for that student

#### 2.23 Assignments
- [ ] Assignment list shows Ali Khan's created assignments (seeded in assignments table)
- [ ] Create new assignment works → `assignments` table updated
- [ ] View submissions list per assignment
- [ ] Grade a submission → `assignment_submissions.marks_obtained` + `teacher_feedback` updated
- [ ] AI grading trigger: does "AI Grade" button work? → calls Gemini API → saves to `ai_grading_results`
- [ ] AI grading result (suggested_score, feedback, rubric_breakdown) shows in UI

#### 2.24 Digital Notes (Teacher Upload)
- [ ] Teacher can upload notes (PDF/video) for their class/subject
- [ ] Note saves to `digital_notes` with correct `class_id`, `subject_id`, `school_id`
- [ ] File upload stored correctly in storage
- [ ] External URL type note saves without file

#### 2.25 Quizzes (Teacher)
- [ ] Create quiz works → `quizzes` table updated
- [ ] Add questions (MCQ, True/False) → `quiz_questions` table updated
- [ ] Set quiz active/inactive
- [ ] View quiz attempt results from students → `quiz_attempts` + `quiz_answers`

#### 2.26 Leave Request (Teacher)
- [ ] Teacher can submit leave request → `teacher_leave_requests` table updated
- [ ] Leave type dropdown works (Casual, Medical, Emergency)
- [ ] Date validation: end_date must be ≥ start_date (fix the 1988 bug)
- [ ] Total days auto-calculated
- [ ] Pending leave request shows in teacher dashboard
- [ ] After Admin approves → status changes to 'Approved', visible to teacher

#### 2.27 Timetable (Teacher View)
- [ ] Teacher sees their own weekly timetable from `timetables` table
- [ ] Day-wise schedule is correct

#### 2.28 Marksheet Print (Teacher)
- [ ] Teacher can generate class marksheet PDF
- [ ] Marksheet shows all students in Class 1 with all subjects + marks
- [ ] dompdf renders properly with DejaVu Sans font
- [ ] Marksheet has school name, class, exam type, date

---

### 🟡 PORTAL 3: Student Portal

**Login:** `student0@school.com` / `password` (Omar, student_id=1, ADM0001, Class 1)  
Also test: `student6@school.com` / `password` (Zainab, student_id=7, Class 2)

#### 2.29 Student Dashboard
- [ ] Dashboard loads for Omar (student_id=1)
- [ ] Shows: Class 1, Section A, Admission No ADM0001
- [ ] Quick stats: attendance %, pending fees, recent marks

#### 2.30 My Marks / Results
- [ ] Omar's marks show: English 42/100 (C), Maths 57/100 (C), Science 77/100 (B), Urdu 90/100 (A), Computer 40/100 (C)
- [ ] Subject-wise marks display correctly with grade
- [ ] Exam type filter (Annual, Mid-Term) works if multiple exam types exist
- [ ] Chart.js radar/bar chart of subject-wise performance renders

#### 2.31 Marksheet / Report Card (Student)
- [ ] Student can view/download their report card PDF
- [ ] Report card shows: all subjects, individual marks, total, percentage, grade, rank
- [ ] After Phase 1.9 SQL fix, report card data exists for Omar
- [ ] dompdf PDF generation works, proper formatting, school logo/header visible
- [ ] Download button triggers PDF download (not blank/error)

#### 2.32 Attendance (Student)
- [ ] Student sees their own attendance record
- [ ] Calendar view or table view of present/absent dates
- [ ] Attendance percentage calculation is correct
- [ ] Chart.js monthly attendance chart renders

#### 2.33 Fee Status (Student)
- [ ] Omar sees his fees: fee_id=6 (College fees 4000, Paid), fee_id=24 (Tuition 2000, Pending)
- [ ] Paid fees show receipt option
- [ ] Pending fees show due date and amount
- [ ] Fee receipt download works for paid fee (fee_id=6)
- [ ] Online payment gateway option visible (even if sandbox)

#### 2.34 Timetable (Student)
- [ ] Omar sees Class 1's weekly timetable
- [ ] Correct teacher name next to each subject slot
- [ ] Day-wise display correct

#### 2.35 Assignments (Student)
- [ ] Student sees assignments for Class 1 subjects
- [ ] Submit assignment (file upload or text notes)
- [ ] Submission status: Pending/Graded shows correctly
- [ ] After teacher grades: marks_obtained + feedback visible to student

#### 2.36 Quizzes (Student)
- [ ] Student sees active quizzes for their class/subject
- [ ] Start quiz → questions load from `quiz_questions`
- [ ] MCQ answer selection works
- [ ] Submit quiz → `quiz_attempts` + `quiz_answers` saved
- [ ] Score calculated and shown: correct_answers × marks_per_question
- [ ] Student cannot re-take a completed quiz (check `quiz_attempts` status)

#### 2.37 Digital Notes (Student)
- [ ] Student sees notes uploaded for Class 1 subjects
- [ ] Download PDF note works
- [ ] External URL note opens in new tab
- [ ] Download count increments in `digital_notes.download_count`

#### 2.38 Leave Request (Student)
- [ ] Student submits leave request
- [ ] Seeded requests: student_id=7 has 2 pending sick leaves
- [ ] Student sees status of their leave (Pending/Approved/Rejected)
- [ ] Approved leave shows in attendance as excused absence

#### 2.39 ID Card (Student)
- [ ] Student can download/view their ID card
- [ ] ID card PDF has: name, class, admission no, DOB, father name, school name, QR/barcode
- [ ] Photo placeholder shows when photo=NULL

#### 2.40 Notifications (Student)
- [ ] Bell icon shows notification count
- [ ] Notifications from `notifications` table load
- [ ] Mark as read works

---

### 🟠 PORTAL 4: Parent Portal

**Login:** `parent0@school.com` / `password` (Parent of Omar → student_id=1)  
Also test: `parent6@school.com` / `password` (Parent of Zainab → student_id=7)

#### 2.41 Parent Dashboard
- [ ] Parent dashboard loads
- [ ] Shows linked child: Omar Student (ADM0001, Class 1)
- [ ] If parent has multiple children, child switcher works (currently 1:1 mapping in seed)
- [ ] Quick stats for child: attendance %, latest marks, fee status

#### 2.42 Child's Marks (Parent view)
- [ ] Parent sees Omar's marks: English 42/100, Maths 57/100, Science 77/100, Urdu 90/100, Computer 40/100
- [ ] Same data as student sees — parent cannot edit marks
- [ ] Subject performance chart renders

#### 2.43 Report Card (Parent)
- [ ] Parent can download child's report card PDF
- [ ] Same PDF as student sees
- [ ] Marksheet also accessible if implemented

#### 2.44 Attendance (Parent)
- [ ] Parent sees Omar's attendance record
- [ ] Monthly summary / percentage displayed
- [ ] Low attendance alert visible if below threshold

#### 2.45 Fee Status (Parent)
- [ ] Parent sees Omar's fees: College fees (Paid ✓), Tuition (Pending)
- [ ] Receipt download for paid fees works
- [ ] Parent can initiate fee payment for pending fees
- [ ] Payment history visible

#### 2.46 Child's Leave Status
- [ ] Parent can see child's leave request status
- [ ] Parent can submit leave on behalf of child
- [ ] Leave request visible to admin/teacher after submission

#### 2.47 Assignments & Quizzes (Parent read-only)
- [ ] Parent sees child's assignment submissions + grades
- [ ] Parent sees child's quiz scores
- [ ] Parent cannot submit or modify anything

#### 2.48 Notifications (Parent)
- [ ] Fee due reminders, exam results, event announcements visible
- [ ] Mark as read works

#### 2.49 Events (Parent)
- [ ] Upcoming school events visible (seeded events)
- [ ] Event filter by type works

---

### 🔴 PORTAL 5: Accountant Portal

**Login:** `accountant@school.com` / `password` (Demo Accountant, role_id=6, school_id=1)

#### 2.50 Accountant Dashboard
- [ ] Dashboard loads for accountant
- [ ] Summary cards: Total Fees Collected, Pending Fees, Total Expenses, Net Balance
- [ ] Fee collection chart (monthly) renders with Chart.js
- [ ] Payroll summary card shows

#### 2.51 Fee Collection
- [ ] Fee list: shows all 41 fee records for school_id=1
- [ ] Filter by status: Paid/Pending/Overdue
- [ ] Filter by class: Class 1/2/3 students
- [ ] Filter by fee category: College fees, Tuition
- [ ] Search by student name / challan number
- [ ] **Mark fee as Paid:** select pending fee → enter amount → submit → status changes to 'Paid', paid_amount updated
- [ ] Partial payment handling: if amount paid < total, status stays 'Pending' or goes 'Partial'
- [ ] Overpayment detection: flag if paid_amount > amount (like fee_id=9 with 8000 for 4000 fee)
- [ ] Fee receipt auto-generated on marking paid → `fee_receipts` record created
- [ ] Fee receipt PDF downloadable with receipt_no, student name, amount, date, signature block
- [ ] Challan printing works for pending fees

#### 2.52 Fee Structure Management
- [ ] View fee structures per class:
  - Class 1: College fees 4000, Tuition 2000
  - Class 2: College fees 3500, Tuition 3000
  - Class 3: College fees 3000, Tuition 2500
- [ ] Edit fee amount → updates `fee_structures` table
- [ ] Bulk generate fees for new month from fee structure → creates rows in `fees` for all students

#### 2.53 Payroll Management
- [ ] Payroll list shows:
  - Aisha Syed (EMP0004): 70000 + 8000 - 5000 = 73000, Pending
  - After Phase 1 seed: Ali Khan, Fatima Ahmed, Usman Tariq, Bilal Malik also visible
- [ ] Generate payroll for a teacher: calculates basic_pay + allowances - deductions = net_salary
- [ ] Mark payroll as Paid → status updates
- [ ] Pay slip PDF generation: employee name, emp_id, basic pay, allowances, deductions, net salary, month, school stamp
- [ ] Tax slip generation → `tax_slips` table updated
- [ ] Salary history per teacher visible

#### 2.54 Expenses
- [ ] After Phase 1 seed data: expense list shows entries
- [ ] Add new expense: category, amount, description, date → `expenses` table updated
- [ ] Expense categories from `expense_categories` table
- [ ] Expense list filterable by category, date range
- [ ] Monthly expense total in dashboard

#### 2.55 Ledger / Financial Ledger
- [ ] After Phase 1 seed: `ledger_entries` table has data
- [ ] Ledger shows debit/credit entries chronologically
- [ ] Filter by date range
- [ ] Running balance calculated correctly
- [ ] Fee payments auto-create ledger credit entries
- [ ] Expense additions auto-create ledger debit entries
- [ ] Payroll disbursements create ledger debit entries

#### 2.56 Financial Reports
- [ ] Monthly income/expense report generates
- [ ] Fee collection report by class: Class 1 collected X/Y
- [ ] Defaulter list (students with pending fees) generates
- [ ] Export to PDF (dompdf) works
- [ ] Export data to printable format

#### 2.57 Bank Accounts
- [ ] After Phase 1 seed: bank account visible
- [ ] Add bank account works → `bank_accounts` table updated
- [ ] Bank reconciliation feature (if implemented)

---

## 📋 PHASE 3 — Cross-Portal Data Flow Tests

### 3.1 Complete Marks Flow
**Test sequence:**
1. Login as Teacher Ali Khan (`ali.khan@school.com`)
2. Go to Marks → Class 1 → English → enter NEW marks for student Omar (student_id=1): 85/100
3. Save → verify in DB: `marks` table has new/updated row with percentage=85, grade='A', is_pass=1
4. Login as Student Omar (`student0@school.com`) → My Results → English should show 85/100 'A'
5. Login as Parent of Omar (`parent0@school.com`) → Child Results → English 85/100 visible
6. Login as Admin (`admin@school.com`) → Student profile Omar → Marks → 85/100 visible
7. Report card for Omar should auto-update: new percentage, new grade

### 3.2 Complete Fee Payment Flow
**Test sequence:**
1. Login as Admin → Fees → Student Omar → fee_id=24 (Tuition 2000, Pending)
2. Mark as Paid (or route to Accountant)
3. Login as Accountant (`accountant@school.com`) → Fee Collection → Omar's Tuition → Mark Paid (2000)
4. Verify: `fees.status`='Paid', `fees.paid_amount`=2000
5. Verify: `fee_receipts` row created with receipt_no
6. Verify: `ledger_entries` credit entry created
7. Login as Student Omar → Fees → Tuition shows as Paid
8. Login as Parent of Omar → Fees → Tuition shows Paid + receipt downloadable

### 3.3 Leave Request Flow (Student)
**Test sequence:**
1. Login as Student Zainab (`student6@school.com`)
2. Submit leave request: Sick Leave, 2026-06-20 to 2026-06-21, reason "Fever"
3. Verify: `student_leave_requests` row created with status='Pending'
4. Login as Admin → Leave Management → Zainab's request visible
5. Approve the leave → status='Approved'
6. Login as Zainab → Leave status shows 'Approved'
7. Verify attendance for those dates shows as 'L' (Leave) not 'A' (Absent)

### 3.4 Leave Request Flow (Teacher)
**Test sequence:**
1. Login as Teacher Fatima Ahmed (`fatima.ahmed@school.com`)
2. Submit leave request: Medical, 2026-06-25 to 2026-06-26, reason "Doctor appointment"
3. Validate: end_date validation rejects past dates
4. Verify: `teacher_leave_requests` row created, status='Pending'
5. Login as Admin → Teacher Leave → Fatima's request visible
6. Approve → status='Approved'
7. Fatima logs in → sees 'Approved'

### 3.5 Assignment → AI Grading Flow
**Test sequence:**
1. Login as Student Hasan (`student1@school.com`)
2. Submit an assignment: notes = "This is my completed work on the assignment topic..."
3. Verify: `assignment_submissions` row created with status='Submitted'
4. Login as Teacher Ali Khan
5. View Hasan's submission → click "AI Grade" (Gemini API)
6. Verify: Gemini called → `ai_grading_results` row created with suggested_score > 0, feedback non-empty
7. Teacher reviews AI suggestion → confirms grade → `assignment_submissions.marks_obtained` + `status`='Graded'
8. Student Hasan logs in → sees grade + teacher feedback

### 3.6 Quiz Attempt Flow
**Test sequence:**
1. Login as Teacher → create quiz for Class 1, Subject English, add 3 MCQ questions
2. Set quiz active, start_at = now
3. Login as Student Omar → Quizzes → see new quiz
4. Start quiz → attempt all 3 questions
5. Submit → `quiz_attempts.status`='Completed', score calculated
6. View score immediately after submission
7. Login as Teacher → Quiz Results → Omar's attempt with score visible

### 3.7 Document Generation Flow
**Test sequence:**
1. Login as Admin
2. Generate Transfer Certificate for student ADM0002 (Hasan)
3. Verify: `issued_documents` row created with `uuid` NOT NULL this time (after B6 fix)
4. PDF downloads, contains: student name, class, TC number, school name, principal signature block, QR code
5. Generate Character Certificate for ADM0003 (Muhammad)
6. Verify: correct template (template_id=2) used
7. Generate ID Card for ADM0001 (Omar)
8. Verify: ID card has all fields (name, class, photo placeholder, school logo)

### 3.8 Report Card PDF Flow
**Test sequence:**
1. After Phase 1.9 SQL inserts report_cards data
2. Login as Admin → Reports → Report Card → select Omar → Generate PDF
3. PDF opens: school header, student info, subject-wise marks table, total/percentage/grade
4. Login as Student Omar → Report Card → Download → same PDF
5. Login as Parent of Omar → Report Card → Download → same PDF
6. Verify dompdf renders DejaVu Sans font correctly (Urdu/special chars if any)

---

## 📋 PHASE 4 — AI Features Deep Audit

### 4.1 AI Grading (Gemini)
**Location:** Assignment submissions, `ai_grading_results` table, `AiGrading` service

- [ ] Gemini API key is set in `.env` (`GEMINI_API_KEY`)
- [ ] Model used: `gemini-2.5-flash` (confirmed in seed data)
- [ ] Rubric breakdown stored as JSON: `{"Clarity":X,"Accuracy":X,"Completeness":X}`
- [ ] Tokens used tracked in `ai_grading_results.tokens_used`
- [ ] Empty submission gives score=0 with explanatory feedback (confirmed working for submission_id=1,4)
- [ ] Actual content submission gives score > 0 — **TEST THIS**
- [ ] AI result displayed nicely in teacher UI with rubric bars
- [ ] Teacher can override AI score and submit final grade

### 4.2 Smart Attendance Pattern Analysis
- [ ] If implemented: analyze student attendance patterns
- [ ] Flag students with attendance below threshold
- [ ] Prediction/alert: "Student X likely to be absent tomorrow based on pattern"
- [ ] Admin/teacher dashboard widget for attendance alerts

### 4.3 Digital Learning Notes AI (if implemented)
- [ ] AI summary of uploaded PDF notes
- [ ] AI-generated quiz questions from notes content
- [ ] Smart search across digital notes

### 4.4 Fee Default Prediction (if implemented)
- [ ] AI predicts which students are likely to default on fees
- [ ] Based on: previous payment history, payment delays
- [ ] Alert shown in Accountant dashboard

### 4.5 Promotion Rules Engine
- [ ] `promotion_rules` table has seeded data: from_class_id, to_class_id, min_percentage, min_attendance_pct
- [ ] Admin runs promotion → system checks each student's percentage + attendance
- [ ] Students meeting criteria auto-promoted to next class
- [ ] Failed students held back
- [ ] Promotion result report generated

---

## 📋 PHASE 5 — PDF/Print Generation Audit

All PDFs use **dompdf** with **DejaVu Sans** font. Test each:

| Document | Route/URL | What to Check |
|----------|-----------|---------------|
| Transfer Certificate | `/admin/documents/issue/{student}` | TC number, student name, class, dates, QR, signature |
| Character Certificate | `/admin/documents/issue/{student}` | School name, conduct statement, dates |
| Student ID Card | `/admin/students/{id}/id-card` | Photo placeholder, barcode/QR, all fields |
| Report Card | `/admin/report-cards/{student}/pdf` | All subjects, marks, total, grade, rank, school header |
| Marksheet (Class) | `/teacher/marks/marksheet/{class}` | All students in class, subject columns, totals |
| Fee Receipt | `/accountant/receipts/{id}/pdf` | Receipt no, challan no, amount, date, school stamp |
| Pay Slip | `/accountant/payroll/{id}/payslip` | Employee details, salary breakdown, month, signature |
| Tax Slip | `/accountant/payroll/{teacher}/tax-slip` | Income, tax deducted, tax year |
| Attendance Report | `/admin/attendance/report/{class}` | Date range, student rows, P/A/L counts |

**For each PDF check:**
- [ ] PDF opens (not 500 error)
- [ ] Content is correct (not placeholder/empty)
- [ ] dompdf renders without font errors
- [ ] Page orientation correct (portrait/landscape as appropriate)
- [ ] School logo appears (check if logo path set in `schools` table)

---

## 📋 PHASE 6 — Sidebar & Navigation Audit

Check all portal sidebars for missing/broken links:

### Admin Sidebar
- [ ] Dashboard ✓
- [ ] Students → All CRUD operations linked
- [ ] Teachers → All CRUD + assignments
- [ ] Classes & Sections
- [ ] Subjects
- [ ] Attendance → Student + Teacher
- [ ] Marks / Exam Management
- [ ] Fee Management → Fee List, Fee Categories, Fee Structure, Receipts
- [ ] Payroll → Payroll List, Pay Slips
- [ ] Expenses → List + Categories
- [ ] Ledger
- [ ] Documents → Issue Certificate, ID Cards, Templates
- [ ] Report Cards
- [ ] Timetable
- [ ] Library
- [ ] Inventory
- [ ] Transport
- [ ] Hostel
- [ ] Quizzes
- [ ] Digital Notes
- [ ] Events
- [ ] Announcements
- [ ] Leave Management → Student Leave, Teacher Leave
- [ ] Audit Logs
- [ ] Roles & Permissions
- [ ] School Settings

### Teacher Sidebar
All modules that teacher has `teacher_module_access` entries for:
- [ ] Dashboard
- [ ] My Classes / Students
- [ ] Attendance (mark student attendance)
- [ ] Marks Entry
- [ ] Assignments (create/grade)
- [ ] Digital Notes (upload)
- [ ] Quizzes (create)
- [ ] Timetable (view)
- [ ] Leave Requests
- [ ] My Profile

### Student Sidebar
- [ ] Dashboard
- [ ] My Results / Marks
- [ ] Report Card
- [ ] Attendance
- [ ] Fee Status
- [ ] Timetable
- [ ] Assignments
- [ ] Quizzes
- [ ] Digital Notes
- [ ] Leave Requests
- [ ] ID Card
- [ ] Notifications

### Parent Sidebar
- [ ] Dashboard
- [ ] Child's Results
- [ ] Report Card
- [ ] Attendance
- [ ] Fee Status
- [ ] Leave Requests
- [ ] Assignments (read-only)
- [ ] Events
- [ ] Notifications

### Accountant Sidebar
- [ ] Dashboard
- [ ] Fee Collection
- [ ] Fee Structure
- [ ] Fee Receipts
- [ ] Payroll
- [ ] Expenses
- [ ] Ledger
- [ ] Financial Reports
- [ ] Bank Accounts

---

## 📋 PHASE 7 — Security & Authorization Checks

- [ ] Student cannot access teacher/admin routes (middleware protection)
- [ ] Parent cannot see other parents' children (scope by `parent_user_id`)
- [ ] Teacher can only mark attendance/marks for **their assigned classes** (not all classes)
- [ ] Accountant cannot access student marks or teacher management
- [ ] CSRF protection on all forms
- [ ] School isolation: all queries scoped to `school_id=1` where applicable
- [ ] Soft-deleted students (`deleted_at NOT NULL`) do not appear in active lists

---

## 📋 PHASE 8 — Final Verification Checklist

After all fixes run, do these final spot checks:

```sql
-- 1. Verify School Admin user exists
SELECT id, name, email, role_id FROM users WHERE role_id = 2;

-- 2. Verify report_cards populated
SELECT student_id, percentage, grade FROM report_cards ORDER BY student_id;

-- 3. Verify marks have no NULL exam_schedule issues affecting reports
SELECT COUNT(*) as total_marks, SUM(CASE WHEN exam_schedule_id IS NULL THEN 1 ELSE 0 END) as null_schedule FROM marks;

-- 4. Verify fee inconsistencies fixed
SELECT id, amount, paid_amount, status FROM fees WHERE (status='Paid' AND paid_amount=0) OR (paid_amount > amount);

-- 5. Verify issued_documents have UUID
SELECT COUNT(*) as total, SUM(CASE WHEN uuid IS NULL THEN 1 ELSE 0 END) as null_uuid FROM issued_documents;

-- 6. Verify all teachers have module access
SELECT t.full_name, COUNT(tma.id) as module_count FROM teachers t LEFT JOIN teacher_module_access tma ON t.id=tma.teacher_id GROUP BY t.id;

-- 7. Verify payrolls for all teachers
SELECT t.full_name, p.net_salary, p.status FROM teachers t LEFT JOIN payrolls p ON t.id=p.teacher_id;

-- 8. Verify ledger has entries
SELECT COUNT(*) FROM ledger_entries;

-- 9. Verify parent-student links correct
SELECT u.name as parent, s.first_name as student, s.admission_no FROM parent_students ps JOIN users u ON ps.parent_user_id=u.id JOIN students s ON ps.student_id=s.id;

-- 10. Verify report card PDF route works for student_id=1
-- Test: GET /student/report-card/1/pdf → 200 OK, PDF content-type
```

---

## 🎯 Deliverable Expected From Antigravity

For each issue found:
1. **Identify** → exact file, line number, function name
2. **Fix** → provide corrected code
3. **Verify** → SQL query or browser step to confirm fix works

**Priority order:**
1. 🔴 Critical: Portal login failures, broken PDF generation, empty data displays
2. 🟠 High: Wrong data shown (fee mismatch, marks not saving), missing DB records
3. 🟡 Medium: UI/UX issues in portals, missing sidebar links
4. 🟢 Low: AI feature enhancements, optional polish

**Do NOT touch:**
- Any CSS files
- Blade layout files (overall structure)
- Color variables / theme files
- Font configuration (keep DejaVu Sans for dompdf)
