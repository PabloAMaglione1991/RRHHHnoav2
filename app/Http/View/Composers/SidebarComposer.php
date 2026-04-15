<?php


namespace App\Http\View\Composers;


use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Modulo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


class SidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (!Auth::check()) {
            return;
        }


        $user = Auth::user();


        // Cargamos roles para las verificaciones de visibilidad
        $user->loadMissing('roles');


        // Consultamos la tabla real t_modulos de Santa Fe
        $modulos = DB::table('t_modulos')
            ->where('modulo_activo', 1)
            ->orderBy('modulo_nombre', 'asc')
            ->get();


        $sidebarItems = [];


        foreach ($modulos as $mod) {
            // NORMALIZACIÓN: Convertimos 'mis-fichadas' o 'mis_fichadas' en 'mis.fichadas'
            $moduloClaveBD = $mod->modulo_clave;
            $routeMapped = str_replace(['-', '_'], '.', $moduloClaveBD);
           
            $visible = false;


            // 1. Accesos Comunes (Todo Agente)
            if (in_array($routeMapped, ['dashboard', 'mis.fichadas', 'mis.licencias', 'mis.documentos', 'mi.perfil'])) {
                $visible = true;
            }
            // 2. Gestión Jefes (Agentes a Cargo)
            elseif ($routeMapped == 'fichadas.jefe') {
                $visible = $user->hasRole(['ADMIN', 'RRHH', 'JEFE']);
            }
            // 3. Gestión Novedades
            elseif ($routeMapped == 'gestion.novedades' || $routeMapped == 'gestionar.novedades') {
                $visible = $user->hasRole(['ADMIN', 'GESTOR_NOVEDADES']);
            }
            // 4. Configuración (Solo Admin)
            elseif ($routeMapped == 'configuracion') {
                $visible = $user->isAdmin();
            }
            // 5. Resto de módulos (Gestión RRHH / Admin)
            else {
                $visible = $user->hasRole(['ADMIN', 'RRHH']);
            }


            if ($visible) {
                // Asignación de iconos manual
                $icon = match($routeMapped) {
                    'dashboard'                 => 'bi bi-house-door',
                    'mis.fichadas'              => 'bi bi-clock',
                    'mis.licencias'             => 'bi bi-calendar3',
                    'mi.perfil'                 => 'bi bi-person',
                    'fichadas.jefe'             => 'bi bi-people',
                    'gestion.usuarios'          => 'bi bi-person-badge',
                    'gestion.novedades'         => 'bi bi-bell',
                    'gestionar.licencias'       => 'bi bi-file-earmark-medical',
                    'gestion.tipos.licencias'   => 'bi bi-list-check',
                    'configuracion'             => 'bi bi-gear',
                    default                     => 'bi bi-grid'
                };


                // MEJORA: Lógica de URL para evitar el '#'
                if (Route::has($routeMapped)) {
                    $finalUrl = route($routeMapped);
                } elseif (Route::has($moduloClaveBD)) {
                    $finalUrl = route($moduloClaveBD);
                } else {
                    $finalUrl = url('/' . ltrim($moduloClaveBD, '/'));
                }


                $sidebarItems[] = [
                    'nombre' => $mod->modulo_nombre,
                    'route'  => $routeMapped,
                    'url'    => $finalUrl,
                    'icon'   => $icon,
                    'active' => request()->is($moduloClaveBD . '*') || request()->is($routeMapped . '*')
                ];
            }
        }


        $view->with('sidebarItems', $sidebarItems);
    }
}




