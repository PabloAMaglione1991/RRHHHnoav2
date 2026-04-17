<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 't_modulos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'modulo_nombre',
        'modulo_ruta',
        'modulo_icono',
        'modulo_activo',
        'modulo_orden',
        'modulo_clave'
    ];
}
