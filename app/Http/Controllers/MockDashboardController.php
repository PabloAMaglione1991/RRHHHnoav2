<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MockDashboardController extends Controller
{
    public function index()
    {
        $horasTrabajadas = 168.5;
        $horasFormateadas = "168h 30m";
        $resumenLicencias = collect([
            (object)['estado' => 'Pendientes', 'total' => 3],
            (object)['estado' => 'Aprobadas', 'total' => 12],
            (object)['estado' => 'Rechazadas', 'total' => 1],
        ]);
        $mes = date('n');
        $anio = date('Y');
        $moduloReportes = true;
        $moduloNovedades = true;

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
