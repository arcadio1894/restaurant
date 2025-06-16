<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamacion extends Model
{
    use HasFactory;

    protected $table = 'reclamaciones';

    protected $appends = ['status_name'];

    protected $fillable = [
        'codigo',
        'nombre',
        'apellido',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'email',
        'departamento',
        'provincia',
        'distrito',
        'direccion',
        'menor_edad',
        'nombre_representante',
        'telefono_representante',
        'direccion_representante',
        'correo_representante',
        'tipo_bien',
        'monto',
        'descripcion',
        'tipo_reclamacion',
        'canal',
        'motivo',
        'submotivo',
        'detalle',
        'pedido_cliente',
        'comprobante',
        'estado',
        'respuesta'
    ];

    public function getStatusNameAttribute()
    {
        $statusNames = [
            'pendiente'   => 'PENDIENTE',
            'revisado'    => 'EN REVISION',
            'solucionado' => 'SOLUCIONADO',
            'anulado'     => 'ANULADO',
        ];

        $estado = strtolower(trim($this->estado));

        return array_key_exists($estado, $statusNames)
            ? $statusNames[$estado]
            : 'DESCONOCIDO';
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departamento', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'provincia', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'distrito', 'id');
    }

    public function motive()
    {
        return $this->belongsTo(Motivo::class, 'motivo', 'id');
    }

    public function submotive()
    {
        return $this->belongsTo(Submotivo::class, 'submotivo', 'id');
    }

    public function comprobantes()
    {
        return $this->hasMany(ComprobanteReclamacion::class);
    }
}
