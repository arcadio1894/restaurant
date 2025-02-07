<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla
    protected $table = "districts";

    // Definir que la clave primaria no es autoincremental y es de tipo string
    protected $primaryKey = "id";
    public $incrementing = false;

    // Definir el tipo de clave primaria como string
    protected $keyType = "string";

    // Campos asignables
    protected $fillable = ['id', 'name', 'province_id', 'department_id'];

    // Relación con el modelo Province
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    // Relación opcional con el modelo Department (si la necesitas)
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
