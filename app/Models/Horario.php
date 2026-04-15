<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 't_horarios_definiciones';
    protected $primaryKey = 'id';
    public $timestamps = false; // Tabla legacy sin created_at/updated_at
    protected $guarded = [];

    // Accessor para mostrar días legible (opcional)
    public function getDiasLegibleAttribute()
    {
        $diasArr = explode(',', $this->dias_semana);
        $nombres = [];
        $map = [1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'];

        foreach ($diasArr as $d) {
            if (isset($map[$d]))
                $nombres[] = $map[$d];
        }
        return implode(', ', $nombres);
    }
}
