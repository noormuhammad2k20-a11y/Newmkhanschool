# ANTIGRAVITY EXECUTION PROMPT — Accountant Portal Module (v1.0)
## Project: NewMkhanSchool (Laravel 11)
## Repo: https://github.com/noormuhammad2k20-a11y/Newmkhanschool

---

## 0. CONTEXT (READ FIRST)

This is a Laravel 11 + Blade + MySQL/MariaDB school management system (PHP 8.2). It already has working portals for **Super Admin**, **School Admin**, **Teacher**, **Student**, and **Parent**, each with its own layout, sidebar, and route group under role-based middleware. The system supports multi-branch schools (`schools`, `school_branches`, `branch_admins` tables, `school_id` scoping on most tables).

You must build a brand-new **Accountant Portal** that:

1. Follows the **EXACT same visual theme, layout structure, sidebar style, card components, table styles, button colors, and Blade layout/component structure** already used in the School Admin portal. DO NOT create a new theme, new CSS framework, or new layout system. Reuse `layouts.admin` (or whatever the existing admin master layout is called) — extend it, don't replace it.
2. Is fully functional end-to-end: migrations, models, controllers, routes, middleware, views, PDF exports, and seed data.
3. Is scoped to `school_id` exactly like the School Admin portal (multi-branch aware).
4. Does NOT touch or break any existing Super Admin / School Admin / Teacher / Student / Parent functionality. Only ADD new code. If you must modify a shared file (routes/web.php, sidebar partial, RoleSeeder, etc.), make additive, backward-compatible changes only.

Before writing any code, first explore the existing codebase structure:
- `app/Http/Controllers/Admin/` (or wherever School Admin controllers live)
- `resources/views/admin/` (layout, sidebar partial, dashboard, fees module — use as the visual reference)
- `app/Models/`
- `routes/web.php`
- Existing middleware for role checking (e.g. `CheckRole`, `RoleMiddleware`)
- `database/seeders/RoleSeeder.php` / `PermissionSeeder.php`
- Existing fee module (`FeeController`, `fees` views) — the Accountant fee collection screens must look IDENTICAL in style to these.
- Existing PDF generation setup (`barryvdh/laravel-dompdf`) used for Transfer/School Leaving Certificates — reuse the same dompdf config, fonts (DejaVu Sans), `@page` setup, and footer pattern for all new PDF documents (fee receipts, salary slips, tax slips, expense vouchers).

---

## 1. NEW ROLE & PERMISSIONS

### 1.1 Role
Add a new row to `roles` table:
```
id: (next available, e.g. 6)
name: 'Accountant'
description: 'Manages fees, payroll, expenses, and financial reports for the school'
```

### 1.2 New Permissions
Add to `permissions` table (continue numbering from existing max id = 43):
```
44, 'Manage Fee Collection', 'manage_fee_collection'
45, 'Manage Fee Structure', 'manage_fee_structure'
46, 'View Financial Reports', 'view_financial_reports'
47, 'Manage Expenses', 'manage_expenses'
48, 'View Expenses', 'view_expenses'
49, 'Manage Bank Accounts', 'manage_bank_accounts'
50, 'Manage Cash Book', 'manage_cash_book'
51, 'Generate Tax Slips', 'generate_tax_slips'
52, 'Manage Inventory Purchases', 'manage_inventory_purchases'
```

### 1.3 Role-Permission Mapping (role_id = Accountant's id)
Assign these permissions to the Accountant role via `role_permissions`:
- view_dashboard
- view_fees, manage_fees, manage_fee_collection, view_own_fees
- manage_fee_structure
- manage_payroll
- generate_tax_slips
- manage_expenses, view_expenses
- manage_bank_accounts
- manage_cash_book
- manage_inventory_purchases
- view_reports, generate_reports, view_financial_reports
- view_own_profile, edit_own_profile
- view_announcements
- send_messages

Do NOT assign: manage_students, manage_teachers, mark_attendance, enter_marks, manage_exam_schedule, manage_library, manage_hostel, manage_transport, system_settings, manage_health_records.

### 1.4 Middleware
Add `'Accountant'` (or `'accountant'`) to whatever role-checking middleware/enum already exists for School Admin (e.g. `CheckRole:Accountant` or similar). Create route group:

