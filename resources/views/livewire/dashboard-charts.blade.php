<div class="row mt-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
    <div class="col-md-12">
        <h4 class="mb-4 fw-bold">Análisis e Insights</h4>
    </div>
    
    <!-- Gráfico de Tendencia de Asistencia -->
    <div class="col-md-8 mb-4">
        <div class="card card-premium overflow-hidden">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Asistencia de la Semana</h5>
                <p class="text-muted small">Cantidad de personas únicas fichando por día</p>
            </div>
            <div class="card-body px-4 pb-4">
                <div id="tendenciaChart"></div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Distribución de Licencias -->
    <div class="col-md-4 mb-4">
        <div class="card card-premium overflow-hidden h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 text-center">
                <h5 class="fw-bold mb-0">Licencias del Mes</h5>
            </div>
            <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                @if(count($licenciasData) > 0)
                    <div id="licenciasDonut"></div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Sin registros este mes</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            // 1. Chart Tendencia
            var optionsTendencia = {
                series: [{
                    name: 'Asistencia',
                    data: @json($tendenciaData)
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                colors: ['#6366f1'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100, 100, 100]
                    }
                },
                xaxis: {
                    categories: @json($tendenciaLabels),
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: { labels: { show: true } },
                grid: {
                    borderColor: '#f1f1f1',
                    xaxis: { lines: { show: false } }
                }
            };
            var chartTendencia = new ApexCharts(document.querySelector("#tendenciaChart"), optionsTendencia);
            chartTendencia.render();

            // 2. Chart Licencias
            @if(count($licenciasData) > 0)
                var optionsLicencias = {
                    series: @json($licenciasData),
                    chart: {
                        type: 'donut',
                        height: 300
                    },
                    labels: @json($licenciasLabels),
                    colors: ['#0088cc', '#2cc185', '#ffbb33', '#ff4444', '#aa66cc'],
                    legend: { position: 'bottom' },
                    dataLabels: { enabled: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: { show: true, label: 'Días' }
                                }
                            }
                        }
                    }
                };
                var chartLicencias = new ApexCharts(document.querySelector("#licenciasDonut"), optionsLicencias);
                chartLicencias.render();
            @endif
        });
    </script>
</div>
