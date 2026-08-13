import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { Auth } from '../../services/auth';

@Component({
  selector: 'app-register',
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './register.html',
  styleUrl: './register.css',
})
export class Register {
  userData = {
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'client'
  };
  errorMessage = '';

  constructor(private authService: Auth, private router: Router) {}

  onSubmit() {
    this.authService.register(this.userData).subscribe({
      next: () => {
        this.router.navigate(['/client/catalog']);
      },
      error: (err: any) => {
        this.errorMessage = err.error?.message || "Erreur lors de l'inscription.";
      }
    });
  }
}
