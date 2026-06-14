<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AccountantRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Role
        $roleId = DB::table('roles')->insertGetId([
            'name' => 'Accountant',
            'description' => 'Manages fees, payroll, expenses, and financial reports for the school'
        ]);

        // 2. Permissions
        $permissions = [
            ['id' => 44, 'name' => 'Manage Fee Collection', 'slug' => 'manage_fee_collection'],
            ['id' => 45, 'name' => 'Manage Fee Structure', 'slug' => 'manage_fee_structure'],
            ['id' => 46, 'name' => 'View Financial Reports', 'slug' => 'view_financial_reports'],
            ['id' => 47, 'name' => 'Manage Expenses', 'slug' => 'manage_expenses'],
            ['id' => 48, 'name' => 'View Expenses', 'slug' => 'view_expenses'],
            ['id' => 49, 'name' => 'Manage Bank Accounts', 'slug' => 'manage_bank_accounts'],
            ['id' => 50, 'name' => 'Manage Cash Book', 'slug' => 'manage_cash_book'],
            ['id' => 51, 'name' => 'Generate Tax Slips', 'slug' => 'generate_tax_slips'],
            ['id' => 52, 'name' => 'Manage Inventory Purchases', 'slug' => 'manage_inventory_purchases'],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->updateOrInsert(
                ['id' => $perm['id']],
                [
                    'name' => $perm['name'],
                    'slug' => $perm['slug']
                ]
            );
        }

        // 3. Role-Permission Mapping
        $accountantPermissions = [
            'view_dashboard',
            'view_fees', 'manage_fees', 'manage_fee_collection', 'view_own_fees',
            'manage_fee_structure',
            'manage_payroll',
            'generate_tax_slips',
            'manage_expenses', 'view_expenses',
            'manage_bank_accounts',
            'manage_cash_book',
            'manage_inventory_purchases',
            'view_reports', 'generate_reports', 'view_financial_reports',
            'view_own_profile', 'edit_own_profile',
            'view_announcements',
            'send_messages'
        ];

        $permissionIds = DB::table('permissions')->whereIn('slug', $accountantPermissions)->pluck('id');

        foreach ($permissionIds as $permId) {
            DB::table('role_permissions')->updateOrInsert([
                'role_id' => $roleId,
                'permission_id' => $permId
            ]);
        }

        // 4. Create Demo Accountant User
        User::updateOrCreate(
            ['email' => 'accountant@school.com'],
            [
                'name' => 'Demo Accountant',
                'password_hash' => Hash::make('password'),
                'role_id' => $roleId,
                'school_id' => 1, // Assuming school ID 1 exists
                'status' => 'active'
            ]
        );
    }
}
