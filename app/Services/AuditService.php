<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Registra una acción en la tabla de auditoría.
     */
    public static function log($accion, $modelo = null, $id = null, $detalles = null)
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'accion' => $accion,
                'modelo_tipo' => $modelo,
                'modelo_id' => $id,
                'detalles' => is_array($detalles) ? json_encode($detalles) : $detalles,
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
            ]);
        } catch (\Exception $e) {
            // No bloqueamos la ejecución principal por un error en auditoría
            \Illuminate\Support\Facades\Log::error("Error grabando audit log: " . $e->getMessage());
        }
    }
}
