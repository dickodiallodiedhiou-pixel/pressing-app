import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-login',
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './login.html',
  styleUrl: './login.css',
})
export class Login {
  credentials = { email: '', password: '' };
  errorMessage = '';

  constructor(private authService: Auth, private router: Router) {}

  onSubmit() {
    this.authService.login(this.credentials).subscribe({
      next: (res: any) => {
        const role = res.user.role;
        if (role === 'gestionnaire') {
          this.router.navigate(['/admin/dashboard']);
        } else {
          this.router.navigate(['/client/catalog']);
        }
      },
      error: (err: any) => {
        this.errorMessage = err.error?.message || 'Identifiants incorrects.';
      }
    });
  }
}
