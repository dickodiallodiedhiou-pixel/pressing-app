import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { Auth } from '../services/auth';

export const authGuard: CanActivateFn = (route, state) => {
  const authService = inject(Auth);
  const router = inject(Router);

  if (authService.isLoggedIn()) {
    const requiredRole = route.data?.['role'];
    if (requiredRole && authService.getRole() !== requiredRole) {
      // Redirection si le rôle ne correspond pas
      router.navigate([authService.getRole() === 'gestionnaire' ? '/admin/dashboard' : '/client/catalog']);
      return false;
    }
    return true;
  }

  router.navigate(['/login']);
  return false;
};