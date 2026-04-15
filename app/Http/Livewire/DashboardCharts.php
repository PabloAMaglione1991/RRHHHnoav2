<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\AnalyticsService;

class DashboardCharts extends Component
{
    public $tendenciaLabels = [];
    public $tendenciaData = [];
    public $licenciasLabels = [];
    public $licenciasData = [];

    public function mount(AnalyticsService $service)
    {
        $this->loadData($service);
    }

    public function loadData(AnalyticsService $service)
    {
        $tendencia = $service->getTendenciaAsistencia(7);
        $this->tendenciaLabels = $tendencia['labels'];
        $this->tendenciaData = $tendencia['data'];

        $licencias = $service->getDistribucionLicenciasMes();
        $this->licenciasLabels = $licencias['labels'];
        $this->licenciasData = $licencias['data'];
    }

    public function render()
    {
        return view('livewire.dashboard-charts');
    }
}
