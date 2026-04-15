@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-primary fw-bold">Cálculo de Distribución 30%</h2>
                <p class="text-muted">Simulación de cálculo de descuentos según normativa.</p>
            </div>
        </div>

        <div class="card card-premium shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-auto">
                        <label class="form-label fw-bold">Mes</label>
                        <select name="mes" class="form-select">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="form-label fw-bold">Año</label>
                        <select name="anio" class="form-select">
                            <option value="2025" {{ $anio == 2025 ? 'selected' : '' }}>2025</option>
                            <option value="2026" {{ $anio == 2026 ? 'selected' : '' }}>2026</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Calcular</button>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Agente</th>
                            <th>Días 14A</th>
                            <th>Otros Descuentos</th>
                            <th>% Descuento</th>
                            <th>% A Cobrar</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calculo as $item)
                            <tr class="{{ $item['porcentaje_cobro'] < 100 ? 'table-warning' : '' }}">
                                <td class="ps-4">
                                    <span class="fw-bold d-block">{{ $item['agente']->age_apell1 }},
                                        {{ $item['agente']->age_nombre }}</span>
                                    <small class="text-muted">Legajo: {{ $item['agente']->age_id }}</small>
                                </td>
                                <td>{{ $item['dias_14a'] }}</td>
                                <td>{{ $item['descuento_aplicado'] > 0 && $item['dias_14a'] == 0 ? 'Sí' : '-' }}</td>
                                <td class="text-danger fw-bold">-{{ $item['descuento_aplicado'] }}%</td>
                                <td class="text-success fw-bold">{{ $item['porcentaje_cobro'] }}%</td>
                                <td><small class="text-muted">{{ $item['motivos'] }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-calculator fs-1 d-block mb-3"></i>
                                    No hay agentes calculados para este periodo.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection