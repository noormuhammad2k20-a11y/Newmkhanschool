# NewMkhanSchool — System Audit: Maujooda Halat vs Required Pages/Fields/Buttons

Yeh file aapke GitHub project (`Newmkhanschool`) aur uploaded database (`newschool__13_.sql`) ko check karne ke baad banayi gayi hai. Har portal ke har page ke liye: konsi chezen **already maujood hain**, aur konsi **missing hain / add karni hain**.

Legend: ✅ Maujood | ⚠️ Partial/Placeholder | ❌ Missing

---

# 1. ADMIN PORTAL

## 1.1 Dashboard (`admin/dashboard.blade.php`) ✅
**Maujood:** Basic dashboard view exists.
**Missing/Add:**
- Stats cards: Total Students, Teachers, Branches, Fee Collected, Pending Fees, Attendance % — confirm sab cards data-bound hain
- Quick Action Buttons: "Add Student", "Add Teacher"
- Charts: Fee Collection trend, Attendance trend, Class distribution

## 1.2 Students — Index (`students/index.blade.php`) ✅
**Maujood:**
- Search input (Name/B-Form/Admission No)
- Filters: Class, Section, Status dropdowns
- Tuition checkbox filter
- Remove/Delete button

**Missing/Add:**
- ❌ "Export" button (CSV/Excel/PDF)
- ❌ "Bulk Import" button (CSV upload)
- ❌ Pagination control (confirm if present)
- ❌ Edit/View action buttons per row (confirm visible in table)

## 1.3 Students — Create (`students/create.blade.php`) ✅ (very complete)
**Maujood:**
- first_name, last_name, email, date_of_birth, gender (select)
- placeofbirth, religion, caste
- admission_number, exam_roll, admission_date, class_admitted
- current_class_id, current_section_id (selects)
- previous_school, current_school
- guardian_name, national_id (CNIC), emergency_contact
- address (textarea), status (select), is_tuition (checkbox)
- photo upload

