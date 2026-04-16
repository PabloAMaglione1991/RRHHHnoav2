<div class="row mb-5 g-4">
    <div class="col-md-4">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden h-100 animate__animated animate__fadeInLeft">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper bento-cyan">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $licenciasActivas }}</h3>
                    <p>Licencias Hoy</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden h-100 animate__animated animate__fadeInUp">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper bento-green">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $fichadasHoy }}</h3>
                    <p>Presentes</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden h-100 animate__animated animate__fadeInRight">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper bento-purple">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $usuariosActivos }}</h3>
                    <p>Total Personal</p>
                </div>
            </div>
        </div>
    </div>
</div>
