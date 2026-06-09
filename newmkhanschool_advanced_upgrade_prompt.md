# Newmkhanschool — Advanced Features Upgrade Prompt
**System:** Laravel (PHP 8.2) · MariaDB 10.4 · Existing Theme Must Be Preserved  
**Repo:** https://github.com/noormuhammad2k20-a11y/Newmkhanschool  
**Database:** newschool

---

## ⚠️ ABSOLUTE RULE — THEME PROTECTION (READ BEFORE EVERYTHING ELSE)

> **DO NOT change, remove, touch, or override ANY existing theme, CSS classes, layout structure, sidebar, navbar, card styles, button styles, badge patterns, or ANY design element.**
>
> - Every new page MUST use `@extends('layouts.app')` (or whatever master layout already exists)
> - Every new page MUST copy the exact same sidebar partial, card HTML, table HTML, button classes, badge classes already used in existing admin pages
> - When in doubt about ANY styling: open an existing working page and copy it exactly
> - DO NOT install any new CSS framework or UI kit
> - DO NOT change any existing Blade view that already works
> - New controllers, migrations, routes, and views must be ADDITIONS only — never replacements

---

## PHASE A — DATABASE MIGRATIONS (Run in Order)

### A.1 — Online Fee Payment Tables

```sql
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
```

### A.2 — Student Promotion Engine Tables

```sql
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
```

### A.3 — Multi-Branch Management Tables

```sql
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
```

### A.4 — Online Exam / Quiz Module Tables

```sql
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
```

### A.5 — Document Management Tables

```sql
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
```

### A.6 — Staff Leave & Substitute Management Tables

```sql
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
```

### A.7 — Seed Document Templates

```sql
INSERT INTO document_templates (name, slug, content, variables) VALUES
('Transfer Certificate', 'transfer-certificate',
'<h2 style="text-align:center">{{school_name}}</h2>
<h3 style="text-align:center">TRANSFER CERTIFICATE</h3>
<p>This is to certify that <strong>{{student_name}}</strong> Son/Daughter of <strong>{{father_name}}</strong>,
bearing Admission No. <strong>{{admission_no}}</strong>, was a student of this institution from
<strong>{{admission_date}}</strong> to <strong>{{leaving_date}}</strong>.</p>
<p>He/She was studying in Class <strong>{{class_name}}</strong> at the time of leaving.</p>
<p>His/Her character and conduct was <strong>Good</strong> during his/her stay in this institution.</p>
<p>No dues are pending against him/her.</p>
<p style="margin-top:60px">Principal Signature: _______________________</p>',
'school_name,student_name,father_name,admission_no,admission_date,leaving_date,class_name'),

('Character Certificate', 'character-certificate',
'<h2 style="text-align:center">{{school_name}}</h2>
<h3 style="text-align:center">CHARACTER CERTIFICATE</h3>
<p>This is to certify that <strong>{{student_name}}</strong> Son/Daughter of <strong>{{father_name}}</strong>,
bearing Admission No. <strong>{{admission_no}}</strong>, studied in Class <strong>{{class_name}}</strong>
during the academic session <strong>{{academic_year}}</strong>.</p>
<p>His/Her character and conduct was <strong>Excellent</strong> throughout his/her academic career.
He/She was an honest, hardworking, and disciplined student.</p>
<p>We wish him/her all the best in his/her future endeavors.</p>
<p style="margin-top:60px">Principal Signature: _______________________</p>',
'school_name,student_name,father_name,admission_no,class_name,academic_year'),

('Bonafide Certificate', 'bonafide-certificate',
'<h2 style="text-align:center">{{school_name}}</h2>
<h3 style="text-align:center">BONAFIDE CERTIFICATE</h3>
<p>This is to certify that <strong>{{student_name}}</strong> Son/Daughter of <strong>{{father_name}}</strong>,
Resident of <strong>{{address}}</strong>, bearing Admission No. <strong>{{admission_no}}</strong>,
is currently a bonafide student of this institution studying in Class <strong>{{class_name}}</strong>,
Session <strong>{{academic_year}}</strong>.</p>
<p>This certificate is issued for the purpose of <strong>{{purpose}}</strong>.</p>
<p style="margin-top:60px">Principal Signature: _______________________</p>',
'school_name,student_name,father_name,address,admission_no,class_name,academic_year,purpose');
```

---

## PHASE B — LARAVEL CONTROLLERS

### B.1 — JazzCash / EasyPaisa Fee Payment Controller

