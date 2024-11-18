<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'phone',
        'first_name',
        'last_name',
        'address_line',
        'reference',

        'city',
        'state',
        'postal_code',
        'country',
        'is_default'
    ];

    /**
     * Relación con el modelo User.
     * Un usuario puede tener muchas direcciones.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para obtener solo las direcciones de tipo 'shipping' (envío).
     */
    public function scopeShipping($query)
    {
        return $query->where('type', 'shipping');
    }

    /**
     * Scope para obtener solo las direcciones de tipo 'billing' (facturación).
     */
    public function scopeBilling($query)
    {
        return $query->where('type', 'billing');
    }

    /**
     * Obtiene la dirección predeterminada para el usuario.
     * Retorna la dirección de tipo 'shipping' o 'billing' que está marcada como predeterminada.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
