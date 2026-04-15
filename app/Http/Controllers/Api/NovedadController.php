<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Novedad;
use Illuminate\Http\Request;

class NovedadController extends Controller
{
    public function index()
    {
        $novedades = Novedad::orderBy('fecha_publicacion', 'desc')
            ->take(5)
            ->get();

        return response()->json($novedades);
    }
}
