<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichada extends Model
{
    use HasFactory;

    protected $table = 't_fichadas';
    protected $primaryKey = 'fich_id'; // Asumiendo ID, verificar si existe
    public $timestamps = false;

    protected $guarded = [];
}
