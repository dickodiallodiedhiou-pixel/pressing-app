<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    public function dashboard()
    {
        $today = now()->format('Y-m-d');

        // 1. Nombre de tickets créés le jour même
        $ticketsCreesAujourdhui = Ticket::whereDate('created_at', $today)->count();

        // 2. Nombre de tickets récupérés / clôturés le jour même
        $ticketsRecuperesAujourdhui = Ticket::where('statut', 'Récupéré')
            ->whereDate('updated_at', $today)
            ->count();

        // 3. Recette journalière
        $recetteAujourdhui = Payment::whereDate('paid_at', $today)->sum('montant');

        // 4. Nombre de tickets par mois (année en cours)
        $ticketsParMois = Ticket::select(
            DB::raw('MONTH(created_at) as mois'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

        // 5. Répartition du chiffre d'affaires par service par mois
        $caParServiceParMois = DB::table('ticket_service')
            ->join('services', 'ticket_service.service_id', '=', 'services.id')
            ->join('tickets', 'ticket_service.ticket_id', '=', 'tickets.id')
            ->select(
                'services.libelle',
                DB::raw('MONTH(tickets.created_at) as mois'),
                DB::raw('SUM(ticket_service.quantite * ticket_service.prix_unitaire) as chiffre_affaires')
            )
            ->where('tickets.is_paid', true)
            ->whereYear('tickets.created_at', date('Y'))
            ->groupBy('services.libelle', 'mois')
            ->get();

        return response()->json([
            'tickets_crees_aujourdhui' => $ticketsCreesAujourdhui,
            'tickets_recuperes_aujourdhui' => $ticketsRecuperesAujourdhui,
            'recette_aujourdhui' => $recetteAujourdhui,
            'tickets_par_mois' => $ticketsParMois,
            'ca_par_service_par_mois' => $caParServiceParMois,
        ]);
    }
}