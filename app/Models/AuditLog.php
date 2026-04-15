<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 't_audit_logs';
    public $timestamps = false; // Solo usamos created_at via migration

    protected $fillable = [
        'user_id',
        'accion',
        'modelo_tipo',
        'modelo_id',
        'detalles',
        'ip_address',
        'user_agent'
    ];

    public function user()
    {
        return $this->belongsTo(Agente::class, 'user_id', 'age_id');
    }
}