**File:** `app/Http/Controllers/Admin/OnlineFeePaymentController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\FeePaymentTransaction;
use App\Models\FeeReceipt;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class OnlineFeePaymentController extends Controller
{
    // Show payment initiation form
    public function initiatePayment(Request $request, $feeId)
    {
        $fee = Fee::with('student.user')->findOrFail($feeId);

        // Ensure school_id scoping
        abort_unless($fee->student->school_id == auth()->user()->school_id
            || auth()->user()->hasRole('Super Admin'), 403);

        return view('admin.fees.initiate-payment', compact('fee'));
    }

    // Process JazzCash payment
    public function processJazzCash(Request $request, $feeId)
    {
        $request->validate([
            'mobile_number' => 'required|regex:/^03[0-9]{9}$/',
            'cnic_last6'    => 'required|digits:6',
        ]);

        $fee = Fee::findOrFail($feeId);

        // Create pending transaction
        $txn = FeePaymentTransaction::create([
            'fee_id'          => $fee->id,
            'student_id'      => $fee->student_id,
            'gateway'         => 'JazzCash',
            'transaction_ref' => 'JC-' . strtoupper(Str::random(12)),
            'amount'          => $fee->amount - ($fee->paid_amount ?? 0),
            'status'          => 'Pending',
        ]);

        // ---------------------------------------------------------------
        // JAZZCASH MWALLET API INTEGRATION
        // Replace these config values in your .env:
        //   JAZZCASH_MERCHANT_ID=your_merchant_id
        //   JAZZCASH_PASSWORD=your_password
        //   JAZZCASH_INTEGRITY_SALT=your_salt
        //   JAZZCASH_ENV=sandbox  (change to live for production)
        // ---------------------------------------------------------------
        $merchantId   = config('services.jazzcash.merchant_id');
        $password     = config('services.jazzcash.password');
        $integritySalt= config('services.jazzcash.integrity_salt');
        $env          = config('services.jazzcash.env', 'sandbox');
        $apiUrl       = $env === 'live'
            ? 'https://payments.jazzcash.com.pk/ApplicationAPI/API/2.0/Purchase/DoMWalletTransaction'
            : 'https://sandbox.jazzcash.com.pk/ApplicationAPI/API/2.0/Purchase/DoMWalletTransaction';

        $dateTime   = now()->format('YmdHis');
        $txnRefNo   = $txn->transaction_ref;
        $amount     = number_format($txn->amount * 100, 0, '.', ''); // paisas

        // Build hash string (PP_Amount|PP_BillReference|PP_CNIC|PP_Description|PP_Language|
        //                     PP_MerchantID|PP_MobileNumber|PP_Password|PP_TxnCurrency|
        //                     PP_TxnDateTime|PP_TxnExpiryDateTime|PP_TxnRefNo|PP_TxnType|PP_Version)
        $hashString = implode('&', [
            $integritySalt, $amount, "FEE-{$fee->id}",
            $request->cnic_last6, "School Fee Payment",
            'EN', $merchantId, $request->mobile_number, $password,
            'PKR', $dateTime, now()->addHours(1)->format('YmdHis'), $txnRefNo, 'MWALLET', '1.1',
        ]);
        $secureHash = hash_hmac('sha256', $hashString, $integritySalt);

        $postData = [
            'pp_Version'            => '1.1',
            'pp_TxnType'            => 'MWALLET',
            'pp_Language'           => 'EN',
            'pp_MerchantID'         => $merchantId,
            'pp_Password'           => $password,
            'pp_TxnRefNo'           => $txnRefNo,
            'pp_Amount'             => $amount,
            'pp_TxnCurrency'        => 'PKR',
            'pp_TxnDateTime'        => $dateTime,
            'pp_TxnExpiryDateTime'  => now()->addHours(1)->format('YmdHis'),
            'pp_BillReference'      => "FEE-{$fee->id}",
            'pp_Description'        => 'School Fee Payment',
            'pp_TxnType'            => 'MWALLET',
            'pp_MobileNumber'       => $request->mobile_number,
            'pp_CNIC'               => $request->cnic_last6,
            'ppmpf_1'               => '',
            'pp_SecureHash'         => $secureHash,
        ];

        // Make API call
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        $txn->update(['gateway_response' => $response]);

        if (isset($result['pp_ResponseCode']) && $result['pp_ResponseCode'] === '000') {
            // SUCCESS
            $txn->update(['status' => 'Success', 'paid_at' => now()]);
            $fee->update([
                'paid_amount' => ($fee->paid_amount ?? 0) + $txn->amount,
                'status'      => 'Paid',
            ]);
            $receipt = $this->generateReceipt($txn);
            return redirect()->route('admin.fees.index')
                ->with('success', "Payment successful! Receipt No: {$receipt->receipt_no}");
        } else {
            $txn->update(['status' => 'Failed']);
            $errMsg = $result['pp_ResponseMessage'] ?? 'Payment failed. Please try again.';
            return back()->with('error', $errMsg);
        }
    }

    // Process EasyPaisa payment
    public function processEasyPaisa(Request $request, $feeId)
    {
        $request->validate([
            'mobile_number' => 'required|regex:/^03[0-9]{9}$/',
        ]);

        $fee = Fee::findOrFail($feeId);

        $txn = FeePaymentTransaction::create([
            'fee_id'          => $fee->id,
            'student_id'      => $fee->student_id,
            'gateway'         => 'EasyPaisa',
            'transaction_ref' => 'EP-' . strtoupper(Str::random(12)),
            'amount'          => $fee->amount - ($fee->paid_amount ?? 0),
            'status'          => 'Pending',
        ]);

        // ---------------------------------------------------------------
        // EASYPAISA REST API INTEGRATION
        // Add to .env:
        //   EASYPAISA_STORE_ID=your_store_id
        //   EASYPAISA_HASH_KEY=your_hash_key
        //   EASYPAISA_ENV=sandbox
        // ---------------------------------------------------------------
        $storeId    = config('services.easypaisa.store_id');
        $hashKey    = config('services.easypaisa.hash_key');
        $env        = config('services.easypaisa.env', 'sandbox');
        $apiUrl     = $env === 'live'
            ? 'https://easypaisa.com.pk/easypay/Index.jsf'
            : 'https://easypaisa.com.pk/easypay-sandbox/Index.jsf';

        $orderId    = $txn->transaction_ref;
        $amount     = number_format($txn->amount, 2);
        $dateTime   = now()->format('Ymd His');

        // EasyPaisa hash: storeId+amount+orderId+mobileAccountNo+emailAddress+
        //                 expiryDate+txnType+bankID+postBackURL+autoRedirect+hashKey
        $hashStr    = "{$storeId}{$amount}{$orderId}{$request->mobile_number}"
                    . "no-reply@school.com" . now()->addDay()->format('Ymd His')
                    . "MA" . "" . route('admin.fees.easypaisa.callback') . "0{$hashKey}";
        $hash       = strtoupper(hash('sha256', $hashStr));

        $postData = [
            'storeId'           => $storeId,
            'amount'            => $amount,
            'postBackURL'       => route('admin.fees.easypaisa.callback'),
            'orderRefNum'       => $orderId,
            'mobileNum'         => $request->mobile_number,
            'emailAddress'      => 'no-reply@school.com',
            'txnType'           => 'MA',
            'bankID'            => '',
            'expiryDate'        => now()->addDay()->format('Ymd His'),
            'autoRedirect'      => '0',
            'checkSum'          => $hash,
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $txn->update(['gateway_response' => $response]);
        $result = json_decode($response, true);

        if (isset($result['status']) && $result['status'] === 'Paid') {
            $txn->update(['status' => 'Success', 'paid_at' => now()]);
            $fee->update([
                'paid_amount' => ($fee->paid_amount ?? 0) + $txn->amount,
                'status'      => 'Paid',
            ]);
            $receipt = $this->generateReceipt($txn);
            return redirect()->route('admin.fees.index')
                ->with('success', "Payment successful! Receipt No: {$receipt->receipt_no}");
        } else {
            $txn->update(['status' => 'Failed']);
            return back()->with('error', 'EasyPaisa payment failed. Please try again.');
        }
    }

    // EasyPaisa callback
    public function easyPaisaCallback(Request $request)
    {
        $txn = FeePaymentTransaction::where('transaction_ref', $request->orderRefNum)->first();
        if ($txn && $request->status === 'Paid') {
            $txn->update(['status' => 'Success', 'paid_at' => now()]);
            $txn->fee->update(['status' => 'Paid', 'paid_amount' => $txn->amount]);
            $this->generateReceipt($txn);
        }
        return response()->json(['status' => 'ok']);
    }

    // Download receipt PDF
    public function downloadReceipt($receiptId)
    {
        $receipt = FeeReceipt::with(['student', 'fee', 'transaction'])->findOrFail($receiptId);
        $pdf = Pdf::loadView('admin.fees.receipt-pdf', compact('receipt'));
        return $pdf->download("receipt_{$receipt->receipt_no}.pdf");
    }

    // Internal: generate receipt record + PDF
    private function generateReceipt(FeePaymentTransaction $txn): FeeReceipt
    {
        $receiptNo = 'RCP-' . now()->format('Ymd') . '-' . str_pad($txn->id, 5, '0', STR_PAD_LEFT);
        $receipt   = FeeReceipt::create([
            'receipt_no'     => $receiptNo,
            'transaction_id' => $txn->id,
            'student_id'     => $txn->student_id,
            'fee_id'         => $txn->fee_id,
            'amount'         => $txn->amount,
        ]);
        return $receipt;
    }
}
```

---

### B.2 — Student Promotion Engine Controller

**File:** `app/Http/Controllers/Admin/StudentPromotionController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Mark;
use App\Models\StudentAttendance;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\PromotionRule;
use App\Models\StudentPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPromotionController extends Controller
{
    // Step 1: Select academic year and class to promote from
    public function index()
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $classes       = SchoolClass::orderBy('name')->get();
        $activeYear    = AcademicYear::where('is_active', 1)->first();
        $rules         = PromotionRule::with(['fromClass','toClass'])
                           ->where('academic_year_id', $activeYear?->id)->get();
        return view('admin.promotions.index', compact('academicYears','classes','activeYear','rules'));
    }

    // Step 2: Preview which students pass / fail before committing
    public function preview(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id'         => 'required|exists:classes,id',
        ]);

        $academicYear = AcademicYear::findOrFail($request->academic_year_id);
        $class        = SchoolClass::findOrFail($request->class_id);
        $rule         = PromotionRule::where('from_class_id', $class->id)
                          ->where('academic_year_id', $academicYear->id)->first();

        $students = Student::with(['currentSection'])
            ->where('current_class_id', $class->id)
            ->where('status', 'Active')
            ->get();

        $results = $students->map(function ($student) use ($academicYear, $rule) {
            // Calculate total marks percentage
            $marks = Mark::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)->get();

            $totalObtained = $marks->sum('marks_obtained');
            $totalMax      = $marks->sum('total_marks');
            $marksPct      = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 1) : 0;

            // Calculate attendance percentage
            $totalDays   = StudentAttendance::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)->count();
            $presentDays = StudentAttendance::where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('status', 'P')->count();
            $attendPct   = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

            $minMarks  = $rule?->min_percentage ?? 40;
            $minAttend = $rule?->min_attendance_pct ?? 75;

            $passesMarks   = $marksPct >= $minMarks;
            $passesAttend  = $attendPct >= $minAttend;
            $isEligible    = $passesMarks && $passesAttend;

            return (object)[
                'student'         => $student,
                'marks_pct'       => $marksPct,
                'attendance_pct'  => $attendPct,
                'passes_marks'    => $passesMarks,
                'passes_attend'   => $passesAttend,
                'is_eligible'     => $isEligible,
                'to_class_id'     => $rule?->to_class_id,
            ];
        });

        $nextClasses = SchoolClass::orderBy('name')->get();

        return view('admin.promotions.preview', compact(
            'results', 'class', 'academicYear', 'rule', 'nextClasses'
        ));
    }

    // Step 3: Execute bulk promotion
    public function execute(Request $request)
    {
        $request->validate([
            'academic_year_id'   => 'required|exists:academic_years,id',
            'from_class_id'      => 'required|exists:classes,id',
            'to_class_id'        => 'required|exists:classes,id',
            'student_ids'        => 'required|array',
            'student_ids.*'      => 'exists:students,id',
            'default_section_id' => 'required|exists:sections,id',
        ]);

        DB::beginTransaction();
        try {
            $promoted = 0;
            foreach ($request->student_ids as $studentId) {
                $student = Student::findOrFail($studentId);

                StudentPromotion::create([
                    'student_id'       => $student->id,
                    'academic_year_id' => $request->academic_year_id,
                    'from_class_id'    => $student->current_class_id,
                    'from_section_id'  => $student->current_section_id,
                    'to_class_id'      => $request->to_class_id,
                    'to_section_id'    => $request->default_section_id,
                    'promotion_type'   => 'Promoted',
                    'promoted_by'      => auth()->id(),
                    'remarks'          => 'Bulk promotion by admin',
                ]);

                $student->update([
                    'current_class_id'   => $request->to_class_id,
                    'current_section_id' => $request->default_section_id,
                ]);

                $promoted++;
            }

            DB::commit();
            return redirect()->route('admin.promotions.index')
                ->with('success', "{$promoted} students promoted successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Promotion failed: ' . $e->getMessage());
        }
    }

    // Manage promotion rules (passing criteria per class)
    public function rules()
    {
        $classes      = SchoolClass::orderBy('name')->get();
        $academicYear = AcademicYear::where('is_active', 1)->first();
        $rules        = PromotionRule::with(['fromClass','toClass'])
                          ->where('academic_year_id', $academicYear?->id)->get();
        return view('admin.promotions.rules', compact('classes','rules','academicYear'));
    }

    public function saveRule(Request $request)
    {
        $request->validate([
            'from_class_id'        => 'required|exists:classes,id',
            'to_class_id'          => 'required|exists:classes,id',
            'min_percentage'       => 'required|numeric|min:0|max:100',
            'min_attendance_pct'   => 'required|numeric|min:0|max:100',
            'academic_year_id'     => 'required|exists:academic_years,id',
        ]);

        PromotionRule::updateOrCreate(
            [
                'from_class_id'    => $request->from_class_id,
                'academic_year_id' => $request->academic_year_id,
            ],
            [
                'to_class_id'          => $request->to_class_id,
                'min_percentage'       => $request->min_percentage,
                'min_attendance_pct'   => $request->min_attendance_pct,
            ]
        );

        return back()->with('success', 'Promotion rule saved.');
    }
}
```

