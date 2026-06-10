export type UserRole = 'super_admin' | 'hr_manager' | 'finance' | 'employee'

export interface User {
  id: number
  name: string
  email: string
  roles: UserRole[]
  permissions: string[]
}

export interface SelectOption {
  label: string
  value: string | number
}
