<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PRESSING LIC</h2>
        <p>Reçu de paiement / Commande #{{ $ticket->id }}</p>
    </div>
    <p><strong>Client :</strong> {{ $ticket->client->name }} ({{ $ticket->client->email }})</p>
    <p><strong>Date :</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Service</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ticket->services as $service)
            <tr>
                <td>{{ $service->libelle }}</td>
                <td>{{ $service->pivot->quantite }}</td>
                <td>{{ number_format($service->pivot->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                <td>{{ number_format($service->pivot->quantite * $service->pivot->prix_unitaire, 0, ',', ' ') }} FCFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h3>Total : {{ number_format($ticket->montant_total, 0, ',', ' ') }} FCFA</h3>
</body>
</html>