---

### B.3 — Multi-Branch Controller

**File:** `app/Http/Controllers/Admin/BranchController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolBranch;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Fee;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Super Admin');
    }

    public function index()
    {
        $branches = SchoolBranch::withCount(['students','teachers'])->get();

        // Summary stats across all branches
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalRevenue  = Fee::where('status','Paid')->sum('paid_amount');

        return view('admin.branches.index', compact('branches','totalStudents','totalTeachers','totalRevenue'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:200',
            'code'           => 'required|string|max:20|unique:school_branches',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:150',
            'principal_name' => 'nullable|string|max:150',
            'logo'           => 'nullable|image|max:2048',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('branch-logos','public');
        }

        SchoolBranch::create($data);
        return redirect()->route('admin.branches.index')->with('success','Branch created successfully.');
    }

    public function show($id)
    {
        $branch = SchoolBranch::findOrFail($id);

        // Branch-specific stats
        $stats = [
            'students'         => Student::where('school_id', $id)->count(),
            'teachers'         => Teacher::where('school_id', $id)->count(),
            'revenue_this_month' => Fee::where('school_id', $id)
                ->where('status','Paid')
                ->whereMonth('created_at', now()->month)->sum('paid_amount'),
            'attendance_today' => StudentAttendance::where('date', today()->toDateString())
                ->whereHas('student', fn($q) => $q->where('school_id',$id))
                ->where('status','P')->count(),
        ];

        return view('admin.branches.show', compact('branch','stats'));
    }

    public function edit($id)
    {
        $branch = SchoolBranch::findOrFail($id);
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = SchoolBranch::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:20|unique:school_branches,code,'.$id,
        ]);

        $data = $request->except('logo');
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('branch-logos','public');
        }

        $branch->update($data);
        return redirect()->route('admin.branches.index')->with('success','Branch updated.');
    }

    public function switchBranch(Request $request)
    {
        // Allow super admin to switch context to a specific branch
        $request->validate(['branch_id' => 'required|exists:school_branches,id']);
        session(['active_branch_id' => $request->branch_id]);
        return back()->with('success','Switched to branch successfully.');
    }
}
```

---

### B.4 — Dynamic Fee Challan Controller

**File:** `app/Http/Controllers/Admin/FeeChallanController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Student;
use App\Models\FeeStructure;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class FeeChallanController extends Controller
{
    // Generate challan for a single student
    public function generate($studentId)
    {
        $student      = Student::with(['currentClass'])->findOrFail($studentId);
        $academicYear = AcademicYear::where('is_active', 1)->first();

        $pendingFees = Fee::where('student_id', $student->id)
            ->whereIn('status', ['Pending','Overdue'])
            ->get();

        if ($pendingFees->isEmpty()) {
            return back()->with('info', 'No pending fees for this student.');
        }

        $challanData = [
            'student'      => $student,
            'fees'         => $pendingFees,
            'total_amount' => $pendingFees->sum('amount'),
            'challan_no'   => 'CHN-' . now()->format('Ymd') . '-' . str_pad($student->id, 5,'0',STR_PAD_LEFT),
            'due_date'     => now()->addDays(15)->format('d M Y'),
            'issued_date'  => now()->format('d M Y'),
            'school'       => $this->getSchoolSettings($student->school_id ?? 1),
            'qr_data'      => "STUDENT:{$student->admission_no}|AMOUNT:{$pendingFees->sum('amount')}|DATE:".now()->format('Ymd'),
            'academic_year'=> $academicYear,
        ];

        $pdf = Pdf::loadView('admin.fees.challan-pdf', $challanData)
            ->setPaper('a4', 'portrait');

        return $pdf->download("challan_{$student->admission_no}_{$challanData['challan_no']}.pdf");
    }

    // Bulk generate challans for an entire class
    public function bulkGenerate(Request $request)
    {
        $request->validate(['class_id' => 'required|exists:classes,id']);

        $students = Student::where('current_class_id', $request->class_id)
            ->where('status', 'Active')->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'No active students in this class.');
        }

        // For bulk, generate a ZIP file of PDFs or a single merged PDF
        // Here we do a summary table PDF
        $academicYear = AcademicYear::where('is_active', 1)->first();

        $studentFees = $students->map(function ($student) use ($academicYear) {
            $pending = Fee::where('student_id', $student->id)
                ->whereIn('status', ['Pending','Overdue'])->sum('amount');
            return ['student' => $student, 'pending' => $pending];
        })->filter(fn($s) => $s['pending'] > 0);

        $pdf = Pdf::loadView('admin.fees.bulk-challan-pdf', [
            'studentFees'  => $studentFees,
            'academicYear' => $academicYear,
            'class'        => \App\Models\SchoolClass::find($request->class_id),
            'generated_at' => now(),
        ])->setPaper('a4','portrait');

        return $pdf->download("bulk_challans_class_{$request->class_id}.pdf");
    }

    private function getSchoolSettings($schoolId): array
    {
        // Pull school info from schools/school_branches table or config
        return [
            'name'    => config('app.school_name', 'MKhan School'),
            'address' => config('app.school_address', 'School Address'),
            'phone'   => config('app.school_phone', ''),
            'logo'    => config('app.school_logo', ''),
        ];
    }
}
```

---

### B.5 — Online Exam Controller (Teacher Side)

**File:** `app/Http/Controllers/Teacher/OnlineExamController.php`