**Missing/Add (database has columns but form doesn't use them):**
- ❌ `father_cnic` separate field (only `national_id` exists — confirm mapping)
- ❌ `mobile_number` field (student's own mobile — DB has it)
- ❌ `b_form_number` — DB has it, form doesn't show it explicitly (only national_id?)
- ❌ Blood Group field (not in DB at all — add column + field if needed)
- ❌ "Save & Add Another" button

## 1.4 Teachers — Create (`teachers/create.blade.php`) ✅ (multi-step wizard)
**Maujood:**
- Step 1: first_name, last_name, email, phone, cnic
- Step 2: qualification, subject_specialization, experience
- Step 3: resume upload
- Next/Previous/Submit buttons

**Missing/Add:**
- ❌ Gender dropdown
- ❌ Date of Birth field
- ❌ Profile Photo upload (only resume upload exists)
- ❌ Branch dropdown (school_id is fixed=1; multi-branch not wired in form)
- ❌ Assigned Classes/Subjects multi-select (DB has `teacher_assignments` table — but not in create form; assignment done separately)
- ❌ Joining Date field
- ❌ Salary field (linked to payroll — payroll table exists separately, no link in teacher create)
- ❌ Employment Status dropdown (Active/On Leave/Resigned)
- ❌ Address field

## 1.5 Student Attendance (`attendance/mark.blade.php`) ✅
**Maujood:** Attendance marking page exists with class/section/date filters.

## 1.6 Teacher Attendance (`attendance/teacher.blade.php`) ✅
**Maujood:** Page exists.
**Missing/Add:**
- ❌ Check-in / Check-out time fields (DB `teacher_attendances` — confirm columns support this)
- ❌ Export report button

## 1.7 Classes & Subjects (`admin/academics/index.blade.php`) ✅
**Maujood:**
- Add Class: name, sections (multi)
- Add Subject: name, code, class_id, subject_id, teacher_id (assignment)

**Missing/Add:**
- ❌ "Assign Class Teacher" dropdown when creating a Class
- ❌ Edit/Delete actions on Classes/Subjects table — confirm present
- ❌ Subject Type dropdown (Core/Elective) — not in DB schema

## 1.8 Timetable (`admin/timetables/form.blade.php`, `classes/timetable.blade.php`) ⚠️
**Maujood:** Basic form with subject_id_ref, teacher_id. `timetable_versions` table exists in DB (good — versioning supported).
**Missing/Add:**
- ❌ Full grid view (Days x Periods) — confirm renders as grid, not just a form
- ❌ "Auto-Generate Timetable" button linking to AI Timetable Generator
- ❌ Settings: periods/day, period duration, break times, school start/end time (not in DB — needs settings table or config)
- ❌ Print/Export PDF button

## 1.9 Examination (`exams/index.blade.php`, `exams/marks.blade.php`) ✅
**Maujood:**
- Create Exam: class_id, exam_type, delete_group, and per-subject rows (subject_id, exam_date, exam_time, end_time, max_marks, passing_marks) — this is solid
- `exam_types` and `exam_schedules` tables exist

**Missing/Add:**
- ⚠️ Marks Entry table (`exams/marks.blade.php`) — currently NO input/select fields detected; likely needs:
  - ❌ Marks Obtained input per student (bound to `marks` table)
  - ❌ "Save Marks" button
  - ❌ "Lock Marks" toggle (DB doesn't track lock-status — add a column if needed)
- ❌ Result Tab: "Generate Result Cards", "Publish Results", "Print Report Cards" buttons
- ❌ Class performance summary chart after marks entry

## 1.11 Fee Management — Fee Structure (`accountant/fee-structure/index.blade.php`) ⚠️
**Maujood:** Table showing Class, Fee Category, Default Amount (read display). `fee_categories`, `fee_structures` tables exist with data.
**Missing/Add:**
- ❌ "Add Fee Structure" form (Class dropdown, Fee Head input/select, Amount input, Frequency dropdown) — frequency not in DB schema, needs adding if required
- ❌ Edit/Delete actions on fee structure rows

## 1.12 Fee Collection (`fees/index.blade.php`, `admin/fees/challan-pdf.blade.php`) ✅
**Maujood:** `fees`, `fee_payment_transactions`, `fee_receipts` tables fully built with JazzCash/EasyPaisa/Cash/Bank/Cheque gateways, challan numbers, receipts, PDF generation. This module is well developed.

## 1.13 Staff Payroll (`admin/payroll/index.blade.php`, `accountant/payroll/index.blade.php`) ⚠️
**Maujood:** Table shows Employee, Month/Year, Basic Pay, Deductions, Net Salary, Status. `payrolls` and `tax_slips` tables exist in DB.
**Missing/Add:**
- ❌ "Generate Payroll" button is just `alert('Feature coming soon')` — **not functional**, needs real implementation:
  - ❌ Month/Year picker
  - ❌ Allowances/Deductions input fields per employee
  - ❌ "Mark as Paid" button (functional)
  - ❌ "Generate Payslip (PDF)" — `slip_pdf.blade.php` exists, confirm linked
  - ❌ "Bulk Process Payroll" button

## 1.14 Inventory Management (`admin/inventory/*`) ✅ (quite complete)
**Maujood:**
- Create: asset_code, category, condition_status, description, location, min_stock_alert, name, purchase_price, quantity, supplier, unit
- stock-in.blade.php, stock-out.blade.php exist
- `inventory`, `inventory_transactions`, `inventory_purchases` tables exist

**Missing/Add:**
- ❌ "Low Stock" status badge/filter using `min_stock_alert`
- ❌ Branch/Store dropdown (if multi-branch needed)

## 1.15 Branches (`admin/branches/*`) ✅ (CRUD complete: create/edit/index/show)
**Missing/Add:**
- ⚠️ Confirm `schools` table supports multiple branches with: Branch Head, Contact, Total Students/Teachers counts on index — check if these stats are computed
- ❌ Active/Inactive toggle — confirm in schema

## 1.16 Documents & Certificates (`admin/documents/*`) ✅ (very complete)
**Maujood:** create, index, pdf, preview, select-template, signatures, templates/edit, templates/index. `document_templates`, `issued_documents` tables with real data (Transfer Certificate, Character Certificate, Marksheet templates with HTML).

## 1.17 Document Templates ✅
**Maujood:** Editor with template content (rich HTML), `document_templates` table.
**Missing/Add:**
- ❌ "Preview with Sample Data" button — confirm exists
- ❌ File Upload for Letterhead/Logo — confirm separate from template HTML

## 1.18 Digital Signatures (`admin/documents/signatures.blade.php`) ✅
**Maujood:** Upload form for Principal's signature (`principal_signature_path`), shows current signature, "Active" badge.
**Missing/Add:**
- ❌ Multiple signatories support (currently only ONE — "Principal"). DB/schema needs a `signatories` table for: Name, Designation, Signature Image, Stamp Image, Active toggle, Default-for-document-type
- ❌ Stamp/Seal image upload (separate from signature)

## 1.19 Analytics (`admin/analytics/*`) ✅
**Maujood:** index, branch, revenue views exist.
**Missing/Add:**
- ❌ Confirm charts render: Enrollment Trends, Revenue vs Expenses, Attendance Heatmap, Subject-wise Performance, Teacher Workload
- ❌ "Export Report (PDF/Excel)" button
- ❌ Date Range filter on analytics pages

## 1.20 Promotions (`admin/promotions/*`) ✅ (index, preview, rules — quite complete)
**Maujood:** academic_year_id, class_id selects; `promotion_rules`, `student_promotions` tables with min_percentage, min_attendance_pct logic — well built!
**Missing/Add:**
- ❌ Per-student "Promote To" dropdown override (in case bulk rule doesn't fit one student)
- ❌ "Generate Promotion Report" button/export

## 1.21 AI Modules — Attendance Prediction (`admin/ai/attendance.blade.php`) ❌ Placeholder
**Maujood:** Page exists but only 2 elements detected (likely just a button/header).
**Missing/Add:**
- ❌ Class/Student select dropdown
- ❌ Date range picker
- ❌ "Run Prediction" button (functional, calling AI/ML logic)
- ❌ Output table: students at risk of absence + confidence %
- ❌ "Send Alert to Parents" button
- **Backend:** No prediction logic/model integration found — needs to be built from scratch (this is the biggest gap)

## 1.22 AI Modules — Student Risk Analysis (`admin/ai/risk.blade.php`) ⚠️ Partial
**Maujood:** class_id select exists.
**Missing/Add:**
- ❌ "Analyze" button (functional)
- ❌ Output table: Student, Risk Score, Risk Factors, Recommended Action with color badges
- ❌ "Notify Teacher/Parent" button
- **Backend:** `ai_grading_results` table exists but is for assignment grading, not student risk — separate logic/table needed

## 1.23 AI Modules — Timetable Generator (`admin/ai/timetable.blade.php`) ⚠️ Partial (most built of the 3 AI pages)
**Maujood:** Form has id, day_of_week, start_time, end_time, room, subject_id, teacher_id — looks like manual timetable entry, not true AI generation.
**Missing/Add:**
- ❌ "Generate Timetable" AI button with constraint inputs (teacher availability, subject hours/week)
- ❌ Auto-generated preview grid
- ❌ "Apply to Timetable" / "Regenerate" / "Save as Draft" buttons
- **Backend:** No constraint-solving/generation algorithm found — needs building

## 1.24 Roles & Permissions (`admin/roles/index.blade.php`) ✅
**Maujood:** `roles`, `permissions`, `role_permissions` tables — well structured (6 roles, 29+ permissions). Form has `permissions[]` checkboxes.
**Missing/Add:**
- ❌ "Add Role" button (create new custom role)
- ❌ Permission Matrix view (Modules x Permissions grid) — confirm current layout is matrix or simple list
- ❌ User → Role assignment tab (search user, assign role, update)

## 1.25 Missing Entirely — Modules in DB but NO Admin UI
- ❌ **Banking & Cash Book — Cash Book tab**: `bank_accounts` and `ledger_entries` tables exist, but accountant only has Bank Accounts CRUD; Cash Book entry form (Date, Type, Description, Amount, Account, Reference) is missing.

---

# 2. ACCOUNTANT PORTAL

## 2.1 Dashboard (`accountant/dashboard.blade.php`) ✅
**Missing/Add:**
- ❌ Stats cards (Today's Collection, Monthly Collection, Pending Dues, Total Expenses)
- ❌ Charts (Collection vs Target, Expense Breakdown)
- ❌ "Collect Fee" / "Add Expense" quick buttons

## 2.2 Fee Collection (`accountant/fees/index.blade.php`) ✅
**Maujood:** search, amount, gateway, status fields — solid, matches `fee_payment_transactions` schema (JazzCash, EasyPaisa, Cash, Bank, Cheque, Bank Transfer, Online).
**Missing/Add:**
- ❌ "Print Receipt" button after collection — confirm `receipt_pdf.blade.php` is linked
- ❌ Fee breakdown display before collecting (multiple fee_category rows for one student)

## 2.3 Fee Structure (`accountant/fee-structure/index.blade.php`) ⚠️
Same as Admin 1.11 above — needs Add/Edit form.

## 2.4 Defaulters (`accountant/fees/defaulters.blade.php`) ⚠️ Minimal
**Maujood:** `fee_id` field + button only.
**Missing/Add:**
- ❌ Filter: Class, Days Overdue
- ❌ Table: Student, Guardian Contact, Amount Due, Days Overdue, Last Paid Date
- ❌ Bulk checkbox select
- ❌ "Export to Excel" button

## 2.5 Payroll (`accountant/payroll/index.blade.php`) ⚠️
Same as Admin 1.13 — "Generate Payroll" is non-functional placeholder. `slip_pdf.blade.php` exists (good), but generation flow missing.

## 2.6 Expenses (`accountant/expenses/index.blade.php`) ✅
**Maujood:** amount, description, expense_category_id, expense_date, voucher_no — `expenses`, `expense_categories` tables exist. Good coverage.
**Missing/Add:**
- ❌ File Upload for Receipt/Invoice (DB has no column for receipt path — add `receipt_path` to `expenses` table)
- ❌ Payment Mode dropdown (Cash/Bank/etc — not in `expenses` schema)
- ❌ Paid To (Vendor name) field — not in schema

## 2.7 Banking & Cash Book (`accountant/bank-accounts/index.blade.php`) ⚠️
**Maujood:** Bank Accounts tab complete (account_name, account_number, bank_name, branch, initial_balance). `bank_accounts`, `ledger_entries` tables exist.
**Missing/Add:**
- ❌ Cash Book tab entirely missing — needs:
  - Table: Date, Description, Debit, Credit, Balance, Mode, Reference (bound to `ledger_entries`)
  - "Add Entry" form: date, type (Receipt/Payment), description, amount, account, reference no.

## 2.8 Inventory Purchases (`accountant/inventory-purchases/index.blade.php`) ✅
**Maujood:** invoice_number, notes, payment_status, purchase_date, supplier_name, total_amount — matches `inventory_purchases` table.
**Missing/Add:**
- ❌ Line-items sub-table (Item, Qty, Unit Price, Total per item) — confirm `inventory_transactions` linkage exists in form
- ❌ "Mark as Received" button to auto-update stock
- ❌ File Upload: Invoice attachment

## 2.9 My Profile (`accountant/profile/edit.blade.php`) ✅
**Missing/Add:**
- ❌ Confirm Change Password fields (current/new/confirm) present
- ❌ Profile photo upload

---

# 3. TEACHER PORTAL

## 3.1 Dashboard (`teacher/dashboard.blade.php`) ✅
**Missing/Add:**
- ❌ Today's Timetable widget
- ❌ Pending Assignments to Grade count
- ❌ Quick action buttons (Mark Attendance, Create Assignment)

## 3.2 Digital Notes (`teacher/digital_learning/notes.blade.php`) ✅
`digital_notes` table is well-built (file_type enum, external_url, is_public, download_count).
**Missing/Add:**
- ❌ Confirm Upload form has: Title, Class, Subject, Description, File/Link, Publish toggle — verify all present

## 3.3 Quizzes (`teacher/digital_learning/quizzes.blade.php`, `quiz_questions.blade.php`) ✅
`quizzes`, `quiz_questions`, `quiz_attempts`, `quiz_answers` tables — comprehensive (duration, start_at/end_at, passing_marks).
**Missing/Add:**
- ❌ Confirm "Add Question" repeatable form with 4 options + correct answer radio + marks per question
- ❌ "Publish Quiz" vs "Save as Draft" — `is_active` flag exists, confirm UI toggle

## 3.4 Student Attendance (`teacher/attendance.blade.php`) ✅

## 3.5 My Classes / My Subjects (`teacher/classes.blade.php`, `teacher/subjects.blade.php`) ✅
**Missing/Add:**
- ❌ Syllabus Progress tracker (% complete + notes) — no DB column for this, needs new field on `teacher_assignments` or new table

## 3.6 Student Lists (`teacher/students.blade.php`) ✅
**Missing/Add:**
- ❌ "Export List" button
- ❌ Attendance %/Average Marks columns — confirm computed and shown

## 3.7 Marks & Grades (`teacher/marks.blade.php`, `partials/marks_table.blade.php`) ✅
Matches `marks` table well (marks_obtained, total_marks, percentage, grade, gpa, is_pass, remarks).
**Missing/Add:**
- ❌ "Submit for Approval" / lock mechanism — no `is_locked` column in `marks` table, add if needed

## 3.8 Assignments (`teacher/assignments.blade.php`, `assignments/submissions.blade.php`) ✅
`assignments`, `assignment_submissions` tables exist.
**Missing/Add:**
- ❌ Confirm Create form: Title, Class, Subject, Instructions, Attachment, Due Date, Total Marks — all present?
- ❌ Submissions table: Marks input + Feedback textarea + "Save Grades" button

## 3.9 AI Auto Grader (`teacher/ai-grader/index.blade.php`) ⚠️
`ai_grading_results` table exists — good foundation.
**Missing/Add:**
- ❌ "Run Auto Grading" button (functional, calling AI grading service)
- ❌ Output table: AI Suggested Marks, AI Feedback, Teacher Override input, Approve checkbox
- ❌ "Approve All" / "Save Final Grades" buttons
- **Backend:** Confirm actual AI grading integration exists (Gemini test file `test_gemini.php` found in repo root — suggests partial integration, needs wiring to UI)

## 3.10 Exams & Results / Exam Schedule (`teacher/exams.blade.php`, `exam-schedule.blade.php`) ✅
**Missing/Add:**
- ❌ "View Result Summary" chart per class

## 3.11 Online Exams (`teacher/online-exams/*`) ✅ (create, index, questions, results — complete set)
**Missing/Add:**
- ❌ Confirm: Shuffle Questions toggle, Show Result Immediately toggle (check `quizzes`/online exam table for these flags — add columns if missing)
- ❌ Live monitoring view (Started/In Progress/Submitted status per student)

## 3.12 Seating Plans (`teacher/seating/*`) ✅ (create, edit, index, show — complete set)
**Missing/Add:**
- ❌ "Generate Seating Plan" auto-arrange button — confirm algorithm exists vs manual only
- ❌ "Print Seating Chart" button

## 3.13 Leave Requests (`teacher/leaves.blade.php`, `student-leaves.blade.php`) ✅
`teacher_leave_requests`, `student_leave_requests` tables exist.
**Missing/Add:**
- ❌ File Upload: Supporting Document field — confirm column exists in both leave tables
- ❌ Class teacher's "Student Leave Approval" tab — confirm Approve/Reject buttons present in `student-leaves.blade.php`

## 3.14 Student Performance (`teacher/performance.blade.php`) ✅
**Missing/Add:**
- ❌ Charts: Marks trend, Subject-wise bar, Attendance correlation
- ❌ "Export Performance Report" button
- ❌ Teacher remarks textarea (save to `marks.remarks` or new field)

## 3.15 My Profile (`teacher/profile.blade.php`) ✅
**Missing/Add:**
- ❌ Change Password fields — confirm present
- ❌ Profile photo upload — confirm

## 3.16 Messaging (`teacher/messages.blade.php`) ✅
**Missing/Add:**
- ❌ Confirm real-time/conversation-list UI vs simple message log
- ❌ Attachment support
- ❌ "New Message" → recipient selector

## 3.17 Other — `teacher/homework.blade.php`, `teacher/placeholder.blade.php`
- ⚠️ `placeholder.blade.php` suggests at least one teacher menu item is **not implemented yet** — identify which sidebar link points here and build it out.

---

# 4. STUDENT PORTAL

Overall this portal is the most complete (achievements, fees, exams, online exams, messaging, profile, etc. all have working views).

## 4.1 Dashboard ✅
**Missing/Add:**
- ❌ Stats cards: Attendance %, GPA, Pending Fee, Upcoming Exams count
- ❌ Recent Results widget

## 4.2 My Marks / My Progress ✅
**Missing/Add:**
- ❌ "Download Report Card (PDF)" button — confirm linked to a PDF template
- ❌ Class Rank/Position display (compute from `marks` table — needs ranking query)

## 4.3 Fee Status (`student/fees.blade.php`, `student/fees/initiate-payment.blade.php`) ✅
**Missing/Add:**
- ❌ "Download Fee Voucher" (offline payment) button
- ❌ Confirm online "Pay Now" actually integrates JazzCash/EasyPaisa (transaction table shows test/sandbox responses — needs production gateway config)

## 4.4 Timetable ✅ — confirm "Download/Print" button

## 4.5 Assignments ✅ — confirm Submit Assignment modal with file upload + comments

## 4.6 Attendance ✅ — confirm calendar view (color-coded) + "Download Attendance Report"

## 4.7 Exam Schedule ✅ — confirm "Download Schedule" button

## 4.8 Digital Notes / Quizzes / Online Exams / Quiz Results ✅ (full set exists, including `take.blade.php`, `take_quiz.blade.php`)
**Missing/Add:**
- ❌ Confirm Quiz/Exam interface has: Timer display, Question navigation grid, "Save & Next", "Mark for Review", "Submit" with confirmation modal

## 4.9 Leave Requests ✅ — confirm File Upload for supporting document

## 4.10 Messages (`student/messages.blade.php`) ✅ — confirm conversation list UI

## 4.11 My Profile (`student/profile.blade.php`) ✅
**Missing/Add:**
- ❌ Change Password section — confirm
- ❌ Editable contact fields vs fully read-only

---

# 5. PARENT PORTAL

This portal has a decent set of pages already (`parent/dashboard`, `children`, `child-*` views, `fees/payment`, `messages`, `notifications`, `online-exams`, `profile`).

## 5.1 Dashboard ✅
**Missing/Add:**
- ❌ Stats cards (Attendance %, Fee Due, Upcoming Exams, New Messages)
- ❌ Child selector if multiple children (`parent_students` table supports many-to-many — confirm UI handles 2+ children)

## 5.2 My Children (`parent/children.blade.php`) ✅
**Missing/Add:**
- ❌ Per-child tabs/cards with charts (Attendance trend, Performance trend)
- ❌ "View Full Report" button per child

## 5.3 Messages (`parent/messages.blade.php`) ✅
**Missing/Add:** Attachment button, conversation list with Teacher/Admin distinction

## 5.4 Exam Schedule (`parent/child-exam-schedule.blade.php`) ✅
**Missing/Add:** Child selector dropdown if multiple children, "Download Schedule" button

## 5.5 Leave Application (`parent/child-leave.blade.php`) ✅
**Missing/Add:** File Upload for supporting document, Child selector

## 5.6 Online Exams (`parent/online-exams/*`) ✅ (index + result — good)
**Missing/Add:** Child selector if multiple children

## 5.7 Fee Payment (`parent/fees/payment.blade.php`, `receipt.blade.php`) ✅
**Missing/Add:**
- ❌ Multi-fee-head checkbox selection (pay for multiple pending fees at once)
- ❌ "Download Voucher" for unpaid items
- ❌ Child selector dropdown if multiple children

## 5.8 My Profile (`parent/profile.blade.php`) ✅
**Missing/Add:**
- ❌ Linked Children read-only list display
- ❌ Change Password fields — confirm present

---

# SUMMARY — TOP PRIORITY GAPS (Cross-Portal)

1. **AI Modules (Admin)** — Attendance Prediction & Student Risk Analysis pages are near-empty placeholders; Timetable Generator is manual-entry only. Real AI/ML backend logic needs to be built for all three.
2. **Payroll "Generate Payroll" button** — currently `alert('Feature coming soon')` in both Admin and Accountant portals. This is non-functional and is a core finance feature.
3. **Cash Book** (Accountant → Banking & Cash Book) — only Bank Accounts sub-tab exists; Cash Book ledger entry form is missing despite `ledger_entries` table existing.
4. **Digital Signatures** — only ONE signatory (Principal) supported; multi-signatory system (Head Master, Class Teacher, etc.) needs new table + UI.
5. **Teacher Create Form** — missing Gender, DOB, Photo, Branch, Class/Subject assignment, Joining Date, Salary, Employment Status, Address fields (all standard but not in current 3-step wizard).
6. **Export/Bulk Import buttons** — missing across Students, Teachers, Defaulters, Performance reports list pages.
7. **Marks Entry page** (`exams/marks.blade.php`) — appears to have no input fields detected; needs Marks Obtained inputs + Save button bound to `marks` table.
8. **`teacher/placeholder.blade.php`** exists — at least one Teacher sidebar item is unimplemented; identify and build.
9. **Expense receipt/vendor fields** — `expenses` table lacks `receipt_path`, `payment_mode`, `paid_to` columns needed for the full Expense form.

---

# Notes
- Aapke repo mein already kuch detailed prompt/audit `.md` files mojood hain (`newmkhanschool_master_upgrade_prompt.md`, `NewMkhanSchool_Full_Audit_Prompt.md`, `newmkhanschool_advanced_upgrade_prompt.md`, etc.) — yeh shायد previous AI sessions ke detailed plans hain jo overlap kar sakte hain is file se. In sab ko ek baar review karke duplicate kaam se bachen.
- Database mein `school_id` har table mein hai — multi-school/multi-branch architecture support karta hai, lekin UI mein branch-switching/filtering confirm karen sab jagah implemented hai.
