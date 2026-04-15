<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoAgente extends Model
{
    use HasFactory;

    protected $table = 't_documentos_agente';
    protected $primaryKey = 'doc_id';
    const CREATED_AT = 'fecha_subida';
    const UPDATED_AT = null;

    protected $fillable = [
        'age_id',
        'tipo_documento',
        'nombre_archivo',
        'ruta_archivo',
        'subido_por',
        'observaciones'
    ];

    public function agente()
    {
        return $this->belongsTo(Agente::class, 'age_id', 'age_id');
    }

    public function subidoPor()
    {
        return $this->belongsTo(Agente::class, 'subido_por', 'age_id');
    }
}
