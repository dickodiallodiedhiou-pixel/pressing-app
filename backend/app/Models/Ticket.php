<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['client_id', 'statut', 'montant_total', 'is_paid'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'ticket_service')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
