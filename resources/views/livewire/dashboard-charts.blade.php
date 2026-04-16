<div class="mt-8">
    <div class="mb-6">
        <h4 class="text-xl font-bold text-slate-800">Análisis e Insights</h4>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Gráfico de Tendencia de Asistencia -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="pt-6 px-6">
                    <h5 class="text-lg font-bold text-slate-800">Asistencia de la Semana</h5>
                    <p class="text-slate-500 text-sm">Cantidad de personas únicas fichando por día</p>
                </div>
                <div class="p-6">
                    <div id="tendenciaChart"></div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Distribución de Licencias -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden h-full flex flex-col">
                <div class="pt-6 px-6 text-center">
                    <h5 class="text-lg font-bold text-slate-800">Licencias del Mes</h5>
                </div>
                <div class="p-6 flex-grow flex items-center justify-center">
                    @if(count($licenciasData) > 0)
                        <div id="licenciasDonut" class="w-full"></div>
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-calendar-x text-slate-300 text-5xl mb-4"></i>
                            <p class="text-slate-500 font-medium">Sin registros este mes</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script shadow>
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
                    fontFamily: 'Inter, sans-serif',
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
                    axisTicks: { show: false },
                    labels: {
                        style: { colors: '#64748b', fontWeight: 500 }
                    }
                },
                yaxis: { 
                    labels: { 
                        show: true,
                        style: { colors: '#64748b', fontWeight: 500 }
                    } 
                },
                grid: {
                    borderColor: '#f1f5f9',
                    xaxis: { lines: { show: false } }
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: function (val) { return val + " personas" }
                    }
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
                        height: 320,
                        fontFamily: 'Inter, sans-serif',
                    },
                    labels: @json($licenciasLabels),
                    colors: ['#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                    legend: { 
                        position: 'bottom',
                        horizontalAlign: 'center',
                        fontSize: '13px',
                        markers: { radius: 12 },
                        itemMargin: { horizontal: 10, vertical: 5 }
                    },
                    dataLabels: { enabled: false },
                    stroke: { width: 0 },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '14px',
                                        fontWeight: 600,
                                        color: '#64748b'
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '24px',
                                        fontWeight: 700,
                                        color: '#1e293b',
                                        formatter: function (val) { return val }
                                    },
                                    total: { 
                                        show: true, 
                                        label: 'Total Días',
                                        fontSize: '14px',
                                        fontWeight: 600,
                                        color: '#64748b'
                                    }
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
