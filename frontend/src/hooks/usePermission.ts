import { useAuthStore } from '@/stores/authStore'
import { ROLES } from '@/lib/constants'

export function usePermission() {
  const user = useAuthStore((state) => state.user)

  const hasRole = (role: string): boolean => {
    return user?.roles?.includes(role as never) ?? false
  }

  const can = (permission: string): boolean => {
    if (hasRole(ROLES.SUPER_ADMIN)) return true
    return user?.permissions?.includes(permission) ?? false
  }

  const isRole = {
    superAdmin: hasRole(ROLES.SUPER_ADMIN),
    hrManager: hasRole(ROLES.HR_MANAGER),
    finance: hasRole(ROLES.FINANCE),
    employee: hasRole(ROLES.EMPLOYEE),
  }

  return { can, hasRole, isRole }
}
