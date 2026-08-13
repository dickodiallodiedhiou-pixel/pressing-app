import { Routes } from '@angular/router';
import { Login } from './pages/login/login';
import { Register } from './pages/register/register';
import { Catalog } from './pages/client/catalog/catalog';
import { MyTickets } from './pages/client/my-tickets/my-tickets';
import { Dashboard } from './pages/admin/dashboard/dashboard';
import { ServicesManagement } from './pages/admin/services-management/services-management';
import { TicketsManagement } from './pages/admin/tickets-management/tickets-management';
import { authGuard } from './guards/auth-guard';

export const routes: Routes = [
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  { path: 'login', component: Login },
  { path: 'register', component: Register },

  // Espace client
  { path: 'client/catalog', component: Catalog, canActivate: [authGuard], data: { role: 'client' } },
  { path: 'client/my-tickets', component: MyTickets, canActivate: [authGuard], data: { role: 'client' } },

  // Espace gestionnaire (admin)
  { path: 'admin/dashboard', component: Dashboard, canActivate: [authGuard], data: { role: 'gestionnaire' } },
  { path: 'admin/services-management', component: ServicesManagement, canActivate: [authGuard], data: { role: 'gestionnaire' } },
  { path: 'admin/tickets-management', component: TicketsManagement, canActivate: [authGuard], data: { role: 'gestionnaire' } },

  { path: '**', redirectTo: 'login' }
];
