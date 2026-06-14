# NewMkhanSchool — Deep Audit, Cleanup & Feature Suggestions
**Audit Date:** 2026-06-14  
**Database:** `newschool` (MariaDB 10.4.32 / PHP 8.2)  
**Repo:** https://github.com/noormuhammad2k20-a11y/Newmkhanschool  
**Stack:** Laravel · PHP 8.2 · Blade · MySQL/MariaDB · dompdf · Chart.js

---

## CONSTRAINT: Theme/CSS/Layout unchanged rakhna hai — sirf backend + DB fix karna hai.

---

## PART 1 — UNWANTED / DEAD TABLES (DROP KAREIN)

Ye tables database mein hain lekin koi portal inhe use nahi karta. Zero rows hain, koi active UI nahi, ya duplicate logic hai.

### 1.1 — IMMEDIATELY DROP (Safe — koi dependency nahi)

```sql
-- Old-style payroll table (replaced by `payrolls`)
DROP TABLE IF EXISTS `payroll`;

-- Old teacher leave table (replaced by `teacher_leave_requests`)
DROP TABLE IF EXISTS `teacher_leaves`;

-- Multi-branch system — aap single-school use kar rahe ho
DROP TABLE IF EXISTS `branch_admins`;
DROP TABLE IF EXISTS `branch_settings`;
DROP TABLE IF EXISTS `school_branches`;

-- AI prediction tables — implement nahi hua
DROP TABLE IF EXISTS `ai_predictions`;
DROP TABLE IF EXISTS `attendance_anomalies`;
DROP TABLE IF EXISTS `attendance_patterns`;

-- Enterprise/unused features
DROP TABLE IF EXISTS `student_badges`;
DROP TABLE IF EXISTS `student_portfolios`;
DROP TABLE IF EXISTS `portfolio_items`;
DROP TABLE IF EXISTS `report_card_narratives`;

-- Online exam system (alag se hai — quizzes system already kaam kar raha hai)
-- Note: Agar online_exams feature future mein chahiye to DROP mat karo
DROP TABLE IF EXISTS `exam_answers`;
DROP TABLE IF EXISTS `exam_attempts`;
DROP TABLE IF EXISTS `exam_questions`;
DROP TABLE IF EXISTS `online_exams`;

-- Inventory categories alag table bana di but inventory table mein `category` VARCHAR column pehle se hai
DROP TABLE IF EXISTS `inventory_categories`;

-- Seating feature — plan bana but assignments kabhi nahi bhari
DROP TABLE IF EXISTS `seating_assignments`;
DROP TABLE IF EXISTS `seating_plans`;

-- Substitute teacher feature — UI nahi bani abhi
DROP TABLE IF EXISTS `substitute_assignments`;

-- Teacher leave balance — tracking nahi ho rahi, requests bhi empty hain
DROP TABLE IF EXISTS `teacher_leave_balances`;
DROP TABLE IF EXISTS `teacher_leave_requests`; -- OLD `teacher_leaves` mein data hai, yahan nahi

-- Old fee_payments (replaced by fee_payment_transactions)
DROP TABLE IF EXISTS `fee_payments`;
```

### 1.2 — CONFIRM KARKE DROP (Data hai lekin possibly unused)

```sql
-- messages table: 0 rows — messaging feature implement nahi hua
DROP TABLE IF EXISTS `messages`;

-- student_promotions: 0 rows — promotion module pending
DROP TABLE IF EXISTS `student_promotions`;
```

---

## PART 2 — CRITICAL BUGS & FIXES

### BUG-01: Duplicate Migration — `tax_slips` Table
**Severity: CRITICAL**  
Do migrations ek hi table banati hain:
- Migration #38: `2026_06_11_202713_create_tax_slips_table`
- Migration #48: `2026_06_12_173544_create_tax_slips_table`

Fresh `php artisan migrate` pe **ERROR** aayega.

**Fix:** Migration #38 ka file delete/rename karo, ya uska `up()` method empty karo aur `Schema::dropIfExists` nahi:

```php
// database/migrations/2026_06_11_202713_create_tax_slips_table.php
public function up(): void
{
    // Intentionally empty — tax_slips is created in a later migration
}
public function down(): void {}
```

---

### BUG-02: `student_attendances` Orphan Records (FK Violation)
**Severity: HIGH**

`student_attendances` table mein in student IDs ki rows hain jo `students` table mein **exist nahi karti**:
- Student IDs: `69, 70, 71, 72, 73, 79, 80, 81, 82, 83, 94, 95, 96, 97, 98`
- Ye purana test data hai jab students ka data alag tha.

