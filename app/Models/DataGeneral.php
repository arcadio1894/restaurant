<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataGeneral extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'valueText',
        'valueNumber',
        'description'
    ];

    // Recupera un valor basado en el nombre
    public static function getValue($name)
    {
        $record = self::where('name', $name)->first();
        return $record ? ( ($record->valueText == null || $record->valueText == '' )? $record->valueNumber : $record->valueText ) : null;
    }

    // Actualiza un valor específico
    public static function setValue($name, $value)
    {
        $record = self::where('name', $name)->first();
        if ($record) {
            if (is_numeric($value)) {
                $record->valueNumber = $value;
            } else {
                $record->valueText = $value;
            }
            $record->save();
        }
    }
}
