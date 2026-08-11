<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['libelle', 'prix_unitaire', 'description', 'is_active'];

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_service')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }
}