**Fix:**
```sql
DELETE FROM student_attendances 
WHERE student_id NOT IN (SELECT id FROM students);
```

---

### BUG-03: `teacher_module_access` Orphan Records
**Severity: MEDIUM**  
`teacher_module_access` mein teacher IDs `3, 9, 10, 23` ki entries hain — lekin `teachers` table mein sirf IDs `1–5` hain. Teacher IDs 9, 10, 23 exist nahi karti. Yahan **koi FK constraint nahi** isliye error nahi aaya lekin data garbage hai.

**Fix:**
```sql
DELETE FROM teacher_module_access 
WHERE teacher_id NOT IN (SELECT id FROM teachers);

-- Phir FK add karo (already defined in migration but verify karo):
ALTER TABLE teacher_module_access 
  DROP FOREIGN KEY IF EXISTS tma_teacher_id_fk,
  ADD CONSTRAINT tma_teacher_id_fk 
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE;
```

---

### BUG-04: `schools` Table — Duplicate Columns + Empty `code` UNIQUE Issue
**Severity: HIGH**

`schools` table mein DUPLICATE columns hain jo alag-alag migrations mein add hue:
- `phone_number` (purana) + `phone` (naya) — same cheez, dono hain
- `logo_path` (purana) + `logo` (naya) — same cheez, dono hain

Aur school `code` field empty string `''` hai jo UNIQUE constraint ke saath 2nd school create karne par conflict karega.

**Fix:**
```sql
-- Purane columns remove karo (pehle check karo koi view/query use na kare)
ALTER TABLE schools 
  DROP COLUMN IF EXISTS phone_number,
  DROP COLUMN IF EXISTS logo_path,
  DROP COLUMN IF EXISTS branch_code,
  DROP COLUMN IF EXISTS parent_school_id,
  DROP COLUMN IF EXISTS is_main_branch;

-- School code fix karo
UPDATE schools SET code = 'SCH001' WHERE id = 1 AND code = '';
```

---

### BUG-05: `timetables` Table — Dual Column Confusion
**Severity: MEDIUM**

`timetables` mein ye duplicate columns hain:
- `section_id` (INT, no FK) + `section_id_ref` (INT, FK to sections) — dono same cheez
- `subject` (VARCHAR text) + `subject_id_ref` (INT, FK to subjects) — dono same cheez

Blade views mein koi column use karta hai koi doosra — inconsistency hai.

**Fix:** 
```sql
-- section_id aur subject text columns ko drop karo (ref wale FKs ke saath hain)
ALTER TABLE timetables 
  DROP COLUMN IF EXISTS section_id,
  DROP COLUMN IF EXISTS subject;
-- Phir codebase mein saari queries update karo section_id_ref aur subject_id_ref use karne ke liye
```

---

### BUG-06: `marks` Table — `exam_schedule_id` Missing FK Constraint
**Severity: MEDIUM**

`marks.exam_schedule_id` column hai lekin **koi FOREIGN KEY constraint define nahi** hai. Isliye invalid `exam_schedule_id` values enter ho sakti hain bina error ke.

**Fix:**
```sql
ALTER TABLE marks 
  ADD CONSTRAINT marks_exam_schedule_id_foreign 
    FOREIGN KEY (exam_schedule_id) REFERENCES exam_schedules(id) ON DELETE SET NULL;
```

---

### BUG-07: `fee_categories` + `fee_structures` — `school_id` Always NULL
**Severity: MEDIUM**

Database mein saari fee categories aur fee structures ka `school_id = NULL` hai. FK constraint bhi defined hai but value kabhi set nahi hoti. Matlab multi-school me saari schools ki fees share ho rahi hain.

**Fix in Controller:**
```php
// FeeCategory store mein:
$data['school_id'] = auth()->user()->school_id ?? 1;
// FeeStructure store mein bhi same
```

---

### BUG-08: `users` Table — 2 Ghost Students Without `school_id`
**Severity: LOW**

Users 184 (`test@test.com`) aur 185 (`testing@gmail.com`) hain jinka `school_id = NULL` hai aur students table mein linked student nahi. Ye test accounts hain.

**Fix:**
```sql
DELETE FROM users WHERE id IN (184, 185);
```

---

### BUG-09: `payroll` (Old) Still Has Active FK — Data Inconsistency
**Severity: MEDIUM**

Old `payroll` table mein data hai (EMP-0014 Eleanor Rigby etc.) jo fake/test data hai. New `payrolls` table mein proper data hai (Aisha Syed, June 2026). Dono tables exist hone se Accountant Portal confusion mein hai — koi ek use karo.

