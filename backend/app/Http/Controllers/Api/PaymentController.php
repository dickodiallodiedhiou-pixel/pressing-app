<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'montant' => 'required|numeric|min:0',
        ]);

        $ticket = Ticket::findOrFail($request->ticket_id);

        if ($ticket->is_paid) {
            return response()->json(['message' => 'Ce ticket a déjà été payé.'], 400);
        }

        // Enregistrement du paiement
        $payment = Payment::create([
            'ticket_id' => $ticket->id,
            'montant' => $request->montant,
            'mode_paiement' => 'especes',
            'paid_at' => now(),
        ]);

        // Mise à jour du statut de paiement du ticket
        $ticket->is_paid = true;
        $ticket->save();

        return response()->json([
            'message' => 'Paiement enregistré avec succès.',
            'payment' => $payment
        ], 201);
    }
}