<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Novedad extends Model
{
    use HasFactory;


    protected $table = 't_novedades';
    protected $primaryKey = 'id';
    public $timestamps = true;


    protected $fillable = [
        'titulo',
        'contenido',
        'tipo',
        'nov_fijada',
        'nov_activo',
        'nov_fecha_publicacion',
        'nov_creado_por_age_id'
    ];
}




