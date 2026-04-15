<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FacturacionService;

class FacturacionController extends Controller
{
    protected $facturacionService;

    public function __construct(FacturacionService $facturacionService)
    {
        $this->facturacionService = $facturacionService;
    }

    public function index(Request $request)
    {
        $mes = $request->input('mes', date('m'));
        $anio = $request->input('anio', date('Y'));

        // Hardcodeamos mes anterior si estamos al principio de mes, o mes actual
        // Por defecto hoy.

        $calculo = $this->facturacionService->calcularDistribucion($mes, $anio);

        return view('facturacion.index', compact('calculo', 'mes', 'anio'));
    }
}