```php
<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\OnlineExam;
use App\Models\ExamQuestion;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Traits\TeacherScoped;
use Illuminate\Http\Request;

class OnlineExamController extends Controller
{
    use TeacherScoped;

    public function index()
    {
        $teacher = $this->getTeacher();
        $exams   = OnlineExam::where('teacher_id', $teacher->id)
            ->with(['subject','class_'])
            ->orderByDesc('exam_date')
            ->paginate(15);
        return view('teacher.online-exams.index', compact('exams'));
    }

    public function create()
    {
        $teacher  = $this->getTeacher();
        $classes  = SchoolClass::whereIn('id', $this->getAssignedClassIds($teacher))->get();
        $subjects = Subject::whereIn('id', $this->getAssignedSubjectIds($teacher))->get();
        return view('teacher.online-exams.create', compact('classes','subjects'));
    }

    public function store(Request $request)
    {
        $teacher = $this->getTeacher();
        $request->validate([
            'title'             => 'required|string|max:255',
            'subject_id'        => 'required|exists:subjects,id',
            'class_id'          => 'required|exists:classes,id',
            'exam_date'         => 'required|date',
            'start_time'        => 'required',
            'end_time'          => 'required|after:start_time',
            'duration_minutes'  => 'required|integer|min:5|max:480',
            'total_marks'       => 'required|integer|min:1',
            'passing_marks'     => 'required|integer|min:1|lte:total_marks',
            'instructions'      => 'nullable|string',
        ]);

        // Verify teacher is assigned to this class/subject
        abort_unless(
            $this->getAssignedClassIds($teacher)->contains($request->class_id) &&
            $this->getAssignedSubjectIds($teacher)->contains($request->subject_id), 403
        );

        $academicYear = AcademicYear::where('is_active', 1)->firstOrFail();

        $exam = OnlineExam::create([
            'title'                   => $request->title,
            'description'             => $request->description,
            'subject_id'              => $request->subject_id,
            'class_id'                => $request->class_id,
            'academic_year_id'        => $academicYear->id,
            'teacher_id'              => $teacher->id,
            'exam_date'               => $request->exam_date,
            'start_time'              => $request->start_time,
            'end_time'                => $request->end_time,
            'duration_minutes'        => $request->duration_minutes,
            'total_marks'             => $request->total_marks,
            'passing_marks'           => $request->passing_marks,
            'instructions'            => $request->instructions,
            'shuffle_questions'       => $request->boolean('shuffle_questions', true),
            'show_result_immediately' => $request->boolean('show_result_immediately'),
            'status'                  => 'Draft',
        ]);

        return redirect()->route('teacher.online-exams.questions', $exam->id)
            ->with('success', 'Exam created. Now add questions.');
    }

    public function questions($examId)
    {
        $teacher  = $this->getTeacher();
        $exam     = OnlineExam::where('id', $examId)->where('teacher_id', $teacher->id)->firstOrFail();
        $questions = ExamQuestion::where('exam_id', $exam->id)->orderBy('order_no')->get();
        return view('teacher.online-exams.questions', compact('exam','questions'));
    }

    public function storeQuestion(Request $request, $examId)
    {
        $teacher = $this->getTeacher();
        $exam    = OnlineExam::where('id', $examId)->where('teacher_id', $teacher->id)->firstOrFail();

        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:MCQ,True/False,Short',
            'marks'         => 'required|integer|min:1',
            'correct_answer'=> 'required_if:question_type,MCQ,True/False',
        ]);

        ExamQuestion::create([
            'exam_id'        => $exam->id,
            'question_text'  => $request->question_text,
            'question_type'  => $request->question_type,
            'option_a'       => $request->option_a,
            'option_b'       => $request->option_b,
            'option_c'       => $request->option_c,
            'option_d'       => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'marks'          => $request->marks,
            'order_no'       => ExamQuestion::where('exam_id', $exam->id)->max('order_no') + 1,
        ]);

        return back()->with('success', 'Question added.');
    }

    public function publish($examId)
    {
        $teacher = $this->getTeacher();
        $exam    = OnlineExam::where('id', $examId)->where('teacher_id', $teacher->id)->firstOrFail();
        abort_if(ExamQuestion::where('exam_id', $exam->id)->count() === 0, 422, 'Add questions before publishing.');
        $exam->update(['status' => 'Published']);
        return back()->with('success', 'Exam published. Students can now attempt it.');
    }

    // View results/attempts
    public function results($examId)
    {
        $teacher  = $this->getTeacher();
        $exam     = OnlineExam::where('id', $examId)->where('teacher_id', $teacher->id)->firstOrFail();
        $attempts = ExamAttempt::with('student.user')
            ->where('exam_id', $exam->id)
            ->orderByDesc('obtained_marks')->get();
        return view('teacher.online-exams.results', compact('exam','attempts'));
    }
}
```

---

### B.6 — Online Exam Controller (Student Side)

**File:** `app/Http/Controllers/Student/OnlineExamController.php`

```php
<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\OnlineExam;
use App\Models\ExamQuestion;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OnlineExamController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $exams   = OnlineExam::where('class_id', $student->current_class_id)
            ->whereIn('status', ['Published','Active','Closed'])
            ->with(['subject'])
            ->orderByDesc('exam_date')
            ->get();

        $attemptedIds = ExamAttempt::where('student_id', $student->id)
            ->pluck('exam_id')->toArray();

        return view('student.online-exams.index', compact('exams','attemptedIds'));
    }

    public function start($examId)
    {
        $student = auth()->user()->student;
        $exam    = OnlineExam::where('id', $examId)
            ->where('class_id', $student->current_class_id)
            ->where('status', 'Published')
            ->firstOrFail();

        // Check if already attempted
        $existing = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)->first();
        if ($existing && $existing->status !== 'Not Started') {
            return redirect()->route('student.online-exams.result', $examId)
                ->with('info', 'You have already submitted this exam.');
        }

        // Create attempt record
        $attempt = ExamAttempt::updateOrCreate(
            ['exam_id' => $exam->id, 'student_id' => $student->id],
            ['started_at' => now(), 'status' => 'In Progress', 'ip_address' => request()->ip()]
        );

        $questions = ExamQuestion::where('exam_id', $exam->id)
            ->when($exam->shuffle_questions, fn($q) => $q->inRandomOrder())
            ->get();

        return view('student.online-exams.take', compact('exam','questions','attempt'));
    }

    public function submit(Request $request, $examId)
    {
        $student = auth()->user()->student;
        $exam    = OnlineExam::findOrFail($examId);
        $attempt = ExamAttempt::where('exam_id', $examId)
            ->where('student_id', $student->id)
            ->where('status', 'In Progress')
            ->firstOrFail();

        $questions  = ExamQuestion::where('exam_id', $exam->id)->get();
        $totalMarks = 0;
        $obtained   = 0;

        foreach ($questions as $question) {
            $studentAnswer = $request->input("answers.{$question->id}");
            $isCorrect     = null;
            $marksAwarded  = 0;

            if ($question->question_type !== 'Short') {
                $isCorrect = strtolower(trim($studentAnswer ?? '')) === strtolower(trim($question->correct_answer ?? ''));
                $marksAwarded = $isCorrect ? $question->marks : 0;
            }
            // Short answer requires manual grading — award 0 for now

            $totalMarks += $question->marks;
            $obtained   += $marksAwarded;

            ExamAnswer::updateOrCreate(
                ['attempt_id' => $attempt->id, 'question_id' => $question->id],
                [
                    'student_answer' => $studentAnswer,
                    'is_correct'     => $isCorrect,
                    'marks_awarded'  => $marksAwarded,
                ]
            );
        }

        $pct = $totalMarks > 0 ? round(($obtained / $totalMarks) * 100, 1) : 0;

        $attempt->update([
            'submitted_at'   => now(),
            'total_marks'    => $totalMarks,
            'obtained_marks' => $obtained,
            'percentage'     => $pct,
            'status'         => 'Submitted',
        ]);

        if ($exam->show_result_immediately) {
            return redirect()->route('student.online-exams.result', $examId)
                ->with('success', 'Exam submitted! Your result is ready.');
        }

        return redirect()->route('student.online-exams.index')
            ->with('success', 'Exam submitted successfully. Result will be announced soon.');
    }

    public function result($examId)
    {
        $student = auth()->user()->student;
        $exam    = OnlineExam::with('subject')->findOrFail($examId);
        $attempt = ExamAttempt::where('exam_id', $examId)
            ->where('student_id', $student->id)->firstOrFail();
        $answers  = ExamAnswer::with('question')
            ->where('attempt_id', $attempt->id)->get();

        return view('student.online-exams.result', compact('exam','attempt','answers'));
    }
}
```

---

### B.7 — Real-Time Analytics Controller

