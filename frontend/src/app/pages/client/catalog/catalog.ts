import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { ServiceService } from '../../../services/service';
import { TicketService } from '../../../services/ticket';

@Component({
  selector: 'app-catalog',
  imports: [CommonModule, FormsModule],
  templateUrl: './catalog.html',
  styleUrl: './catalog.css',
})
export class Catalog implements OnInit {
  services: any[] = [];
  cart: { service_id: number; libelle: string; prix: number; quantite: number }[] = [];
  successMessage = '';
  errorMessage = '';

  constructor(
    private serviceService: ServiceService,
    private ticketService: TicketService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loadServices();
  }

  loadServices() {
    this.serviceService.getServices().subscribe({
      next: (data: any) => (this.services = data),
      error: (err: any) => console.error(err)
    });
  }

  addToCart(service: any) {
    const item = this.cart.find((i) => i.service_id === service.id);
    if (item) {
      item.quantite++;
    } else {
      this.cart.push({
        service_id: service.id,
        libelle: service.libelle,
        prix: parseFloat(service.prix),
        quantite: 1
      });
    }
  }

  removeFromCart(index: number) {
    this.cart.splice(index, 1);
  }

  getTotal(): number {
    return this.cart.reduce((sum, item) => sum + item.prix * item.quantite, 0);
  }

  submitTicket() {
    if (this.cart.length === 0) return;

    const payload = {
      services: this.cart.map((item) => ({
        id: item.service_id,
        quantite: item.quantite
      }))
    };

    this.ticketService.createTicket(payload).subscribe({
      next: () => {
        this.successMessage = 'Commande enregistrée avec succès !';
        this.cart = [];
        setTimeout(() => this.router.navigate(['/client/my-tickets']), 2000);
      },
      error: (err: any) => {
        this.errorMessage = err.error?.message || 'Erreur lors de la validation.';
      }
    });
  }
}