**Fix:** Upar BUG-01 mein old `payroll` drop ka plan diya hai. Pehle ensure karo `payrolls` model ka Controller sahi hai.

---

### BUG-10: `library_books` — Duplicate Entries
**Severity: LOW**

Library mein same books twice hain:
- "The Great Gatsby" by F. Scott Fitzgerald — IDs 1 and 4
- "Introduction to Algorithms" by Thomas H. Cormen — IDs 2 and 5
- "Organic Chemistry" by Paula Yurkanis Bruice — IDs 3 and 6

**Fix:**
```sql
DELETE FROM library_books WHERE id IN (4, 5, 6);
```

---

## PART 3 — PORTAL ISSUES (Kya Kaam Nahi Kar Raha)

### Teacher Portal
| Issue | Status | Detail |
|-------|--------|--------|
| Leave system dual tables | BROKEN | Code kisi ek mein submit karta hai doosri empty rehti hai |
| Seating Plan assignments | INCOMPLETE | Plans bane hain lekin koi student assign nahi |
| Digital Notes | PARTIAL | Sirf 1 note upload hua — feature works but hidden |
| Quiz system | WORKING ✓ | Quizzes, questions, attempts sab data hai |

### Student Portal
| Issue | Status | Detail |
|-------|--------|--------|
| Student Badges | NOT BUILT | Table hai but koi badge nahi mila kisi ko |
| Student Portfolio | NOT BUILT | Table hai, koi data nahi |
| Online Exam vs Quiz | CONFUSION | Ye dono alag systems hain — student kahan jaye? |
| Leave approval visibility | PARTIAL | Request submit hoti hai but status change ka flow unclear |

### Parent Portal
| Issue | Status | Detail |
|-------|--------|--------|
| Fee payment view | PARTIAL | Parent fee dekh sakta hai but receipts nahi download hoti |
| Messages | NOT BUILT | Table empty hai — parent-teacher messaging nahi hai |
| Attendance chart | DATA ISSUE | Orphan attendance records (BUG-02) chart tod sakte hain |

### Accountant Portal
| Issue | Status | Detail |
|-------|--------|--------|
| Bank Accounts | NOT CONFIGURED | `bank_accounts` table empty — ledger bhi kaam nahi karega |
| Ledger | EMPTY | `ledger_entries` = 0 rows — fee payments ledger mein nahi ja rahi |
| Expenses | EMPTY | `expenses` + `expense_categories` dono empty |
| Payroll Dual Table | CONFUSED | Old + new payroll table dono mein data split hai |
| JazzCash Integration | BROKEN | Transaction #1 ka response: "Please provide a valid value for pp_MerchantID" — sandbox creds wrong hain |

### Admin Portal
| Issue | Status | Detail |
|-------|--------|--------|
| School Code empty | BUG | `schools.code = ''` — multi-school add nahi hoga |
| Promotion Rules | HALF-DONE | 1 rule bana but `student_promotions` empty — bulk promotion feature nahi chala |
| Report Cards | NOT GENERATED | `report_cards` table empty — PDF generation nahi hua |

---

## PART 4 — SUGGESTIONS (Last Mein — Jo Pasand Aaye Rakho, Baki Remove Karo)

Ye suggestions current active portals ke context mein hain. Theme/CSS unchanged rahega.

---

### S-01: Teacher Portal — Attendance Pattern Summary Card
Har teacher ke dashboard par ek simple card: "Is mahine X students ne 3+ din ghairhazri di" — `student_attendances` table se direct query, koi AI nahi.

---

### S-02: Student Portal — My Progress Timeline
Student apna marks history dekhe — current data already `marks` table mein hai. Ek simple Chart.js line chart per subject, exam type filter ke saath.

---

### S-03: Parent Portal — PDF Receipt Download
`fee_receipts` table mein already receipt data hai. Parent ko sirf ek "Download Receipt" button chahiye — dompdf se 5-minute ka kaam.

---

### S-04: Admin Portal — Student Promotion Bulk Action
`promotion_rules` table already hai. Ek button: "Academic Year End — Promote All Students" jo:
1. `marks` se check kare passing criteria
2. `student_promotions` mein log kare
3. `students.current_class_id` update kare

---

### S-05: Accountant Portal — Auto Ledger Entry on Fee Payment
Jab `fee_payment_transactions.status = 'Success'` ho to automatically ek `ledger_entries` row bane (Income type). Ye `Observer` ya event listener se karna easy hai.

---