**File:** `app/Http/Controllers/Admin/AnalyticsController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Fee;
use App\Models\StudentAttendance;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('admin.analytics.index');
    }

    // AJAX endpoint for all chart data
    public function chartData(Request $request)
    {
        $schoolId     = auth()->user()->hasRole('Super Admin') ? null : auth()->user()->school_id;
        $academicYear = AcademicYear::where('is_active', 1)->first();

        return response()->json([
            'fee_collection'    => $this->feeCollectionTrend($schoolId),
            'attendance_weekly' => $this->weeklyAttendance($schoolId, $academicYear),
            'class_performance' => $this->classPerformance($schoolId, $academicYear),
            'student_stats'     => $this->studentStats($schoolId),
            'fee_status_pie'    => $this->feeStatusPie($schoolId),
            'attendance_heatmap'=> $this->attendanceHeatmap($schoolId, $academicYear),
        ]);
    }

    private function feeCollectionTrend($schoolId): array
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date   = now()->subMonths($i);
            $q      = Fee::where('status','Paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);
            if ($schoolId) $q->where('school_id', $schoolId);
            $months[] = [
                'label'  => $date->format('M Y'),
                'amount' => (float) $q->sum('paid_amount'),
            ];
        }
        return $months;
    }

    private function weeklyAttendance($schoolId, $academicYear): array
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date  = now()->subDays($i)->toDateString();
            $q     = StudentAttendance::where('date', $date);
            if ($academicYear) $q->where('academic_year_id', $academicYear->id);
            $total   = $q->count();
            $present = (clone $q)->where('status','P')->count();
            $days[]  = [
                'date'    => $date,
                'label'   => now()->subDays($i)->format('D'),
                'present' => $present,
                'absent'  => $total - $present,
                'total'   => $total,
            ];
        }
        return $days;
    }

    private function classPerformance($schoolId, $academicYear): array
    {
        $classes = SchoolClass::orderBy('name')->get();
        return $classes->map(function ($class) use ($academicYear) {
            $studentIds = Student::where('current_class_id', $class->id)->pluck('id');
            $q          = Mark::whereIn('student_id', $studentIds);
            if ($academicYear) $q->where('academic_year_id', $academicYear->id);
            $total    = $q->sum('total_marks');
            $obtained = $q->sum('marks_obtained');
            return [
                'class'      => $class->name,
                'percentage' => $total > 0 ? round(($obtained/$total)*100,1) : 0,
            ];
        })->values()->toArray();
    }

    private function studentStats($schoolId): array
    {
        $q = Student::query();
        if ($schoolId) $q->where('school_id',$schoolId);
        return [
            'total'    => $q->count(),
            'active'   => (clone $q)->where('status','Active')->count(),
            'male'     => (clone $q)->where('gender','Male')->count(),
            'female'   => (clone $q)->where('gender','Female')->count(),
        ];
    }

    private function feeStatusPie($schoolId): array
    {
        $q = Fee::query();
        if ($schoolId) $q->where('school_id',$schoolId);
        return [
            ['label' => 'Paid',    'count' => (clone $q)->where('status','Paid')->count()],
            ['label' => 'Pending', 'count' => (clone $q)->where('status','Pending')->count()],
            ['label' => 'Overdue', 'count' => (clone $q)->where('status','Overdue')->count()],
        ];
    }

    private function attendanceHeatmap($schoolId, $academicYear): array
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date  = now()->subDays($i)->toDateString();
            $q     = StudentAttendance::where('date', $date);
            if ($academicYear) $q->where('academic_year_id', $academicYear->id);
            $total   = $q->count();
            $present = (clone $q)->where('status','P')->count();
            $data[]  = [
                'date'  => $date,
                'value' => $total > 0 ? round(($present/$total)*100) : 0,
            ];
        }
        return $data;
    }
}
```

---

### B.8 — Document Management Controller

**File:** `app/Http/Controllers/Admin/DocumentController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use App\Models\IssuedDocument;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = IssuedDocument::with(['student','template','issuedBy'])
            ->latest()->paginate(20);
        $templates = DocumentTemplate::where('is_active',1)->get();
        $students  = Student::with('currentClass')->where('status','Active')->get();
        return view('admin.documents.index', compact('documents','templates','students'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'student_id'  => 'required|exists:students,id',
            'template_id' => 'required|exists:document_templates,id',
            'purpose'     => 'nullable|string|max:500',
        ]);

        $student      = Student::with(['currentClass','currentSection'])->findOrFail($request->student_id);
        $template     = DocumentTemplate::findOrFail($request->template_id);
        $academicYear = AcademicYear::where('is_active',1)->first();

        // Replace template variables with real data
        $variables = [
            '{{school_name}}'    => config('app.school_name','MKhan School'),
            '{{student_name}}'   => $student->first_name . ' ' . $student->last_name,
            '{{father_name}}'    => $student->father_name,
            '{{admission_no}}'   => $student->admission_no,
            '{{class_name}}'     => $student->currentClass?->name ?? '',
            '{{admission_date}}' => $student->admission_date ? date('d M Y', strtotime($student->admission_date)) : '',
            '{{leaving_date}}'   => now()->format('d M Y'),
            '{{address}}'        => $student->address ?? '',
            '{{academic_year}}' => $academicYear ? ($academicYear->start_date . ' - ' . $academicYear->end_date) : '',
            '{{purpose}}'        => $request->purpose ?? 'official use',
            '{{date}}'           => now()->format('d M Y'),
        ];

        $content = str_replace(
            array_keys($variables),
            array_values($variables),
            $template->content
        );

        // Generate document number
        $docNo = strtoupper(substr($template->slug,0,2)) . '-' . now()->format('Ymd') . '-' . str_pad(IssuedDocument::count()+1,5,'0',STR_PAD_LEFT);

        $issued = IssuedDocument::create([
            'student_id'  => $student->id,
            'template_id' => $template->id,
            'document_no' => $docNo,
            'issued_by'   => auth()->id(),
            'purpose'     => $request->purpose,
        ]);

        // Generate PDF
        $pdf = Pdf::loadView('admin.documents.pdf', [
            'content'   => $content,
            'document'  => $issued,
            'student'   => $student,
            'template'  => $template,
            'issued_at' => now()->format('d M Y'),
        ])->setPaper('a4','portrait');

        return $pdf->download("{$template->slug}_{$student->admission_no}_{$docNo}.pdf");
    }

    public function templates()
    {
        $templates = DocumentTemplate::all();
        return view('admin.documents.templates', compact('templates'));
    }

    public function editTemplate($id)
    {
        $template = DocumentTemplate::findOrFail($id);
        return view('admin.documents.edit-template', compact('template'));
    }

    public function updateTemplate(Request $request, $id)
    {
        $template = DocumentTemplate::findOrFail($id);
        $request->validate(['content' => 'required|string']);
        $template->update(['content' => $request->content]);
        return back()->with('success','Template updated.');
    }
}
```

---

### B.9 — Staff Leave & Substitute Controller

