<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motivo extends Model
{
    use HasFactory;
    // Definir la tabla asociada
    protected $table = 'motivos';

    // Campos asignables
    protected $fillable = ['nombre'];

    // Relación con Submotivo
    public function submotivos()
    {
        return $this->hasMany(Submotivo::class);
    }
}
