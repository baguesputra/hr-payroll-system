export const APP_NAME = 'HR Payroll System'

export const ROLES = {
  SUPER_ADMIN: 'super_admin',
  HR_MANAGER: 'hr_manager',
  FINANCE: 'finance',
  EMPLOYEE: 'employee',
} as const

export const PTKP_STATUS = [
  { label: 'TK/0 - Tidak Kawin, 0 Tanggungan', value: 'TK/0' },
  { label: 'K/0 - Kawin, 0 Tanggungan', value: 'K/0' },
  { label: 'K/1 - Kawin, 1 Tanggungan', value: 'K/1' },
  { label: 'K/2 - Kawin, 2 Tanggungan', value: 'K/2' },
  { label: 'K/3 - Kawin, 3 Tanggungan', value: 'K/3' },
] as const

export const EMPLOYMENT_STATUS = [
  { label: 'Tetap', value: 'permanent' },
  { label: 'Kontrak', value: 'contract' },
  { label: 'Probation', value: 'probation' },
] as const

export const PAYROLL_STATUS = {
  DRAFT: 'draft',
  PROCESSED: 'processed',
  APPROVED: 'approved',
  LOCKED: 'locked',
} as const