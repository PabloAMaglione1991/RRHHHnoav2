<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Novedad extends Model
{
    use HasFactory;


    protected $table = 't_novedades';
    protected $primaryKey = 'nov_id';
    public $timestamps = false;


    protected $fillable = [
        'nov_titulo',
        'nov_contenido_largo',
        'nov_contenido_corto',
        'nov_tipo', // Requiere agregar columna en DB
        'nov_fecha_publicacion',
        'nov_fijada',
        'nov_activo',
        'nov_creado_por_age_id'
    ];
}




