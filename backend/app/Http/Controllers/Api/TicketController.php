<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Mail\OrderCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReadyMail;
use Barryvdh\DomPDF\Facade\Pdf;
class TicketController extends Controller
{
    // Lister les tickets
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'client') {
            // Le client ne voit que ses tickets
            $tickets = Ticket::with('services')->where('client_id', $user->id)->latest()->get();
        } else {
            // Le gestionnaire voit tous les tickets
            $tickets = Ticket::with(['client', 'services', 'payment'])->latest()->get();
        }

        return response()->json($tickets);
    }

    // Créer un ticket (Client)
    public function store(Request $request)
    {
        $request->validate([
            'services' => 'required|array|min:1',
            'services.*.id' => 'required|exists:services,id',
            'services.*.quantite' => 'required|integer|min:1',
        ]);

        $montantTotal = 0;
        $servicesPivot = [];

        foreach ($request->services as $item) {
            $service = Service::findOrFail($item['id']);
            if (!$service->is_active) {
                return response()->json(['message' => "Le service {$service->libelle} n'est pas disponible."], 400);
            }
            $sousTotal = $service->prix_unitaire * $item['quantite'];
            $montantTotal += $sousTotal;

            $servicesPivot[$service->id] = [
                'quantite' => $item['quantite'],
                'prix_unitaire' => $service->prix_unitaire,
            ];
        }

        $ticket = Ticket::create([
            'client_id' => $request->user()->id,
            'statut' => 'Reçu',
            'montant_total' => $montantTotal,
            'is_paid' => false,
        ]);

        $ticket->services()->attach($servicesPivot);

        // TODO: Envoi de l'email de confirmation au client + notification gestionnaire
        Mail::to($ticket->client->email)->send(new OrderCreatedMail($ticket));
        return response()->json($ticket->load('services'), 201);
    }

    // Afficher un ticket spécifique
    public function show(Ticket $ticket)
    {
        return response()->json($ticket->load(['client', 'services', 'payment']));
    }

    // Changer le statut d'un ticket (Gestionnaire)
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'statut' => 'required|in:En traitement,Prêt,Récupéré,Annulé',
        ]);

        $nouveauStatut = $request->statut;

        // Règle métie : Impossible de passer à Récupéré s'il n'est pas payé
        if ($nouveauStatut === 'Récupéré' && !$ticket->is_paid) {
            return response()->json(['message' => 'Impossible de passer au statut Récupéré sans paiement.'], 400);
        }
        if ($nouveauStatut === 'Prêt') {
            $pdf = Pdf::loadView('pdf.receipt', ['ticket' => $ticket->load(['client', 'services'])]);
            Mail::to($ticket->client->email)->send(new OrderReadyMail($ticket, $pdf->output()));
        }
        $ticket->statut = $nouveauStatut;
        $ticket->save();

        // TODO: Si statut === 'Prêt' -> Générer et envoyer le reçu PDF par mail

        return response()->json($ticket);
    }
}