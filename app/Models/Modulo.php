<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 't_modulos';
    protected $primaryKey = 'id';

    // ESTA ES LA LÍNEA QUE FALTA:
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'route',
        'icon',
        'activo',
        'orden'
    ];
}
