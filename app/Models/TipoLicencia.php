<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoLicencia extends Model
{
    use HasFactory;

    protected $table = 't_licencias_tipos';
    protected $primaryKey = 'lt_id';
    public $timestamps = false; // Tabla legacy sin timestamps

    protected $fillable = [
        'nombre',
        'codigo_legacy',
        'regla_calculo_facturacion',
        'grupo_descuento'
    ];
}
