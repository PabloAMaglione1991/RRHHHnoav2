<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Agente;
use App\Models\Novedad;
use Illuminate\Support\Facades\DB;
use App\Services\FichadaService;
use App\Services\LicenciaService;

class DashboardStats extends Component
{
    public $licenciasActivas = 0;
    public $fichadasHoy = 0;
    public $usuariosActivos = 0;

    public function mount(FichadaService $fichadaService, LicenciaService $licenciaService)
    {
        $this->calculateStats($fichadaService, $licenciaService);
    }

    public function calculateStats(FichadaService $fichadaService, LicenciaService $licenciaService)
    {
        $mes = date('m');
        $anio = date('Y');
        $hoy = date('Y-m-d');

        // 1. Usuarios Activos (Agentes con age_activo = 1)
        $this->usuariosActivos = Agente::where('age_activo', 1)->count();

        // 2. Fichadas Hoy (Registros en t_fichadas para el día de hoy)
        $this->fichadasHoy = DB::table('t_fichadas')
            ->whereDate('fich_fecha', $hoy)
            ->distinct('tarj_nro') // Contamos personas que ficharon, no cantidad de fichadas
            ->count();

        // 3. Licencias/Inasistencias Activas (Hoy)
        $this->licenciasActivas = DB::table('t_inasist')
            ->whereDate('inas_fecha', $hoy)
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
