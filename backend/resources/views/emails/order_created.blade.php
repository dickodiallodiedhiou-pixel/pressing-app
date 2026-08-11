<h2>Bonjour {{ $ticket->client->name }},</h2>
<p>Votre commande <strong>#{{ $ticket->id }}</strong> a bien été enregistrée.</p>
<p>Montant total : {{ number_format($ticket->montant_total, 0, ',', ' ') }} FCFA</p>
<p>Merci de votre confiance !</p>