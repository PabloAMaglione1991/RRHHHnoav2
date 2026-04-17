<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FichadaService;
use App\Services\LicenciaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $fichadaService;
    protected $licenciaService;

    public function __construct(FichadaService $fichadaService, LicenciaService $licenciaService)
    {
        $this->fichadaService = $fichadaService;
        $this->licenciaService = $licenciaService;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $age_id = Auth::user()->age_id;

        $mes = date('m');
        $anio = date('Y');

        $horasTrabajadas = $this->fichadaService->getHorasTrabajadasMes($age_id, $mes, $anio);
        $resumenLicencias = $this->licenciaService->getResumenDescuentosAgente($age_id, $mes, $anio);

        $horasFormateadas = $this->fichadaService->convertirMinutosAHoras($horasTrabajadas);

        // CORRECCIÓN: Revertimos a usar los nombres originales de las columnas (modulo_activo y modulo_nombre)
        $modulosActivos = DB::table('t_modulos')
            ->where('modulo_activo', 1)
            ->pluck('modulo_nombre')
            ->map(function ($name) {
                return mb_strtolower($name, 'UTF-8');
            })->toArray();

        $moduloReportes = in_array('reportes', $modulosActivos);
        
        // Verificamos si existe el módulo de novedades en cualquiera de sus variantes
        $moduloNovedades = in_array('gestión de novedades', $modulosActivos)
            || in_array('gestion de novedades', $modulosActivos)
            || in_array('gestión novedades', $modulosActivos)
            || in_array('gestion novedades', $modulosActivos)
            || in_array('novedades', $modulosActivos);

        return view('dashboard', compact(
            'horasTrabajadas', 
            'horasFormateadas', 
            'resumenLicencias', 
            'mes', 
            'anio', 
            'moduloReportes', 
            'moduloNovedades'
        ));
    }
}
