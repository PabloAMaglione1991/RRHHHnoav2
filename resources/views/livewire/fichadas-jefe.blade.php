<div>
    <h2 class="mb-4 text-primary fw-bold"><i class="bi bi-people"></i> Asistencia Agentes a Cargo</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 20px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
                <div class="card-header bg-primary text-white py-3" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
                    <h5 class="mb-0 fw-bold small"><i class="bi bi-search"></i> Buscar Agente</h5>
                </div>
                <div class="card-body p-3">
                    <input type="text" wire:model="search" class="form-control mb-3 border-0 bg-light rounded-pill" placeholder="Nombre o DNI...">
                    <div class="list-group list-group-flush" style="max-height: 550px; overflow-y: auto;">
                        @forelse($agentes as $ag)
                            <button wire:click="seleccionarAgente({{ $ag->age_id }})"
                                class="list-group-item list-group-item-action @if($agente_id == $ag->age_id) active @endif border-0 rounded-4 mb-2 shadow-sm transition-all @if($agente_id == $ag->age_id) bg-primary text-white @endif">
                                <div class="fw-bold">{{ $ag->age_apell1 }}, {{ $ag->age_nombre }}</div>
                                <div class="small opacity-75">DNI: {{ $ag->age_numdoc }}</div>
                            </button>
                        @empty
                            <div class="text-center p-4 text-muted small">
                                <i class="bi bi-person-x fs-1 opacity-25"></i>
                                <p>No se encontraron agentes.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9 mt-4 mt-md-0">
            @if($datosAgente)
                <div class="card shadow-sm border-0 mb-4 animate__animated animate__fadeIn" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-1 text-primary fw-bold">
                                    <i class="bi bi-person-check"></i> {{ $datosAgente->age_nombre }} {{ $datosAgente->age_apell1 }}
                                </h4>
                                <div class="text-muted small">
                                    Período: <span class="fw-bold text-capitalize">{{ \Carbon\Carbon::createFromDate($anioActual, $mesActual, 1)->translatedFormat('F Y') }}</span>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="badge bg-primary fs-6 p-2 px-4 rounded-pill shadow-sm">
                                    MES
                                </div>
                            </div>
                            <div class="col-md-3 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
                                <select wire:model="mesActual" class="form-select w-auto border-0 bg-light rounded-pill">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                                    @endforeach
                                </select>
                                <select wire:model="anioActual" class="form-select w-auto border-0 bg-light rounded-pill">
                                    @foreach(range(date('Y')-5, date('Y')+1) as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 animate__animated animate__fadeInUp" style="border-radius: 20px;">
                    <div class="card-body p-0 overflow-hidden" style="border-radius: 20px;">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 text-center" style="table-layout: fixed;">
                                <thead class="bg-primary text-white border-0">
                                    <tr>
                                        <th class="py-3 border-0">Lun</th><th class="py-3 border-0">Mar</th><th class="py-3 border-0">Mié</th>
                                        <th class="py-3 border-0">Jue</th><th class="py-3 border-0">Vie</th><th class="py-3 border-0 text-warning">Sáb</th><th class="py-3 border-0 text-danger">Dom</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($calendario as $semana)
                                        <tr style="height: 110px;">
                                            @foreach($semana as $dia)
                                                @php
                                                    $bgClass = $dia['es_mes_actual'] ? 'bg-white' : 'bg-light text-muted opacity-50';
                                                @endphp
                                                <td class="{{ $bgClass }} position-relative p-2 align-top border-light">
                                                    <span class="fw-bold d-block text-start mb-1" style="font-size: 0.9rem;">{{ $dia['dia'] }}</span>
                                                    <div class="text-start">
                                                        @foreach($dia['fichadas'] as $fichada)
                                                            <div class="badge @if($fichada['tipo']=='E') bg-success @else bg-danger @endif w-100 text-start mb-1" style="font-size: 0.7rem; font-weight: 500;">
                                                                {{ $fichada['tipo'] }}: {{ $fichada['hora'] }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 d-flex align-items-center justify-content-center p-5 text-muted bg-white bg-opacity-75" style="border-radius: 20px; border: 2px dashed #ddd !important;">
                    <div class="text-center animate__animated animate__pulse animate__infinite">
                        <i class="bi bi-people fs-1 opacity-25"></i>
                        <h4 class="mt-3 fw-bold">Gestión de Asistencia</h4>
                        <p>Seleccione un agente del panel de la izquierda para desplegar sus fichadas mensuales.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
