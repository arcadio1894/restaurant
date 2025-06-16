<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteReclamacion extends Model
{
    use HasFactory;

    protected $table = 'comprobantes_reclamaciones';

    protected $fillable = [
        'reclamacion_id',
        'archivo',
    ];

    public function reclamacion()
    {
        return $this->belongsTo(Reclamacion::class);
    }
}