### S-06: Admin Portal — Section-wise Report Card Generation
`report_cards` table abhi empty hai. Admin ke paas ek screen ho: class select karo → exam type select karo → "Generate All Report Cards" button → dompdf se bulk PDF. Marks data pehle se `marks` table mein hai.

---

### S-07: Teacher Portal — Leave Request Cleanup (One System Only)
`teacher_leaves` (old, has data) aur `teacher_leave_requests` (new, empty) dono hain. Ek ko choose karo:
- `teacher_leave_requests` rakho (zyada fields hain — reason, substitute_assigned, etc.)
- Old `teacher_leaves` ka data migrate karo
- Portal sirf `teacher_leave_requests` use kare

---

### S-08: Student Portal — Online Quiz Result Card
`quiz_attempts` mein result hai (score, percentage). Student ke portal par ek "My Quiz Results" tab — simple table with quiz title, date, score, percentage. Data pehle se hai, sirf view banana hai.

---

### S-09: Admin Portal — Timetable Column Cleanup
`timetables` table mein `section_id` + `section_id_ref` dono hain (BUG-05). Ek cleanup migration do aur Blade views unified column use karen. Admin timetable edit page ke liye consistent data ensure ho.

---

### S-10: Parent Portal — Announcements Bell (Real Notification)
`notifications` table already hai with `user_id`, `title`, `body`, `is_read`. Parent portal mein sidebar par notification bell icon aur unread count badge. Click karne par mark-as-read. Ye `notifications` model se seedha query hai.

---

## IMPLEMENTATION ORDER (Agar sab karna ho)

```
Phase 0 — Cleanup (1-2 hours)
  ├── BUG-01: Dead tables DROP
  ├── BUG-02: Orphan attendance DELETE
  ├── BUG-03: Orphan teacher_module_access DELETE
  ├── BUG-08: Ghost user DELETE
  └── BUG-10: Duplicate library books DELETE

Phase 1 — Schema Fixes (2-3 hours)
  ├── BUG-04: schools duplicate columns + code fix
  ├── BUG-05: timetables dual column unification
  ├── BUG-06: marks FK add
  └── BUG-01 migration: tax_slips duplicate fix

Phase 2 — Logic Fixes (3-4 hours)
  ├── BUG-07: fee_categories school_id set in controller
  ├── BUG-09: payroll consolidation to `payrolls` only
  └── Teacher leave — one system choose

Phase 3 — Feature Completions (as needed)
  └── Suggestions S-01 through S-10 (pick any)
```

---

## DATABASE TABLE COUNT SUMMARY

| Category | Tables |
|----------|--------|
| Core (keep) | `users`, `roles`, `permissions`, `role_permissions`, `schools`, `classes`, `sections`, `subjects`, `students`, `teachers`, `academic_years` |
| Active Features | `assignments`, `assignment_submissions`, `ai_grading_results`, `student_attendances`, `teacher_attendances`, `marks`, `exam_schedules`, `exam_types`, `fees`, `fee_categories`, `fee_structures`, `fee_payment_transactions`, `fee_receipts`, `quizzes`, `quiz_questions`, `quiz_attempts`, `quiz_answers`, `digital_notes`, `timetables`, `timetable_versions`, `teacher_assignments`, `teacher_module_access`, `teacher_leaves` *(fix to leave_requests)*, `parent_students`, `student_leave_requests`, `announcements`, `notifications`, `events`, `library_books`, `library_issues`, `transport_routes`, `transport_students`, `hostel_rooms`, `hostel_assignments`, `inventory`, `inventory_transactions`, `inventory_purchases`, `payrolls`, `tax_slips`, `ledger_entries`, `bank_accounts`, `expenses`, `expense_categories`, `issued_documents`, `document_templates`, `health_records`, `audit_logs`, `report_cards`, `promotion_rules`, `seating_plans`, `teacher_leave_requests` |
| **DROP (dead/duplicate)** | `payroll`, `teacher_leaves` *(old)*, `branch_admins`, `branch_settings`, `school_branches`, `ai_predictions`, `attendance_anomalies`, `attendance_patterns`, `student_badges`, `student_portfolios`, `portfolio_items`, `report_card_narratives`, `exam_answers`, `exam_attempts`, `exam_questions`, `online_exams`, `inventory_categories`, `seating_assignments`, `substitute_assignments`, `teacher_leave_balances`, `fee_payments`, `messages` |

**Total DROP: ~22 tables**

---

*Ye prompt Antigravity se run karna hai. Pehle Phase 0 + Phase 1 karo, phir test karo, phir Phase 2 aur 3.*
