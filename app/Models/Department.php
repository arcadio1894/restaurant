<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla
    protected $table = "departments";

    // Definir que la clave primaria no es autoincremental y es de tipo string
    protected $primaryKey = "id";
    public $incrementing = false;

    // Definir el tipo de clave primaria como string
    protected $keyType = "string";

    // Especificar los campos que se pueden asignar masivamente
    protected $fillable = ['id', 'name'];

    // Relación con el modelo Province
    public function provinces()
    {
        return $this->hasMany(Province::class, 'department_id', 'id');
    }
}