**File:** `app/Http/Controllers/Admin/StaffLeaveController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherLeaveRequest;
use App\Models\SubstituteAssignment;
use App\Models\Teacher;
use App\Models\TeacherLeaveBalance;
use App\Models\AcademicYear;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffLeaveController extends Controller
{
    public function index()
    {
        $leaves = TeacherLeaveRequest::with(['teacher.user'])
            ->orderByDesc('created_at')->paginate(20);
        $pendingCount = TeacherLeaveRequest::where('status','Pending')->count();
        return view('admin.staff-leaves.index', compact('leaves','pendingCount'));
    }

    public function approve(Request $request, $id)
    {
        $leave   = TeacherLeaveRequest::with('teacher')->findOrFail($id);
        $request->validate([
            'substitute_teacher_id' => 'nullable|exists:teachers,id',
        ]);

        DB::beginTransaction();
        try {
            $leave->update([
                'status'      => 'Approved',
                'approved_by' => auth()->id(),
            ]);

            // Update leave balance
            $academicYear = AcademicYear::where('is_active',1)->first();
            $balance = TeacherLeaveBalance::firstOrCreate(
                ['teacher_id' => $leave->teacher_id, 'academic_year_id' => $academicYear->id],
                ['casual_total'=>12,'casual_used'=>0,'sick_total'=>10,'sick_used'=>0,'annual_total'=>15,'annual_used'=>0]
            );

            $leaveType = strtolower($leave->leave_type);
            if (str_contains($leaveType,'sick'))   $balance->increment('sick_used', $leave->total_days);
            elseif (str_contains($leaveType,'annual')) $balance->increment('annual_used', $leave->total_days);
            else $balance->increment('casual_used', $leave->total_days);

            // Auto-assign substitute if provided
            if ($request->substitute_teacher_id) {
                $this->assignSubstitute($leave, $request->substitute_teacher_id);
                $leave->update(['substitute_assigned' => 1]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error','Approval failed: '.$e->getMessage());
        }

        return back()->with('success','Leave approved.' . ($request->substitute_teacher_id ? ' Substitute assigned.' : ''));
    }

    public function reject(Request $request, $id)
    {
        $leave = TeacherLeaveRequest::findOrFail($id);
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        $leave->update([
            'status'           => 'Rejected',
            'approved_by'      => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        return back()->with('success','Leave request rejected.');
    }

    public function assignSubstituteManually(Request $request, $leaveId)
    {
        $leave = TeacherLeaveRequest::findOrFail($leaveId);
        $request->validate(['substitute_teacher_id' => 'required|exists:teachers,id']);

        $this->assignSubstitute($leave, $request->substitute_teacher_id);
        $leave->update(['substitute_assigned' => 1]);

        return back()->with('success','Substitute teacher assigned.');
    }

    public function substituteSchedule()
    {
        $substitutes = SubstituteAssignment::with([
            'originalTeacher.user',
            'substituteTeacher.user',
            'class_',
            'subject',
        ])->where('date','>=', today()->toDateString())
          ->orderBy('date')->paginate(20);
        return view('admin.staff-leaves.substitutes', compact('substitutes'));
    }

    public function leaveBalances()
    {
        $academicYear = AcademicYear::where('is_active',1)->first();
        $teachers     = Teacher::with(['user','leaveBalance' => function($q) use ($academicYear) {
            $q->where('academic_year_id', $academicYear?->id);
        }])->get();
        return view('admin.staff-leaves.balances', compact('teachers','academicYear'));
    }

    // Auto-assign substitute based on original teacher's timetable
    private function assignSubstitute(TeacherLeaveRequest $leave, int $substituteTeacherId): void
    {
        // Get the original teacher's timetable for the leave period
        $startDate = \Carbon\Carbon::parse($leave->start_date);
        $endDate   = \Carbon\Carbon::parse($leave->end_date);

        $timetableEntries = Timetable::where('teacher_id', $leave->teacher_id)->get();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayName = $date->format('l'); // Monday, Tuesday...
            $dayEntries = $timetableEntries->where('day_of_week', $dayName);

            foreach ($dayEntries as $entry) {
                SubstituteAssignment::firstOrCreate(
                    [
                        'leave_request_id'       => $leave->id,
                        'original_teacher_id'    => $leave->teacher_id,
                        'substitute_teacher_id'  => $substituteTeacherId,
                        'class_id'               => $entry->class_id,
                        'subject_id'             => $entry->subject_id_ref ?? $entry->subject_id,
                        'date'                   => $date->toDateString(),
                    ],
                    [
                        'period_time' => $entry->start_time . ' - ' . $entry->end_time,
                        'status'     => 'Assigned',
                        'assigned_by'=> auth()->id(),
                    ]
                );
            }
        }
    }
}
```

---

## PHASE C — ROUTES (Add to `routes/web.php`)

```php
// ─── ONLINE FEE PAYMENT ──────────────────────────────────────────────────────
Route::middleware(['auth','role:Super Admin,School Admin','same_school'])
    ->prefix('admin')->name('admin.')->group(function () {

    // Fee Payment Gateway
    Route::get('fees/{fee}/pay-online', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class,'initiatePayment'])->name('fees.pay-online');
    Route::post('fees/{fee}/jazzcash', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class,'processJazzCash'])->name('fees.jazzcash');
    Route::post('fees/{fee}/easypaisa', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class,'processEasyPaisa'])->name('fees.easypaisa');
    Route::get('fees/receipts/{receipt}/download', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class,'downloadReceipt'])->name('fees.receipt.download');

    // Fee Challan
    Route::get('fees/challan/{student}', [\App\Http\Controllers\Admin\FeeChallanController::class,'generate'])->name('fees.challan');
    Route::post('fees/challan/bulk', [\App\Http\Controllers\Admin\FeeChallanController::class,'bulkGenerate'])->name('fees.challan.bulk');

    // Student Promotion
    Route::get('promotions', [\App\Http\Controllers\Admin\StudentPromotionController::class,'index'])->name('promotions.index');
    Route::post('promotions/preview', [\App\Http\Controllers\Admin\StudentPromotionController::class,'preview'])->name('promotions.preview');
    Route::post('promotions/execute', [\App\Http\Controllers\Admin\StudentPromotionController::class,'execute'])->name('promotions.execute');
    Route::get('promotions/rules', [\App\Http\Controllers\Admin\StudentPromotionController::class,'rules'])->name('promotions.rules');
    Route::post('promotions/rules', [\App\Http\Controllers\Admin\StudentPromotionController::class,'saveRule'])->name('promotions.rules.save');

    // Multi-Branch
    Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class);
    Route::post('branches/switch', [\App\Http\Controllers\Admin\BranchController::class,'switchBranch'])->name('branches.switch');

    // Online Exams (Teacher manages, admin views)
    Route::get('online-exams', [\App\Http\Controllers\Teacher\OnlineExamController::class,'index'])->name('online-exams.index');

    // Analytics
    Route::get('analytics', [\App\Http\Controllers\Admin\AnalyticsController::class,'index'])->name('analytics.index');
    Route::get('analytics/chart-data', [\App\Http\Controllers\Admin\AnalyticsController::class,'chartData'])->name('analytics.chart-data');

    // Document Management
    Route::get('documents', [\App\Http\Controllers\Admin\DocumentController::class,'index'])->name('documents.index');
    Route::post('documents/generate', [\App\Http\Controllers\Admin\DocumentController::class,'generate'])->name('documents.generate');
    Route::get('documents/templates', [\App\Http\Controllers\Admin\DocumentController::class,'templates'])->name('documents.templates');
    Route::get('documents/templates/{id}/edit', [\App\Http\Controllers\Admin\DocumentController::class,'editTemplate'])->name('documents.templates.edit');
    Route::put('documents/templates/{id}', [\App\Http\Controllers\Admin\DocumentController::class,'updateTemplate'])->name('documents.templates.update');

    // Staff Leave & Substitutes
    Route::get('staff-leaves', [\App\Http\Controllers\Admin\StaffLeaveController::class,'index'])->name('staff-leaves.index');
    Route::post('staff-leaves/{id}/approve', [\App\Http\Controllers\Admin\StaffLeaveController::class,'approve'])->name('staff-leaves.approve');
    Route::post('staff-leaves/{id}/reject', [\App\Http\Controllers\Admin\StaffLeaveController::class,'reject'])->name('staff-leaves.reject');
    Route::post('staff-leaves/{id}/assign-substitute', [\App\Http\Controllers\Admin\StaffLeaveController::class,'assignSubstituteManually'])->name('staff-leaves.assign-substitute');
    Route::get('staff-leaves/substitute-schedule', [\App\Http\Controllers\Admin\StaffLeaveController::class,'substituteSchedule'])->name('staff-leaves.substitute-schedule');
    Route::get('staff-leaves/balances', [\App\Http\Controllers\Admin\StaffLeaveController::class,'leaveBalances'])->name('staff-leaves.balances');
});

// EasyPaisa callback (no auth — external gateway calls this)
Route::post('easypaisa/callback', [\App\Http\Controllers\Admin\OnlineFeePaymentController::class,'easyPaisaCallback'])
    ->name('admin.fees.easypaisa.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ─── TEACHER: Online Exams ────────────────────────────────────────────────────
Route::middleware(['auth','role:Teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('online-exams', [\App\Http\Controllers\Teacher\OnlineExamController::class,'index'])->name('online-exams.index');
    Route::get('online-exams/create', [\App\Http\Controllers\Teacher\OnlineExamController::class,'create'])->name('online-exams.create');
    Route::post('online-exams', [\App\Http\Controllers\Teacher\OnlineExamController::class,'store'])->name('online-exams.store');
    Route::get('online-exams/{id}/questions', [\App\Http\Controllers\Teacher\OnlineExamController::class,'questions'])->name('online-exams.questions');
    Route::post('online-exams/{id}/questions', [\App\Http\Controllers\Teacher\OnlineExamController::class,'storeQuestion'])->name('online-exams.questions.store');
    Route::post('online-exams/{id}/publish', [\App\Http\Controllers\Teacher\OnlineExamController::class,'publish'])->name('online-exams.publish');
    Route::get('online-exams/{id}/results', [\App\Http\Controllers\Teacher\OnlineExamController::class,'results'])->name('online-exams.results');

    // Teacher leave request
    Route::get('leave-requests', [\App\Http\Controllers\Teacher\LeaveController::class,'index'])->name('leave.index');
    Route::post('leave-requests', [\App\Http\Controllers\Teacher\LeaveController::class,'store'])->name('leave.store');
});

// ─── STUDENT: Online Exams ────────────────────────────────────────────────────
Route::middleware(['auth','role:Student'])->prefix('student')->name('student.')->group(function () {
    Route::get('online-exams', [\App\Http\Controllers\Student\OnlineExamController::class,'index'])->name('online-exams.index');
    Route::get('online-exams/{id}/start', [\App\Http\Controllers\Student\OnlineExamController::class,'start'])->name('online-exams.start');
    Route::post('online-exams/{id}/submit', [\App\Http\Controllers\Student\OnlineExamController::class,'submit'])->name('online-exams.submit');
    Route::get('online-exams/{id}/result', [\App\Http\Controllers\Student\OnlineExamController::class,'result'])->name('online-exams.result');
});
```

