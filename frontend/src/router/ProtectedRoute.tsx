import { Navigate, Outlet } from 'react-router-dom'
import { useAuthStore } from '@/stores/authStore'

interface ProtectedRouteProps {
  permission?: string
  redirectTo?: string
}

export function ProtectedRoute({ permission, redirectTo = '/login' }: ProtectedRouteProps) {
  const { isAuthenticated, user } = useAuthStore()

  if (!isAuthenticated) {
    return <Navigate to={redirectTo} replace />
  }

  if (permission) {
    const hasPermission =
      user?.permissions?.includes(permission) || user?.roles?.includes('super_admin')

    if (!hasPermission) {
      return <Navigate to="/403" replace />
    }
  }

  return <Outlet />
}
