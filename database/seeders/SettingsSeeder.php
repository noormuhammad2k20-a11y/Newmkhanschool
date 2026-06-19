<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'name' => 'General Settings',
                'slug' => 'general',
                'icon' => 'ri-settings-3-line',
                'description' => 'Application name, branding, locale and core configuration',
                'order' => 1,
            ],
            [
                'name' => 'School / Organization',
                'slug' => 'school',
                'icon' => 'ri-school-line',
                'description' => 'School identity, officials, academic sessions',
                'order' => 2,
            ],
            [
                'name' => 'Certificate Settings',
                'slug' => 'certificate',
                'icon' => 'ri-award-line',
                'description' => 'Certificate layout, watermarks, signatures, QR codes',
                'order' => 3,
            ],
            [
                'name' => 'Student Management',
                'slug' => 'student',
                'icon' => 'ri-graduation-cap-line',
                'description' => 'Admission formats, roll numbers, promotion rules',
                'order' => 4,
            ],
            [
                'name' => 'Examination Settings',
                'slug' => 'examination',
                'icon' => 'ri-draft-line',
                'description' => 'Exam types, grading system, result generation',
                'order' => 5,
            ],
            [
                'name' => 'User & Security',
                'slug' => 'security',
                'icon' => 'ri-shield-user-line',
                'description' => 'Password policies, session management, audit logs',
                'order' => 6,
            ],
            [
                'name' => 'Notification Settings',
                'slug' => 'notification',
                'icon' => 'ri-notification-3-line',
                'description' => 'Email, SMS, WhatsApp, system notification configuration',
                'order' => 7,
            ],
            [
                'name' => 'Appearance',
                'slug' => 'appearance',
                'icon' => 'ri-palette-line',
                'description' => 'Sidebar, dashboard, colors, branding, typography',
                'order' => 8,
            ],
            [
                'name' => 'System Maintenance',
                'slug' => 'maintenance',
                'icon' => 'ri-tools-line',
                'description' => 'Backups, cache, queues, logs, health monitoring',
                'order' => 9,
            ],
            [
                'name' => 'API & Integrations',
                'slug' => 'api',
                'icon' => 'ri-plug-line',
                'description' => 'Third-party APIs, payment gateways, external services',
                'order' => 10,
            ],
        ];

        foreach ($groups as $group) {
            DB::table('setting_groups')->updateOrInsert(
                ['slug' => $group['slug']],
                $group
            );
        }

        // Fetch group IDs
        $groupIds = DB::table('setting_groups')->pluck('id', 'slug');

        $settings = [
            // ── General Settings ──────────────────────────────────
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.app_name', 'value' => env('APP_NAME', 'School Management System'), 'type' => 'text', 'label' => 'Application Name', 'description' => 'The name displayed in the browser title and header', 'is_public' => true, 'order' => 1],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.system_logo', 'value' => null, 'type' => 'image', 'label' => 'System Logo', 'description' => 'Upload your organization logo (PNG, JPG, SVG — max 2MB)', 'is_public' => true, 'order' => 2],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.favicon', 'value' => null, 'type' => 'image', 'label' => 'Favicon', 'description' => 'Browser tab icon (ICO, PNG, SVG — max 512KB)', 'is_public' => true, 'order' => 3],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.organization_name', 'value' => 'State Education Department', 'type' => 'text', 'label' => 'Organization Name', 'description' => 'Top-level organization or department name', 'is_public' => true, 'order' => 4],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.address', 'value' => env('SCHOOL_ADDRESS', '123 Education Street, Learning City'), 'type' => 'textarea', 'label' => 'Address', 'description' => 'Full postal address', 'is_public' => true, 'order' => 5],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.phone', 'value' => env('SCHOOL_PHONE', '+92 300 1234567'), 'type' => 'text', 'label' => 'Phone Number', 'description' => 'Primary contact phone number', 'is_public' => true, 'order' => 6],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.email', 'value' => 'admin@school.com', 'type' => 'email', 'label' => 'Email Address', 'description' => 'Primary contact email', 'is_public' => true, 'order' => 7],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.website', 'value' => env('APP_URL', 'http://localhost'), 'type' => 'url', 'label' => 'Website URL', 'description' => 'Official website address', 'is_public' => true, 'order' => 8],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.timezone', 'value' => 'Asia/Karachi', 'type' => 'select', 'label' => 'Timezone', 'description' => 'System timezone for dates and times', 'options' => json_encode([['label' => 'Asia/Karachi (PKT)', 'value' => 'Asia/Karachi'], ['label' => 'UTC', 'value' => 'UTC'], ['label' => 'Asia/Dubai (GST)', 'value' => 'Asia/Dubai'], ['label' => 'Asia/Kolkata (IST)', 'value' => 'Asia/Kolkata'], ['label' => 'Europe/London (GMT)', 'value' => 'Europe/London'], ['label' => 'America/New_York (EST)', 'value' => 'America/New_York']]), 'order' => 9],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.date_format', 'value' => 'd-m-Y', 'type' => 'select', 'label' => 'Date Format', 'description' => 'How dates are displayed across the system', 'options' => json_encode([['label' => 'DD-MM-YYYY', 'value' => 'd-m-Y'], ['label' => 'MM/DD/YYYY', 'value' => 'm/d/Y'], ['label' => 'YYYY-MM-DD', 'value' => 'Y-m-d'], ['label' => 'DD/MM/YYYY', 'value' => 'd/m/Y']]), 'order' => 10],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.currency', 'value' => 'PKR', 'type' => 'select', 'label' => 'Currency', 'description' => 'Default currency for financial displays', 'options' => json_encode([['label' => 'PKR (₨)', 'value' => 'PKR'], ['label' => 'USD ($)', 'value' => 'USD'], ['label' => 'EUR (€)', 'value' => 'EUR'], ['label' => 'GBP (£)', 'value' => 'GBP'], ['label' => 'INR (₹)', 'value' => 'INR']]), 'order' => 11],
            ['setting_group_id' => $groupIds['general'], 'key' => 'general.language', 'value' => 'en', 'type' => 'select', 'label' => 'Language', 'description' => 'System default language', 'options' => json_encode([['label' => 'English', 'value' => 'en'], ['label' => 'Urdu', 'value' => 'ur'], ['label' => 'Arabic', 'value' => 'ar'], ['label' => 'Sindhi', 'value' => 'sd']]), 'order' => 12],

            // ── School / Organization ─────────────────────────────
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.name', 'value' => env('SCHOOL_NAME', 'MKhan School'), 'type' => 'text', 'label' => 'School Name', 'description' => 'Official school name used on certificates and documents', 'is_public' => true, 'order' => 1],
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.code', 'value' => '', 'type' => 'text', 'label' => 'School Code', 'description' => 'Unique school identification code', 'order' => 2],
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.board_name', 'value' => 'Board of Secondary Education', 'type' => 'text', 'label' => 'Board Name', 'description' => 'Education board the school is affiliated with', 'order' => 3],
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.principal_name', 'value' => '', 'type' => 'text', 'label' => 'Principal Name', 'description' => 'Name of the current principal', 'order' => 4],
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.headmaster_name', 'value' => '', 'type' => 'text', 'label' => 'Headmaster Name', 'description' => 'Name of the headmaster/headmistress', 'order' => 5],
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.official_signature', 'value' => null, 'type' => 'image', 'label' => 'Official Signature', 'description' => 'Upload the principal\'s or authorized signature image (PNG with transparent background)', 'order' => 6],
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.official_stamp', 'value' => null, 'type' => 'image', 'label' => 'Official Stamp / Seal', 'description' => 'Upload the school\'s official stamp or seal image (PNG with transparent background)', 'order' => 7],
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.academic_session', 'value' => 'April-March', 'type' => 'select', 'label' => 'Academic Session', 'description' => 'Academic session cycle', 'options' => json_encode([['label' => 'April - March', 'value' => 'April-March'], ['label' => 'January - December', 'value' => 'January-December'], ['label' => 'September - August', 'value' => 'September-August']]), 'order' => 8],
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.academic_year', 'value' => '2025-2026', 'type' => 'text', 'label' => 'Current Academic Year', 'description' => 'Currently active academic year', 'order' => 9],
            ['setting_group_id' => $groupIds['school'], 'key' => 'school.contact_info', 'value' => '', 'type' => 'textarea', 'label' => 'Additional Contact Information', 'description' => 'Additional contact details, social media links, etc.', 'order' => 10],

            // ── Certificate Settings ──────────────────────────────
            ['setting_group_id' => $groupIds['certificate'], 'key' => 'certificate.header_text', 'value' => '', 'type' => 'textarea', 'label' => 'Certificate Header', 'description' => 'Custom header text for certificates', 'order' => 1],
            ['setting_group_id' => $groupIds['certificate'], 'key' => 'certificate.footer_text', 'value' => '', 'type' => 'textarea', 'label' => 'Certificate Footer', 'description' => 'Custom footer text for certificates', 'order' => 2],
            ['setting_group_id' => $groupIds['certificate'], 'key' => 'certificate.watermark', 'value' => null, 'type' => 'image', 'label' => 'Watermark Image', 'description' => 'Watermark image for certificate background (PNG recommended)', 'order' => 3],
            ['setting_group_id' => $groupIds['certificate'], 'key' => 'certificate.watermark_opacity', 'value' => '15', 'type' => 'number', 'label' => 'Watermark Opacity (%)', 'description' => 'Opacity percentage for the watermark (0-100)', 'order' => 4],
            ['setting_group_id' => $groupIds['certificate'], 'key' => 'certificate.signature_position', 'value' => 'bottom-right', 'type' => 'select', 'label' => 'Signature Position', 'description' => 'Default position of signature on certificates', 'options' => json_encode([['label' => 'Bottom Right', 'value' => 'bottom-right'], ['label' => 'Bottom Left', 'value' => 'bottom-left'], ['label' => 'Bottom Center', 'value' => 'bottom-center']]), 'order' => 5],
            ['setting_group_id' => $groupIds['certificate'], 'key' => 'certificate.seal_position', 'value' => 'bottom-left', 'type' => 'select', 'label' => 'Seal Position', 'description' => 'Default position of seal on certificates', 'options' => json_encode([['label' => 'Bottom Left', 'value' => 'bottom-left'], ['label' => 'Bottom Right', 'value' => 'bottom-right'], ['label' => 'Center', 'value' => 'center']]), 'order' => 6],
            ['setting_group_id' => $groupIds['certificate'], 'key' => 'certificate.qr_enabled', 'value' => '1', 'type' => 'toggle', 'label' => 'Enable QR Code', 'description' => 'Add QR code for verification on certificates', 'order' => 7],
            ['setting_group_id' => $groupIds['certificate'], 'key' => 'certificate.verification_url', 'value' => '', 'type' => 'url', 'label' => 'Verification URL', 'description' => 'Base URL for QR code certificate verification', 'order' => 8],
            ['setting_group_id' => $groupIds['certificate'], 'key' => 'certificate.number_format', 'value' => 'CERT-{YEAR}-{SEQ}', 'type' => 'text', 'label' => 'Certificate Number Format', 'description' => 'Format pattern: {YEAR}, {MONTH}, {SEQ}, {TYPE}', 'order' => 9],

            // ── Student Management ────────────────────────────────
            ['setting_group_id' => $groupIds['student'], 'key' => 'student.admission_format', 'value' => 'ADM-{YEAR}-{SEQ}', 'type' => 'text', 'label' => 'Admission Number Format', 'description' => 'Format: {YEAR}, {SEQ}, {CLASS}, {BRANCH}', 'order' => 1],
            ['setting_group_id' => $groupIds['student'], 'key' => 'student.roll_format', 'value' => '{SEQ}', 'type' => 'text', 'label' => 'Roll Number Format', 'description' => 'Format: {SEQ}, {CLASS}, {SECTION}', 'order' => 2],
            ['setting_group_id' => $groupIds['student'], 'key' => 'student.auto_id', 'value' => '1', 'type' => 'toggle', 'label' => 'Auto-generate Student IDs', 'description' => 'Automatically generate admission and roll numbers', 'order' => 3],
            ['setting_group_id' => $groupIds['student'], 'key' => 'student.promotion_rules', 'value' => json_encode(['min_attendance' => 75, 'min_marks_percent' => 33]), 'type' => 'json', 'label' => 'Promotion Rules', 'description' => 'JSON configuration for automatic promotion criteria', 'order' => 4],
            ['setting_group_id' => $groupIds['student'], 'key' => 'student.status_options', 'value' => json_encode(['Active', 'Inactive', 'Graduated', 'Transferred', 'Expelled']), 'type' => 'json', 'label' => 'Student Status Options', 'description' => 'Available status values for students', 'order' => 5],

            // ── Examination Settings ──────────────────────────────
            ['setting_group_id' => $groupIds['examination'], 'key' => 'examination.exam_types', 'value' => json_encode(['Monthly Test', 'Mid Term', 'Final Term', 'Unit Test', 'Practical']), 'type' => 'json', 'label' => 'Exam Types', 'description' => 'List of available examination types', 'order' => 1],
            ['setting_group_id' => $groupIds['examination'], 'key' => 'examination.grading_system', 'value' => json_encode([['grade' => 'A+', 'min' => 90, 'max' => 100], ['grade' => 'A', 'min' => 80, 'max' => 89], ['grade' => 'B', 'min' => 70, 'max' => 79], ['grade' => 'C', 'min' => 60, 'max' => 69], ['grade' => 'D', 'min' => 50, 'max' => 59], ['grade' => 'F', 'min' => 0, 'max' => 49]]), 'type' => 'json', 'label' => 'Grading System', 'description' => 'Grade boundaries and labels', 'order' => 2],
            ['setting_group_id' => $groupIds['examination'], 'key' => 'examination.passing_marks', 'value' => '33', 'type' => 'number', 'label' => 'Passing Marks (%)', 'description' => 'Minimum percentage required to pass', 'order' => 3],
            ['setting_group_id' => $groupIds['examination'], 'key' => 'examination.marks_calculation', 'value' => 'percentage', 'type' => 'select', 'label' => 'Marks Calculation Method', 'description' => 'How final marks are calculated', 'options' => json_encode([['label' => 'Percentage Based', 'value' => 'percentage'], ['label' => 'GPA Based', 'value' => 'gpa'], ['label' => 'Grade Based', 'value' => 'grade']]), 'order' => 4],
            ['setting_group_id' => $groupIds['examination'], 'key' => 'examination.auto_result', 'value' => '1', 'type' => 'toggle', 'label' => 'Auto-generate Results', 'description' => 'Automatically calculate and publish results after marks entry', 'order' => 5],

            // ── User & Security ───────────────────────────────────
            ['setting_group_id' => $groupIds['security'], 'key' => 'security.min_password_length', 'value' => '8', 'type' => 'number', 'label' => 'Minimum Password Length', 'description' => 'Minimum required characters for passwords', 'order' => 1],
            ['setting_group_id' => $groupIds['security'], 'key' => 'security.require_special_char', 'value' => '0', 'type' => 'toggle', 'label' => 'Require Special Characters', 'description' => 'Passwords must contain special characters', 'order' => 2],
            ['setting_group_id' => $groupIds['security'], 'key' => 'security.session_timeout', 'value' => '120', 'type' => 'number', 'label' => 'Session Timeout (minutes)', 'description' => 'Auto-logout after inactivity period', 'order' => 3],
            ['setting_group_id' => $groupIds['security'], 'key' => 'security.max_login_attempts', 'value' => '5', 'type' => 'number', 'label' => 'Max Login Attempts', 'description' => 'Maximum failed login attempts before lockout', 'order' => 4],
            ['setting_group_id' => $groupIds['security'], 'key' => 'security.lockout_duration', 'value' => '15', 'type' => 'number', 'label' => 'Lockout Duration (minutes)', 'description' => 'How long account is locked after max attempts', 'order' => 5],
            ['setting_group_id' => $groupIds['security'], 'key' => 'security.enable_audit_log', 'value' => '1', 'type' => 'toggle', 'label' => 'Enable Audit Logging', 'description' => 'Track all user actions and changes', 'order' => 6],
            ['setting_group_id' => $groupIds['security'], 'key' => 'security.enable_login_history', 'value' => '1', 'type' => 'toggle', 'label' => 'Enable Login History', 'description' => 'Record login timestamps and IP addresses', 'order' => 7],
            ['setting_group_id' => $groupIds['security'], 'key' => 'security.two_factor_auth', 'value' => '0', 'type' => 'toggle', 'label' => 'Two-Factor Authentication', 'description' => 'Enable 2FA for admin accounts', 'order' => 8],
            ['setting_group_id' => $groupIds['security'], 'key' => 'security.ip_whitelist', 'value' => '', 'type' => 'textarea', 'label' => 'IP Whitelist', 'description' => 'Comma-separated list of allowed IP addresses (leave empty for all)', 'order' => 9],

            // ── Notification Settings ─────────────────────────────
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.email_enabled', 'value' => '0', 'type' => 'toggle', 'label' => 'Enable Email Notifications', 'description' => 'Send email notifications for system events', 'order' => 1],
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.smtp_host', 'value' => '127.0.0.1', 'type' => 'text', 'label' => 'SMTP Host', 'description' => 'SMTP server hostname', 'order' => 2],
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.smtp_port', 'value' => '587', 'type' => 'number', 'label' => 'SMTP Port', 'description' => 'SMTP server port number', 'order' => 3],
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.smtp_username', 'value' => '', 'type' => 'text', 'label' => 'SMTP Username', 'description' => 'SMTP authentication username', 'order' => 4],
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.smtp_password', 'value' => '', 'type' => 'text', 'label' => 'SMTP Password', 'description' => 'SMTP authentication password', 'order' => 5],
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.sms_enabled', 'value' => '0', 'type' => 'toggle', 'label' => 'Enable SMS Notifications', 'description' => 'Send SMS notifications via gateway', 'order' => 6],
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.sms_gateway_url', 'value' => '', 'type' => 'url', 'label' => 'SMS Gateway API URL', 'description' => 'API endpoint for SMS gateway service', 'order' => 7],
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.sms_api_key', 'value' => '', 'type' => 'text', 'label' => 'SMS API Key', 'description' => 'API key for SMS gateway authentication', 'order' => 8],
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.whatsapp_enabled', 'value' => '0', 'type' => 'toggle', 'label' => 'Enable WhatsApp Notifications', 'description' => 'Send notifications via WhatsApp Business API', 'order' => 9],
            ['setting_group_id' => $groupIds['notification'], 'key' => 'notification.whatsapp_api_key', 'value' => '', 'type' => 'text', 'label' => 'WhatsApp API Key', 'description' => 'WhatsApp Business API key', 'order' => 10],

            // ── Appearance ────────────────────────────────────────
            ['setting_group_id' => $groupIds['appearance'], 'key' => 'appearance.sidebar_style', 'value' => 'default', 'type' => 'select', 'label' => 'Sidebar Style', 'description' => 'Visual style for the navigation sidebar', 'options' => json_encode([['label' => 'Default', 'value' => 'default'], ['label' => 'Compact', 'value' => 'compact'], ['label' => 'Expanded', 'value' => 'expanded']]), 'order' => 1],
            ['setting_group_id' => $groupIds['appearance'], 'key' => 'appearance.primary_color', 'value' => '#000666', 'type' => 'color', 'label' => 'Primary Color', 'description' => 'Main brand color used throughout the system', 'order' => 2],
            ['setting_group_id' => $groupIds['appearance'], 'key' => 'appearance.accent_color', 'value' => '#4c56af', 'type' => 'color', 'label' => 'Accent Color', 'description' => 'Secondary accent color', 'order' => 3],
            ['setting_group_id' => $groupIds['appearance'], 'key' => 'appearance.dark_mode', 'value' => '0', 'type' => 'toggle', 'label' => 'Default Dark Mode', 'description' => 'Enable dark mode as the default theme', 'order' => 4],
            ['setting_group_id' => $groupIds['appearance'], 'key' => 'appearance.login_bg_image', 'value' => null, 'type' => 'image', 'label' => 'Login Page Background', 'description' => 'Custom background image for the login page', 'order' => 5],
            ['setting_group_id' => $groupIds['appearance'], 'key' => 'appearance.dashboard_layout', 'value' => 'default', 'type' => 'select', 'label' => 'Dashboard Layout', 'description' => 'Choose dashboard card arrangement', 'options' => json_encode([['label' => 'Default Grid', 'value' => 'default'], ['label' => 'Compact', 'value' => 'compact'], ['label' => 'Wide', 'value' => 'wide']]), 'order' => 6],

            // ── System Maintenance ────────────────────────────────
            ['setting_group_id' => $groupIds['maintenance'], 'key' => 'maintenance.auto_backup', 'value' => '0', 'type' => 'toggle', 'label' => 'Auto Backup', 'description' => 'Enable automatic scheduled database backups', 'order' => 1],
            ['setting_group_id' => $groupIds['maintenance'], 'key' => 'maintenance.backup_frequency', 'value' => 'daily', 'type' => 'select', 'label' => 'Backup Frequency', 'description' => 'How often to run automatic backups', 'options' => json_encode([['label' => 'Daily', 'value' => 'daily'], ['label' => 'Weekly', 'value' => 'weekly'], ['label' => 'Monthly', 'value' => 'monthly']]), 'order' => 2],
            ['setting_group_id' => $groupIds['maintenance'], 'key' => 'maintenance.backup_retention', 'value' => '30', 'type' => 'number', 'label' => 'Backup Retention (days)', 'description' => 'Number of days to keep backup files', 'order' => 3],
            ['setting_group_id' => $groupIds['maintenance'], 'key' => 'maintenance.maintenance_mode', 'value' => '0', 'type' => 'toggle', 'label' => 'Maintenance Mode', 'description' => 'Put the application in maintenance mode', 'order' => 4],
            ['setting_group_id' => $groupIds['maintenance'], 'key' => 'maintenance.maintenance_message', 'value' => 'The system is currently undergoing maintenance. Please try again later.', 'type' => 'textarea', 'label' => 'Maintenance Message', 'description' => 'Message displayed to users during maintenance', 'order' => 5],
            ['setting_group_id' => $groupIds['maintenance'], 'key' => 'maintenance.enable_cache', 'value' => '1', 'type' => 'toggle', 'label' => 'Enable Caching', 'description' => 'Enable application caching for improved performance', 'order' => 6],
            ['setting_group_id' => $groupIds['maintenance'], 'key' => 'maintenance.log_level', 'value' => 'error', 'type' => 'select', 'label' => 'Log Level', 'description' => 'Minimum severity level for logging', 'options' => json_encode([['label' => 'Debug', 'value' => 'debug'], ['label' => 'Info', 'value' => 'info'], ['label' => 'Warning', 'value' => 'warning'], ['label' => 'Error', 'value' => 'error']]), 'order' => 7],
            ['setting_group_id' => $groupIds['maintenance'], 'key' => 'maintenance.health_check_enabled', 'value' => '1', 'type' => 'toggle', 'label' => 'Enable Health Monitoring', 'description' => 'Monitor system health (database, storage, queue)', 'order' => 8],

            // ── API & Integrations ────────────────────────────────
            ['setting_group_id' => $groupIds['api'], 'key' => 'api.jazzcash_merchant_id', 'value' => env('JAZZCASH_MERCHANT_ID', ''), 'type' => 'text', 'label' => 'JazzCash Merchant ID', 'description' => 'JazzCash payment gateway merchant ID', 'order' => 1],
            ['setting_group_id' => $groupIds['api'], 'key' => 'api.jazzcash_password', 'value' => env('JAZZCASH_PASSWORD', ''), 'type' => 'text', 'label' => 'JazzCash Password', 'description' => 'JazzCash payment gateway password', 'order' => 2],
            ['setting_group_id' => $groupIds['api'], 'key' => 'api.jazzcash_salt', 'value' => env('JAZZCASH_INTEGRITY_SALT', ''), 'type' => 'text', 'label' => 'JazzCash Integrity Salt', 'description' => 'JazzCash integrity salt for hash verification', 'order' => 3],
            ['setting_group_id' => $groupIds['api'], 'key' => 'api.easypaisa_store_id', 'value' => env('EASYPAISA_STORE_ID', ''), 'type' => 'text', 'label' => 'EasyPaisa Store ID', 'description' => 'EasyPaisa payment gateway store ID', 'order' => 4],
            ['setting_group_id' => $groupIds['api'], 'key' => 'api.easypaisa_hash_key', 'value' => env('EASYPAISA_HASH_KEY', ''), 'type' => 'text', 'label' => 'EasyPaisa Hash Key', 'description' => 'EasyPaisa hash key for verification', 'order' => 5],
            ['setting_group_id' => $groupIds['api'], 'key' => 'api.gemini_api_key', 'value' => env('GEMINI_API_KEY', ''), 'type' => 'text', 'label' => 'Gemini AI API Key', 'description' => 'Google Gemini AI API key for AI features', 'order' => 6],
            ['setting_group_id' => $groupIds['api'], 'key' => 'api.openai_api_key', 'value' => env('OPENAI_API_KEY', ''), 'type' => 'text', 'label' => 'OpenAI API Key', 'description' => 'OpenAI API key for document enhancement', 'order' => 7],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