---

## PHASE D — CONFIG (.env additions)

Add these to your `.env` file:

```env
# JazzCash
JAZZCASH_MERCHANT_ID=your_merchant_id
JAZZCASH_PASSWORD=your_password
JAZZCASH_INTEGRITY_SALT=your_integrity_salt
JAZZCASH_ENV=sandbox

# EasyPaisa
EASYPAISA_STORE_ID=your_store_id
EASYPAISA_HASH_KEY=your_hash_key
EASYPAISA_ENV=sandbox

# School Info (for documents/challans)
SCHOOL_NAME="M Khan School"
SCHOOL_ADDRESS="Your School Address"
SCHOOL_PHONE="03XX-XXXXXXX"
```

Add to `config/services.php`:

```php
'jazzcash' => [
    'merchant_id'    => env('JAZZCASH_MERCHANT_ID'),
    'password'       => env('JAZZCASH_PASSWORD'),
    'integrity_salt' => env('JAZZCASH_INTEGRITY_SALT'),
    'env'            => env('JAZZCASH_ENV', 'sandbox'),
],

'easypaisa' => [
    'store_id' => env('EASYPAISA_STORE_ID'),
    'hash_key' => env('EASYPAISA_HASH_KEY'),
    'env'      => env('EASYPAISA_ENV', 'sandbox'),
],
```

---

## PHASE E — PACKAGES TO INSTALL

```bash
# PDF generation (already in previous prompt — confirm it's installed)
composer require barryvdh/laravel-dompdf

# QR Code for fee challans
composer require simplesoftwareio/simple-qrcode

# Publish configs
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## PHASE F — BLADE VIEWS (Key Views — Theme Rules Apply)

### F.1 — Analytics Dashboard View

**File:** `resources/views/admin/analytics/index.blade.php`

```blade
@extends('layouts.app')
@section('title', 'Analytics Dashboard')

@section('content')
{{-- Copy exact page-header pattern from an existing admin page --}}

