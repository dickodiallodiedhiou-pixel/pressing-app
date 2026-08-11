<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Liste des services
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Si c'est un client, on affiche uniquement les services actifs
        if ($user && $user->role === 'client') {
            return response()->json(Service::where('is_active', true)->get());
        }

        // Pour le gestionnaire (ou visiteur), on peut filtrer ou tout retourner
        $query = Service::query();
        if ($request->has('libelle')) {
            $query->where('libelle', 'like', '%' . $request->libelle . '%');
        }

        return response()->json($query->get());
    }

    // Créer un service (Gestionnaire)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'prix_unitaire' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $service = Service::create($validated);
        return response()->json($service, 201);
    }

    // Modifier un service (Gestionnaire)
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'libelle' => 'sometimes|required|string|max:255',
            'prix_unitaire' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);
        return response()->json($service);
    }

    // Archiver/Supprimer un service
    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['message' => 'Service supprimé']);
    }
}