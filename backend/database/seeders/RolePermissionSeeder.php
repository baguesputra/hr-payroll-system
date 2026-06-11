<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Employee
            'employee.view',
            'employee.create',
            'employee.update',
            'employee.delete',
            'employee.export',

            // Attendance
            'attendance.view',
            'attendance.create',
            'attendance.update',
            'attendance.import',
            'attendance.correct',

            // Shift
            'shift.view',
            'shift.create',
            'shift.update',
            'shift.delete',
            'shift.approve-request',

            // Leave
            'leave.view',
            'leave.view-all',
            'leave.create',
            'leave.approve',
            'leave.manage-types',
            'leave.manage-balance',

            // Overtime
            'overtime.view',
            'overtime.view-all',
            'overtime.create',
            'overtime.approve',

            // Payroll
            'payroll.view',
            'payroll.process',
            'payroll.approve',
            'payroll.export',

            // Payslip
            'payslip.view',
            'payslip.view-own',
            'payslip.generate',
            'payslip.send',

            // Report
            'report.view',
            'report.export',

            // Audit
            'audit.view',

            // Settings
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $this->createSuperAdminRole();
        $this->createHRManagerRole();
        $this->createFinanceRole();
        $this->createEmployeeRole();
    }

    private function createSuperAdminRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        // Super admin dapat semua permission
        $role->givePermissionTo(Permission::all());
    }

    private function createHRManagerRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'hr_manager']);
        $role->givePermissionTo([
            'employee.view',
            'employee.create',
            'employee.update',
            'employee.export',

            'attendance.view',
            'attendance.create',
            'attendance.update',
            'attendance.import',
            'attendance.correct',

            'shift.view',
            'shift.create',
            'shift.update',
            'shift.approve-request',

            'leave.view',
            'leave.view-all',
            'leave.create',
            'leave.approve',
            'leave.manage-types',
            'leave.manage-balance',

            'overtime.view',
            'overtime.view-all',
            'overtime.approve',

            'payroll.view',

            'payslip.view',
            'payslip.generate',
            'payslip.send',

            'report.view',
            'report.export',
        ]);
    }

    private function createFinanceRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'finance']);
        $role->givePermissionTo([
            'employee.view',

            'payroll.view',
            'payroll.process',
            'payroll.approve',
            'payroll.export',

            'payslip.view',
            'payslip.generate',
            'payslip.send',

            'report.view',
            'report.export',
        ]);
    }

    private function createEmployeeRole(): void
    {
        $role = Role::firstOrCreate(['name' => 'employee']);
        $role->givePermissionTo([
            'leave.view',
            'leave.create',

            'overtime.view',
            'overtime.create',

            'payslip.view-own',
        ]);
    }
}