<div class="row" id="stats-row">
    {{-- Cards filled by JS --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Total Students</h6>
                <h3 id="stat-total-students">--</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Active Students</h6>
                <h3 id="stat-active-students">--</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Fee Collected (This Month)</h6>
                <h3 id="stat-fee-month">--</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Avg Attendance (Today)</h6>
                <h3 id="stat-attendance">--</h3>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    {{-- Fee Collection Trend --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Fee Collection (Last 6 Months)</div>
            <div class="card-body">
                <canvas id="feeChart" height="100"></canvas>
            </div>
        </div>
    </div>
    {{-- Fee Status Pie --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Fee Status Breakdown</div>
            <div class="card-body">
                <canvas id="feePieChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    {{-- Weekly Attendance --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Weekly Attendance</div>
            <div class="card-body">
                <canvas id="attendanceChart" height="150"></canvas>
            </div>
        </div>
    </div>
    {{-- Class Performance --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Class-wise Average Performance</div>
            <div class="card-body">
                <canvas id="performanceChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route("admin.analytics.chart-data") }}')
        .then(r => r.json())
        .then(data => {
            // Fill stat cards
            document.getElementById('stat-total-students').textContent  = data.student_stats.total;
            document.getElementById('stat-active-students').textContent = data.student_stats.active;
            document.getElementById('stat-fee-month').textContent = 'Rs ' + (data.fee_collection[data.fee_collection.length-1]?.amount ?? 0).toLocaleString();

            const todayAttend = data.attendance_weekly[data.attendance_weekly.length-1];
            const pct = todayAttend?.total > 0 ? Math.round((todayAttend.present/todayAttend.total)*100) : 0;
            document.getElementById('stat-attendance').textContent = pct + '%';

            // Fee Collection Bar Chart
            new Chart(document.getElementById('feeChart'), {
                type: 'bar',
                data: {
                    labels: data.fee_collection.map(d => d.label),
                    datasets: [{
                        label: 'Fee Collected (Rs)',
                        data: data.fee_collection.map(d => d.amount),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive:true, plugins:{ legend:{display:false} } }
            });

            // Fee Pie Chart
            new Chart(document.getElementById('feePieChart'), {
                type: 'doughnut',
                data: {
                    labels: data.fee_status_pie.map(d => d.label),
                    datasets: [{
                        data: data.fee_status_pie.map(d => d.count),
                        backgroundColor: ['#28a745','#ffc107','#dc3545'],
                    }]
                },
                options: { responsive:true }
            });

            // Attendance Line Chart
            new Chart(document.getElementById('attendanceChart'), {
                type: 'line',
                data: {
                    labels: data.attendance_weekly.map(d => d.label),
                    datasets: [
                        { label:'Present', data: data.attendance_weekly.map(d=>d.present), borderColor:'#28a745', fill:false },
                        { label:'Absent',  data: data.attendance_weekly.map(d=>d.absent),  borderColor:'#dc3545', fill:false },
                    ]
                },
                options: { responsive:true }
            });

            // Class Performance Bar
            new Chart(document.getElementById('performanceChart'), {
                type: 'bar',
                data: {
                    labels: data.class_performance.map(d => d.class),
                    datasets: [{
                        label: 'Avg %',
                        data: data.class_performance.map(d => d.percentage),
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { min:0, max:100 } },
                    plugins: { legend:{display:false} }
                }
            });
        });
});
</script>
@endpush
```

### F.2 — Fee Challan PDF View

**File:** `resources/views/admin/fees/challan-pdf.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .challan-box { border: 1px solid #333; padding: 15px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        th { background: #f5f5f5; }
        .total-row { font-weight: bold; background: #e8e8e8; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; }
        .qr-section { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $school['name'] }}</h2>
        <p>{{ $school['address'] }} | {{ $school['phone'] }}</p>
        <h3>FEE PAYMENT CHALLAN</h3>
    </div>

    <div class="challan-box">
        <table>
            <tr>
                <td><strong>Challan No:</strong> {{ $challan_no }}</td>
                <td><strong>Issue Date:</strong> {{ $issued_date }}</td>
                <td><strong>Due Date:</strong> {{ $due_date }}</td>
            </tr>
            <tr>
                <td><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</td>
                <td><strong>Admission No:</strong> {{ $student->admission_no }}</td>
                <td><strong>Class:</strong> {{ $student->currentClass?->name }}</td>
            </tr>
            <tr>
                <td><strong>Father Name:</strong> {{ $student->father_name }}</td>
                <td><strong>Session:</strong> {{ $academic_year?->name ?? '' }}</td>
                <td></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fee Description</th>
                <th>Due Date</th>
                <th>Amount (Rs)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fees as $i => $fee)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $fee->fee_category }}</td>
                <td>{{ $fee->due_date }}</td>
                <td>{{ number_format($fee->amount, 2) }}</td>
                <td>{{ $fee->status }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align:right">Total Payable:</td>
                <td>Rs {{ number_format($total_amount, 2) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div>
            <p>Student/Parent Signature: _________________</p>
        </div>
        <div>
            <p>Bank/Cashier Stamp & Signature: _________________</p>
        </div>
    </div>

    <p style="font-size:10px; text-align:center; margin-top:20px; color:#666;">
        Please pay before the due date to avoid late fee charges. This is a computer-generated challan.
    </p>
</body>
</html>
```

### F.3 — Document PDF View

**File:** `resources/views/admin/documents/pdf.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; line-height: 1.7; }
        .letterhead { text-align: center; border-bottom: 3px double #333; padding-bottom: 15px; margin-bottom: 20px; }
        .doc-no { text-align: right; color: #666; font-size: 11px; }
        .content { margin: 30px 0; text-align: justify; }
        .signature-section { margin-top: 60px; display: flex; justify-content: space-between; }
        .footer-note { margin-top: 40px; font-size: 10px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="letterhead">
        <h2>{{ config('app.school_name', 'M Khan School') }}</h2>
        <p>{{ config('app.school_address', '') }} | {{ config('app.school_phone', '') }}</p>
        <h3>{{ $template->name }}</h3>
    </div>

    <div class="doc-no">
        Document No: {{ $document->document_no }} &nbsp;|&nbsp; Date: {{ $issued_at }}
    </div>

    <div class="content">
        {!! $content !!}
    </div>

    <div class="signature-section">
        <div>
            <p>____________________</p>
            <p>Class Teacher</p>
        </div>
        <div style="text-align:right">
            <p>____________________</p>
            <p>Principal</p>
            <p>(Stamp & Signature)</p>
        </div>
    </div>

    <div class="footer-note">
        This document was issued on {{ $issued_at }}. Document No: {{ $document->document_no }}
    </div>
</body>
</html>
```

---

## PHASE G — SIDEBAR MENU ITEMS (Add to Existing Sidebar)

> Find your existing sidebar partial file (likely `resources/views/partials/sidebar.blade.php` or `layouts/sidebar.blade.php`) and ADD these items using the exact same `<li>`, `<a>`, icon, and class pattern already used.

```blade
{{-- ===== ADD THESE TO YOUR EXISTING SIDEBAR (Admin Section) ===== --}}
{{-- Copy the exact <li> class, <a> class, icon style from existing items --}}

{{-- Fee Challan --}}
<li class="[COPY_EXISTING_LI_CLASS]">
    <a href="{{ route('admin.fees.index') }}" class="[COPY_EXISTING_A_CLASS]">
        <i class="[COPY_EXISTING_ICON_CLASS] fa-receipt"></i>
        <span>Fee Challans</span>
    </a>
</li>

{{-- Student Promotion --}}
<li class="[COPY_EXISTING_LI_CLASS]">
    <a href="{{ route('admin.promotions.index') }}" class="[COPY_EXISTING_A_CLASS]">
        <i class="[COPY_EXISTING_ICON_CLASS] fa-level-up-alt"></i>
        <span>Student Promotion</span>
    </a>
</li>

{{-- Multi-Branch (Super Admin only) --}}
@if(auth()->user()->hasRole('Super Admin'))
<li class="[COPY_EXISTING_LI_CLASS]">
    <a href="{{ route('admin.branches.index') }}" class="[COPY_EXISTING_A_CLASS]">
        <i class="[COPY_EXISTING_ICON_CLASS] fa-sitemap"></i>
        <span>Branch Management</span>
    </a>
</li>
@endif

{{-- Online Exams --}}
<li class="[COPY_EXISTING_LI_CLASS]">
    <a href="{{ route('admin.analytics.index') }}" class="[COPY_EXISTING_A_CLASS]">
        <i class="[COPY_EXISTING_ICON_CLASS] fa-chart-bar"></i>
        <span>Analytics</span>
    </a>
</li>

{{-- Documents --}}
<li class="[COPY_EXISTING_LI_CLASS]">
    <a href="{{ route('admin.documents.index') }}" class="[COPY_EXISTING_A_CLASS]">
        <i class="[COPY_EXISTING_ICON_CLASS] fa-file-alt"></i>
        <span>Documents / Certificates</span>
    </a>
</li>

{{-- Staff Leaves --}}
<li class="[COPY_EXISTING_LI_CLASS]">
    <a href="{{ route('admin.staff-leaves.index') }}" class="[COPY_EXISTING_A_CLASS]">
        <i class="[COPY_EXISTING_ICON_CLASS] fa-user-clock"></i>
        <span>Staff Leaves</span>
    </a>
</li>

{{-- ===== ADD TO TEACHER SIDEBAR ===== --}}
<li class="[COPY_EXISTING_LI_CLASS]">
    <a href="{{ route('teacher.online-exams.index') }}" class="[COPY_EXISTING_A_CLASS]">
        <i class="[COPY_EXISTING_ICON_CLASS] fa-laptop"></i>
        <span>Online Exams</span>
    </a>
</li>

{{-- ===== ADD TO STUDENT SIDEBAR ===== --}}
<li class="[COPY_EXISTING_LI_CLASS]">
    <a href="{{ route('student.online-exams.index') }}" class="[COPY_EXISTING_A_CLASS]">
        <i class="[COPY_EXISTING_ICON_CLASS] fa-laptop"></i>
        <span>Online Exams</span>
    </a>
</li>
```

---

## PHASE H — MODELS TO CREATE

Create these model files in `app/Models/`:

```
FeePaymentTransaction.php  → table: fee_payment_transactions
FeeReceipt.php             → table: fee_receipts
StudentPromotion.php       → table: student_promotions
PromotionRule.php          → table: promotion_rules
SchoolBranch.php           → table: school_branches
BranchAdmin.php            → table: branch_admins
OnlineExam.php             → table: online_exams
ExamQuestion.php           → table: exam_questions
ExamAttempt.php            → table: exam_attempts
ExamAnswer.php             → table: exam_answers
DocumentTemplate.php       → table: document_templates
IssuedDocument.php         → table: issued_documents
TeacherLeaveRequest.php    → table: teacher_leave_requests
SubstituteAssignment.php   → table: substitute_assignments
TeacherLeaveBalance.php    → table: teacher_leave_balances
```

Each model: add `protected $fillable = [...]` with all columns listed above in Phase A migrations. Add appropriate `belongsTo` / `hasMany` relationships.

---

## FINAL CHECKLIST

- [ ] Phase A: All 6 SQL blocks run without errors. `SHOW TABLES;` confirms all new tables exist.
- [ ] Phase A: Document template seed ran — 3 templates visible in `document_templates` table.
- [ ] Phase B: All 9 controller files created in correct namespaces.
- [ ] Phase C: All routes added. `php artisan route:list | grep online-exam` shows routes.
- [ ] Phase D: `.env` and `config/services.php` updated with gateway credentials.
- [ ] Phase E: `composer require barryvdh/laravel-dompdf simplesoftwareio/simple-qrcode` successful.
- [ ] Phase F: Blade views created. No hardcoded CSS — all classes copied from existing pages.
- [ ] Phase G: Sidebar items added using EXACTLY the same HTML class patterns as existing items.
- [ ] Phase H: All 15 models created with `$fillable` arrays.
- [ ] Theme Check: Open every new page — it must look IDENTICAL in style to existing admin pages.
- [ ] Theme Check: No new CSS files added. No Bootstrap version changes. No new color schemes.
- [ ] Security: Every route has `auth` + `role` middleware. No unprotected routes.
- [ ] Security: Teacher controllers use `TeacherScoped` trait — teachers can only see their own data.
- [ ] JazzCash: Test with sandbox credentials before going live.
- [ ] EasyPaisa: Test with sandbox credentials before going live.
- [ ] PDF: Challan and document PDFs generate correctly with `barryvdh/laravel-dompdf`.

---

*Generated for Newmkhanschool Advanced Upgrade*  
*Stack: Laravel PHP 8.2 · MariaDB 10.4 · Existing Theme Preserved*  
*Features: JazzCash/EasyPaisa Payments · Student Promotion Engine · Multi-Branch · Fee Challan PDF · Online Exams · Analytics Charts · Document Management · Staff Leave & Substitute System*
