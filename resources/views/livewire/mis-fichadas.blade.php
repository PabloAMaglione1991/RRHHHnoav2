<div>
    <h2 class="mb-4">Mis Fichadas</h2>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="mb-0 text-primary fw-bold text-capitalize">
                        <i class="bi bi-calendar-month"></i> {{ \Carbon\Carbon::createFromDate($anio, $mes, 1)->translatedFormat('F Y') }}
                    </h4>
                </div>
                <div class="col-md-8 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
                    <button wire:click="sincronizar" wire:loading.attr="disabled" class="btn btn-success text-white">
                        <span wire:loading.remove wire:target="sincronizar"><i class="bi bi-arrow-repeat"></i> Sincronizar Fichadas</span>
                        <span wire:loading wire:target="sincronizar"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i> Sincronizando...</span>
                    </button>
                    
                    <select wire:model="mes" class="form-select w-auto">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                    <select wire:model="anio" class="form-select w-auto">
                        @foreach(range(date('Y')-5, date('Y')+1) as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                    <button wire:click="hoy" class="btn btn-outline-primary">Hoy</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 text-center" style="table-layout: fixed;">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="py-3">Lun</th>
                            <th class="py-3">Mar</th>
                            <th class="py-3">Mié</th>
                            <th class="py-3">Jue</th>
                            <th class="py-3">Vie</th>
                            <th class="py-3 text-warning">Sáb</th>
                            <th class="py-3 text-danger">Dom</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calendario as $semana)
                            <tr style="height: 120px;">
                                @foreach($semana as $dia)
                                    @php
                                        $esHoy = $dia['fecha'] == date('Y-m-d');
                                        $bgClass = $esHoy ? 'bg-light border-primary border-2' : ($dia['es_mes_actual'] ? 'bg-white' : 'bg-light text-muted');
                                    @endphp
                                    <td class="{{ $bgClass }} position-relative p-2 align-top">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold {{ $esHoy ? 'text-primary' : '' }}">{{ $dia['dia'] }}</span>
                                            @if($dia['horas_trabajadas'] > 0)
                                                <span class="badge bg-success rounded-pill" style="font-size: 0.7rem;">{{ $dia['horas_trabajadas'] }}hs</span>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-2 text-start small">
                                            @foreach($dia['fichadas'] as $fichada)
                                                <div class="mb-1">
                                                    @if($fichada['tipo'] == 'E')
                                                        <span class="text-success fw-bold">E:</span> {{ \Carbon\Carbon::parse($fichada['hora'])->format('H:i') }}
                                                    @else
                                                        <span class="text-danger fw-bold">S:</span> {{ \Carbon\Carbon::parse($fichada['hora'])->format('H:i') }}
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($dia['inasistencia'])
                                                 <div class="badge bg-danger w-100 text-truncate" title="Inasistencia">Ausente</div>
                                            @endif
                                            @if($dia['licencia'])
                                                 <div class="badge bg-warning text-dark w-100 text-truncate" title="{{ $dia['licencia'] }}">{{ $dia['licencia'] }}</div>
                                            @endif
                                            @if($dia['feriado'])
                                                 <div class="badge bg-info text-dark w-100 text-truncate" title="Feriado">Feriado</div>
                                            @endif
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
</div>