```php
Route::middleware(['auth', 'role:Accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    // all accountant routes here
});
```

Accountant users are created via `users` table with `role_id` = Accountant's role id, and `school_id` set to their assigned branch — exactly like School Admin users. Reuse the same login flow; after login, redirect based on `role_id` to `/accountant/dashboard`.

---

## 2. NEW DATABASE TABLES (Migrations)

The existing schema already has: `fees`, `fee_categories`, `fee_payments`, `fee_payment_transactions`, `fee_receipts`, `fee_structures`, `payrolls`, `tax_slips`, `inventory`, `inventory_categories`, `inventory_transactions`. These are sufficient for fee/payroll/inventory. You need to ADD the following NEW tables for general accounting:

### 2.1 `expense_categories`
```php
Schema::create('expense_categories', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('school_id');
    $table->string('name');
    $table->text('description')->nullable();
    $table->timestamps();
});
```
Seed defaults per school: Utilities (Electricity, Water, Gas), Maintenance & Repairs, Stationery & Supplies, Salaries (Manual/Cash), Transport & Fuel, Furniture & Equipment, Events & Functions, Miscellaneous.

### 2.2 `bank_accounts`
```php
Schema::create('bank_accounts', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('school_id');
    $table->string('bank_name');
    $table->string('account_title');
    $table->string('account_number');
    $table->string('branch_code')->nullable();
    $table->decimal('opening_balance', 14, 2)->default(0);
    $table->decimal('current_balance', 14, 2)->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 2.3 `expenses`
```php
Schema::create('expenses', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('school_id');
    $table->unsignedBigInteger('expense_category_id');
    $table->string('voucher_no')->unique();
    $table->string('title');
    $table->text('description')->nullable();
    $table->decimal('amount', 12, 2);
    $table->date('expense_date');
    $table->enum('payment_method', ['Cash','Bank','Cheque'])->default('Cash');
    $table->unsignedBigInteger('bank_account_id')->nullable();
    $table->string('paid_to')->nullable();
    $table->enum('status', ['Pending','Approved','Paid','Rejected'])->default('Pending');
    $table->unsignedInteger('created_by'); // users.id
    $table->unsignedInteger('approved_by')->nullable();
    $table->string('attachment')->nullable(); // receipt/bill image or pdf
    $table->timestamps();

    $table->foreign('expense_category_id')->references('id')->on('expense_categories');
    $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
});
```

### 2.4 `ledger_entries` (Cash Book / General Ledger)
```php
Schema::create('ledger_entries', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('school_id');
    $table->date('entry_date');
    $table->enum('type', ['Income','Expense']);
    $table->string('category'); // e.g. 'Fee Collection', 'Expense', 'Payroll', 'Manual Entry'
    $table->string('reference_type')->nullable(); // Fee, Expense, Payroll, TaxSlip, Manual
    $table->unsignedBigInteger('reference_id')->nullable();
    $table->text('description')->nullable();
    $table->decimal('debit', 14, 2)->default(0);   // expense / outflow
    $table->decimal('credit', 14, 2)->default(0);  // income / inflow
    $table->decimal('balance_after', 14, 2)->default(0);
    $table->enum('account_type', ['Cash','Bank'])->default('Cash');
    $table->unsignedBigInteger('bank_account_id')->nullable();
    $table->unsignedInteger('created_by');
    $table->timestamps();

    $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
});
```

> IMPORTANT: Whenever a fee payment is recorded, an expense is approved as "Paid", or a payroll is marked "Paid", AUTOMATICALLY create a corresponding `ledger_entries` row (via Model events/Observers) so the cash book stays accurate without manual double-entry. This is critical — implement Observers for `FeePayment`/`FeePaymentTransaction`, `Expense`, and `Payroll` models.

### 2.5 Migration notes
- Add `school_id` foreign keys consistent with existing pattern (no strict FK constraint on `schools` if existing tables don't enforce it either — check existing convention first and match it).
- Run migrations only — do not modify existing tables' structure.

---

## 3. MODELS

Create Eloquent models with relationships:

- `ExpenseCategory` — hasMany `Expense`
- `Expense` — belongsTo `ExpenseCategory`, `BankAccount`, `User` (createdBy/approvedBy)
- `BankAccount` — hasMany `Expense`, `LedgerEntry`
- `LedgerEntry` — belongsTo `BankAccount`, `User`
- Add missing relationships to existing models if not present:
  - `Fee` model: hasMany `FeePayments`, `FeePaymentTransactions`, belongsTo `Student`, belongsTo `FeeCategory`
  - `FeeStructure`: belongsTo `FeeCategory`, belongsTo `Class`
  - `Payroll`/`Payrolls`: belongsTo `Teacher`, hasOne `TaxSlip`
  - `TaxSlip`: belongsTo `Payroll`

---

## 4. ACCOUNTANT PORTAL — FULL MODULE LIST

Build the following modules under `/accountant/*`. Each module = Controller + routes + Blade views using the EXISTING admin theme components (cards, tables, modals, datatables, breadcrumbs, alerts).

### 4.1 Dashboard (`/accountant/dashboard`)
Widgets (cards row, same style as School Admin dashboard cards):
- Today's Fee Collection (sum of `fee_payments`/`fee_payment_transactions` for today, school-scoped)
- This Month's Fee Collection
- Total Pending Dues (sum of `fees.amount + fine - paid_amount` where status != 'Paid')
- Total Defaulters Count (distinct students with overdue fees)
- This Month's Expenses
- Cash in Hand (latest `ledger_entries.balance_after` where account_type='Cash')
- Total Bank Balance (sum of `bank_accounts.current_balance`)
- Payroll Status for current month (Paid / Pending / Processing counts)

Charts (Chart.js, same library/version already used in admin dashboard):
- Income vs Expense — last 6 months (bar/line chart)
- Fee Collection by Category — pie/doughnut chart
- Fee Collection trend — daily, current month (line chart)

Recent Activity table: last 10 transactions (fee payments + expenses combined, sorted by date).

### 4.2 Fee Collection (`/accountant/fees`)
- List view: all students' fee challans, filterable by class, section, status (Pending/Paid/Overdue), due date range
- "Collect Payment" action: modal/form to record payment — updates `fees.paid_amount` and `status`, inserts into `fee_payments` (or `fee_payment_transactions` with gateway='Cash'/'Bank')
- Auto-generate `fee_receipts` row on successful payment with unique `receipt_no` (format: e.g. `RCPT-{school_code}-{year}-{sequence}`)
- "Print Receipt" button → PDF view via dompdf, styled like the existing Transfer Certificate (school logo header, DejaVu Sans font, signature/stamp area at footer, same `@page` size config)
- Bulk "Generate Challans" for a class/section for a given month (creates `fees` rows from `fee_structures` for all active students in that class)
- Online transaction monitoring sub-tab: list `fee_payment_transactions` with gateway (JazzCash/EasyPaisa/Bank/Cash), status, and ability to manually mark a "Pending" transaction as "Success"/"Failed" with reason

### 4.3 Fee Structure Management (`/accountant/fee-structure`)
- CRUD for `fee_categories` (school-scoped)
- CRUD for `fee_structures`: assign amount per fee category per class
- Table view: Class-wise fee matrix (rows = classes, columns = fee categories, cells = amounts, editable inline)

### 4.4 Defaulters Report (`/accountant/defaulters`)
- List of students with `fees.status` in ('Pending','Overdue') past due_date
- Filters: class, section, days overdue
- Columns: Student name, admission no, class, father name, mobile number, amount due, fine, days overdue
- Export to PDF / Excel
- "Send Reminder" action (uses existing `notifications`/`messages` table to notify parent via existing notification system)

### 4.5 Payroll Management (`/accountant/payroll`)
- List view of `payrolls` for selected month/year, grouped by status
- "Generate Payroll" for a month: auto-create rows for all active teachers/staff (pull `basic_pay` from teacher record or last month's payroll as base, allow manual adjustment of allowances/deductions before saving)
- Edit individual payroll entry (basic_pay, allowances, deductions → auto-calculate `net_salary`)
- "Mark as Paid" action (bulk + individual) → triggers Observer to create `ledger_entries` debit row
- "Generate Salary Slip" PDF per employee — same dompdf styling pattern as certificates (school header, employee details table, earnings/deductions breakdown table, net pay highlighted, signature line)

### 4.6 Tax Slips (`/accountant/tax-slips`)
- Select employee + tax year → auto-pull gross salary (sum of payrolls for that year) → calculate tax based on configurable tax slabs (store slabs in a simple config array or new `tax_slabs` table if needed — KEEP SIMPLE, e.g. JSON config in `config/tax.php`)
- Save to `tax_slips` table with computed `taxable_income`, `tax_amount`, `net_income`, `slab_applied` (JSON/text)
- "Generate PDF" — same dompdf pattern, professional tax certificate layout

### 4.7 Expenses (`/accountant/expenses`)
- CRUD for expenses with `expense_category_id`, amount, date, payment method, paid_to, attachment upload (store in `storage/app/public/expenses/`)
- Status workflow: Pending → Approved → Paid (Accountant can self-approve if School Admin role isn't required; otherwise School Admin approves — IMPLEMENT a simple flow where Accountant creates as "Pending" and can mark "Approved"/"Paid" directly for v1, since Accountant role already implies trust)
- On status = 'Paid' → trigger ledger entry (debit)
- Voucher PDF print (same dompdf style) showing: voucher no, category, amount, paid to, date, approved by, signature lines

### 4.8 Bank Accounts (`/accountant/bank-accounts`)
- CRUD for `bank_accounts` (school-scoped)
- View transaction history per bank account (filtered `ledger_entries` where account_type='Bank' and bank_account_id matches)
- Manual "Deposit"/"Withdraw" entry form for adjustments (creates `ledger_entries` row + updates `current_balance`)

### 4.9 Cash Book / Ledger (`/accountant/cash-book`)
- Daily/monthly view of all `ledger_entries` (Cash account_type) with running balance column
- Filters: date range, type (Income/Expense), category
- Manual entry form (for misc adjustments not tied to fees/expenses/payroll)
- "Print Day Book" PDF export

### 4.10 Inventory Purchases (`/accountant/inventory-purchases`)
- Use existing `inventory` and `inventory_transactions` tables
- Form to record stock "in" (purchase) transactions — updates `inventory.quantity`, creates `inventory_transactions` row with `type='in'`, `performed_by` = current accountant user id
- Optionally link purchase amount to an `expenses` row (checkbox "Record as Expense" → auto-creates expense entry under "Furniture & Equipment" or matching category)
- List view of recent purchases with supplier, cost, quantity

### 4.11 Financial Reports (`/accountant/reports`)
A single Reports hub page with cards/links to each report (each opens a filterable view with PDF export):
- **Fee Collection Report** — date range, group by class/category/payment method
- **Defaulters Report** — (links to 4.4)
- **Income Statement** — total income (fee collections) vs total expenses vs net, for selected period
- **Expense Report** — by category, date range
- **Payroll Summary Report** — by month, totals of basic pay/allowances/deductions/net
- **Cash Flow / Day Book Report** — (links to 4.9 print)
- **Bank Statement Report** — per bank account

All reports: filterable, table view in-browser, with "Export PDF" and "Export Excel" buttons (use existing export packages if already in `composer.json`, e.g. `maatwebsite/excel` — check first; if not present, PDF-only is acceptable for v1).

### 4.12 Profile & Settings (`/accountant/profile`)
- View/edit own profile (name, email, password change) — reuse existing profile component/view from another role, just re-themed for accountant route prefix.

---

## 5. SIDEBAR / NAVIGATION

Add a new sidebar partial (or extend existing dynamic sidebar if it's permission-driven) for Accountant role with this structure — using the SAME icon set (FontAwesome/Bootstrap Icons — match whatever existing sidebar uses) and SAME active-state styling as School Admin sidebar:

```
- Dashboard
- Fee Collection
    - Collect Fee
    - Fee Challans
    - Online Transactions
    - Fee Receipts
- Fee Structure
- Defaulters
- Payroll
    - Generate Payroll
    - Payroll History
    - Tax Slips
- Expenses
    - All Expenses
    - Expense Categories
- Banking
    - Bank Accounts
    - Cash Book
- Inventory Purchases
- Reports
- My Profile
```

If the sidebar is generated dynamically from `role_permissions` (check existing implementation first), instead add permission-to-menu-item mappings consistent with that existing system.

---

## 6. PDF GENERATION — CONSISTENCY REQUIREMENT

For Fee Receipts, Salary Slips, Tax Slips, Expense Vouchers, and all report exports:
- Reuse the exact dompdf setup already configured for Transfer/School Leaving Certificates (`barryvdh/laravel-dompdf`, DejaVu Sans font, table-based layout for dompdf compatibility, `@page` size rules, footer column percentage pattern).
- Header: school logo (from `schools.logo_path`/`logo`), school name, address, phone — pulled dynamically per `school_id`.
- Footer: "Generated by NewMkhanSchool Accountant Portal" + generation timestamp + signature lines (Prepared By / Checked By / Approved By).
- Create a shared Blade layout `resources/views/pdf/layout.blade.php` if one doesn't already exist, so all financial PDFs inherit consistent header/footer — but ONLY if this doesn't conflict with the existing certificate PDF setup. If a shared layout already exists, REUSE it.

---

## 7. ROUTES SUMMARY (add to routes/web.php, inside Accountant group)

```php
Route::middleware(['auth', 'role:Accountant'])->prefix('accountant')->name('accountant.')->group(function () {
    Route::get('dashboard', [AccountantDashboardController::class, 'index'])->name('dashboard');

    Route::resource('fees', AccountantFeeController::class);
    Route::post('fees/{fee}/collect', [AccountantFeeController::class, 'collectPayment'])->name('fees.collect');
    Route::get('fees/{fee}/receipt', [AccountantFeeController::class, 'printReceipt'])->name('fees.receipt');
    Route::post('fees/generate-challans', [AccountantFeeController::class, 'generateChallans'])->name('fees.generate-challans');
    Route::get('transactions', [FeeTransactionController::class, 'index'])->name('transactions.index');

    Route::resource('fee-structure', FeeStructureController::class);
    Route::resource('fee-categories', FeeCategoryController::class);

    Route::get('defaulters', [DefaulterController::class, 'index'])->name('defaulters.index');
    Route::post('defaulters/{student}/remind', [DefaulterController::class, 'remind'])->name('defaulters.remind');

    Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');
    Route::put('payroll/{payroll}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::post('payroll/{payroll}/mark-paid', [PayrollController::class, 'markPaid'])->name('payroll.mark-paid');
    Route::get('payroll/{payroll}/slip', [PayrollController::class, 'slip'])->name('payroll.slip');

    Route::resource('tax-slips', TaxSlipController::class);
    Route::get('tax-slips/{taxSlip}/pdf', [TaxSlipController::class, 'pdf'])->name('tax-slips.pdf');

    Route::resource('expenses', ExpenseController::class);
    Route::resource('expense-categories', ExpenseCategoryController::class);
    Route::post('expenses/{expense}/status', [ExpenseController::class, 'updateStatus'])->name('expenses.status');
    Route::get('expenses/{expense}/voucher', [ExpenseController::class, 'voucher'])->name('expenses.voucher');

    Route::resource('bank-accounts', BankAccountController::class);
    Route::post('bank-accounts/{bankAccount}/transaction', [BankAccountController::class, 'transaction'])->name('bank-accounts.transaction');

    Route::get('cash-book', [CashBookController::class, 'index'])->name('cash-book.index');
    Route::post('cash-book', [CashBookController::class, 'store'])->name('cash-book.store');
    Route::get('cash-book/print', [CashBookController::class, 'print'])->name('cash-book.print');

    Route::get('inventory-purchases', [InventoryPurchaseController::class, 'index'])->name('inventory-purchases.index');
    Route::post('inventory-purchases', [InventoryPurchaseController::class, 'store'])->name('inventory-purchases.store');

    Route::get('reports', [FinancialReportController::class, 'index'])->name('reports.index');
    Route::get('reports/fee-collection', [FinancialReportController::class, 'feeCollection'])->name('reports.fee-collection');
    Route::get('reports/income-statement', [FinancialReportController::class, 'incomeStatement'])->name('reports.income-statement');
    Route::get('reports/expenses', [FinancialReportController::class, 'expenses'])->name('reports.expenses');
    Route::get('reports/payroll-summary', [FinancialReportController::class, 'payrollSummary'])->name('reports.payroll-summary');
    Route::get('reports/bank-statement', [FinancialReportController::class, 'bankStatement'])->name('reports.bank-statement');

    Route::get('profile', [AccountantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [AccountantProfileController::class, 'update'])->name('profile.update');
});
```

---

## 8. SEEDERS

- `RoleSeeder` — add Accountant role (use `updateOrInsert` to avoid duplicate errors on re-run)
- `PermissionSeeder` — add new permissions 44-52 (use `updateOrInsert`)
- `RolePermissionSeeder` — map Accountant → permissions listed in section 1.3
- `ExpenseCategorySeeder` — seed default categories per existing school(s) in `schools` table
- Optional: `AccountantUserSeeder` — create one demo Accountant user (email: `accountant@<schoolcode>.com`, password: `password` hashed) for testing — only in local/dev seeding, clearly marked.

---

## 9. NON-FUNCTIONAL REQUIREMENTS

- All currency values formatted with thousand separators and "Rs." prefix (PKR), consistent with any existing fee display formatting.
- All dates in `d-M-Y` format (or whatever format existing views use — match it).
- All list views must support pagination (15-20 rows/page) and a search box, matching existing admin table style (DataTables if already used elsewhere).
- All monetary inputs validated as numeric, min:0.
- School-scoping: every query MUST filter by `auth()->user()->school_id` (or branch logic if multi-branch admin) — NEVER show cross-school data to an Accountant.
- Authorization: every controller action must check `role:Accountant` middleware AND relevant permission via existing permission-check helper/gate, consistent with how School Admin controllers do it.
- Activity logging: log key actions (fee collection, expense approval, payroll mark-paid) into existing `audit_logs` table (action, model_type, model_id, description, user_id, ip_address).

---

## 10. EXECUTION PHASES (build in this order)

1. **Phase 1 — Foundation**: Role/permission seeders, middleware, route group skeleton, sidebar entry, dashboard (with dummy widgets first), base layout extension.
2. **Phase 2 — Migrations & Models**: All new tables (section 2) + models + relationships + Observers for ledger auto-entries.
3. **Phase 3 — Fee Collection module** (4.2) + Fee Structure (4.3) + Receipts PDF.
4. **Phase 4 — Defaulters (4.4)**.
5. **Phase 5 — Payroll (4.5) + Tax Slips (4.6)** + Salary Slip / Tax Slip PDFs.
6. **Phase 6 — Expenses (4.7) + Bank Accounts (4.8) + Cash Book (4.9)** + voucher PDFs.
7. **Phase 7 — Inventory Purchases (4.10)**.
8. **Phase 8 — Financial Reports hub (4.11)** + all report views + PDF/Excel export.
9. **Phase 9 — Dashboard final widgets/charts** (now that all data sources exist) + Profile (4.12).
10. **Phase 10 — Full QA pass**: test as Accountant user across both/all schools/branches in `schools` table, verify school-scoping, verify no regression in Super Admin/School Admin/Teacher/Student/Parent portals.

---

## 11. DELIVERABLES CHECKLIST

- [ ] Migrations for `expense_categories`, `bank_accounts`, `expenses`, `ledger_entries`
- [ ] Models + relationships + Observers
- [ ] Role + Permissions + RolePermission seeders (Accountant)
- [ ] Middleware/route group for Accountant
- [ ] Sidebar navigation entry
- [ ] Dashboard with widgets + 3 charts
- [ ] Fee Collection module + receipts PDF
- [ ] Fee Structure / Fee Categories CRUD
- [ ] Defaulters report + reminder action
- [ ] Payroll module + salary slip PDF
- [ ] Tax slip generation + PDF
- [ ] Expenses module + voucher PDF
- [ ] Bank accounts module
- [ ] Cash book / ledger module + print
- [ ] Inventory purchases module
- [ ] Financial reports hub (6 reports) with PDF export
- [ ] Accountant profile page
- [ ] Demo Accountant user seeded
- [ ] No regressions in existing portals (manually verified)

---

**END OF PROMPT — Execute phases sequentially. After each phase, run `php artisan migrate` (if new migrations added) and confirm no errors before proceeding to next phase.**
