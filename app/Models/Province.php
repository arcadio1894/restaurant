<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla
    protected $table = "provinces";

    // Definir que la clave primaria no es autoincremental y es de tipo string
    protected $primaryKey = "id";
    public $incrementing = false;

    // Definir el tipo de clave primaria como string
    protected $keyType = "string";

    // Campos asignables
    protected $fillable = ['id', 'name', 'department_id'];

    // Relación con Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    // Relación con District
    public function districts()
    {
        return $this->hasMany(District::class, 'province_id', 'id');
    }
}
