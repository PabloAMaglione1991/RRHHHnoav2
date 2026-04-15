<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudLicencia extends Model
{
    protected $table = 't_licencias_solicitadas';
    protected $primaryKey = 'solicitud_id';
    public $timestamps = false;

    public function agente()
    {
        return $this->belongsTo(Agente::class, 'age_id_agente', 'age_id');
    }

    public function tipoLicencia()
    {
        // El tercer parámetro es la columna en la tabla t_licencias_tipos
        // Cambié 'lt_id' por 'id' (o la que sea tu primaria en esa tabla)
        return $this->belongsTo(LicenciaTipo::class, 'licencia_tipo_id', 'id');
    }
}
