<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 't_modulos';
    protected $primaryKey = 'modulo_id';

    // ESTA ES LA LÍNEA QUE FALTA:
    public $timestamps = false;

    protected $fillable = [
        'modulo_nombre',
        'modulo_clave',
        'modulo_descripcion',
        'modulo_activo'
    ];
}
