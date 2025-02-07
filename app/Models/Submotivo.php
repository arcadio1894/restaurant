<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submotivo extends Model
{
    use HasFactory;

    // Definir la tabla asociada
    protected $table = 'submotivos';

    // Campos asignables
    protected $fillable = ['nombre', 'motivo_id'];

    // Relación con Motivo
    public function motivo()
    {
        return $this->belongsTo(Motivo::class);
    }
}
