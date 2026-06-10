export const ROUTES = {
  // Auth
  LOGIN: '/login',

  // Dashboard
  DASHBOARD: '/dashboard',

  // Employee
  EMPLOYEES: '/employees',
  EMPLOYEE_CREATE: '/employees/create',
  EMPLOYEE_DETAIL: '/employees/:id',
  EMPLOYEE_EDIT: '/employees/:id/edit',

  // Attendance
  ATTENDANCE: '/attendance',

  // Shift
  SHIFTS: '/shifts',

  // Leave
  LEAVES: '/leaves',
  LEAVE_CREATE: '/leaves/create',

  // Overtime
  OVERTIME: '/overtime',
  OVERTIME_CREATE: '/overtime/create',

  // Payroll
  PAYROLL: '/payroll',
  PAYROLL_DETAIL: '/payroll/:id',

  // Payslip
  PAYSLIPS: '/payslips',

  // Report
  REPORTS: '/reports',

  // Errors
  FORBIDDEN: '/403',
  NOT_FOUND: '/404',
} as const
