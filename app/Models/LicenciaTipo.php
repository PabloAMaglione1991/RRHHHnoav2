<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenciaTipo extends Model
{
    // Asegurate que el nombre de la tabla sea el correcto
    protected $table = 't_licencias_tipos'; 
    
    // CAMBIÁ ESTO: Si en tu base de datos la columna es 'id', poné 'id'.
    // Si es 'licencia_tipo_id', poné ese. El error dice que 'lt_id' NO existe.
    protected $primaryKey = 'id'; 

    public $timestamps = false;
}
