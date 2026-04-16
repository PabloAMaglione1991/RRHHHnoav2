<?php


namespace App\Http\View\Composers;


use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Modulo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Log;

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
            // NORMALIZACIÓN ROBUSTA:
            // 1. Quitar acentos y caracteres especiales
            // 2. Reemplazar espacios, guiones y guiones bajos por puntos
            // 3. Convertir a minúsculas
            $moduloClaveBD = $mod->modulo_clave;
            
            $routeMapped = strtolower($moduloClaveBD);
            $routeMapped = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $routeMapped);
            $routeMapped = str_replace([' ', '-', '_'], '.', $routeMapped);
            $routeMapped = preg_replace('/\.+/', '.', $routeMapped); // Evitar dobles puntos
            $routeMapped = trim($routeMapped, '.');

            $visible = false;

            // 1. Accesos Comunes (Todo Agente)
            if (in_array($routeMapped, ['dashboard', 'mis.fichadas', 'mis.licencias', 'mis.documentos', 'mi.perfil'])) {
                $visible = true;
            }
            // 2. Gestión Jefes (Agentes a Cargo)
            elseif (str_contains($routeMapped, 'fichadas.jefe') || str_contains($routeMapped, 'agentes')) {
                $routeMapped = 'fichadas.jefe';
                $visible = $user->hasRole(['ADMIN', 'RRHH', 'JEFE']);
            }
            // 3. Gestión Novedades
            elseif (str_contains($routeMapped, 'novedades')) {
                $routeMapped = 'gestion.novedades';
                $visible = $user->hasRole(['ADMIN', 'GESTOR_NOVEDADES']);
            }
            // 4. Configuración (Solo Admin)
            elseif (str_contains($routeMapped, 'configuracion') || str_contains($routeMapped, 'ajustes')) {
                $routeMapped = 'configuracion';
                $visible = $user->isAdmin();
            }
            // 5. Gestión RRHH / Otros
            else {
                // Intentamos mapear otros comunes
                if (str_contains($routeMapped, 'usuarios') || str_contains($routeMapped, 'personal')) {
                    $routeMapped = 'gestion.usuarios';
                } elseif (str_contains($routeMapped, 'horarios')) {
                    $routeMapped = 'gestion.horarios';
                } elseif (str_contains($routeMapped, 'tipos.licencias')) {
                    $routeMapped = 'gestion.tipos.licencias';
                } elseif (str_contains($routeMapped, 'reportes')) {
                    $routeMapped = 'reportes';
                }

                $visible = $user->hasRole(['ADMIN', 'RRHH']);
            }

            if ($visible) {
                // Asignación de iconos basada en la ruta normalizada
                $icon = match($routeMapped) {
                    'dashboard'                 => 'bi bi-house-door',
                    'mis.fichadas'              => 'bi bi-clock',
                    'mis.licencias'             => 'bi bi-calendar3',
                    'mis.documentos'            => 'bi bi-file-earmark-text',
                    'mi.perfil'                 => 'bi bi-person-circle',
                    'fichadas.jefe'             => 'bi bi-people-fill',
                    'gestion.usuarios'          => 'bi bi-person-badge-fill',
                    'gestion.novedades'         => 'bi bi-megaphone-fill',
                    'gestionar.licencias'       => 'bi bi-clipboard-pulse',
                    'gestion.horarios'          => 'bi bi-alarm',
                    'gestion.tipos.licencias'   => 'bi bi-list-stars',
                    'reportes'                  => 'bi bi-bar-chart-fill',
                    'reportes.rrhh'             => 'bi bi-graph-up-arrow',
                    'configuracion'             => 'bi bi-gear-fill',
                    default                     => 'bi bi-grid-fill'
                };

                // MEJORA: Priorizamos el nombre de la ruta normalizada si existe en Laravel
                $finalUrl = '#';
                if (Route::has($routeMapped)) {
                    $finalUrl = route($routeMapped);
                } elseif (Route::has($moduloClaveBD)) {
                    $finalUrl = route($moduloClaveBD);
                } else {
                    $finalUrl = url('/' . ltrim($moduloClaveBD, '/'));
                }

                \Log::info("DEBUG SIDEBAR: DB_Key: '{$moduloClaveBD}' | Normalized: '{$routeMapped}' | Final_URL: '{$finalUrl}'");

                $sidebarItems[] = [
                    'nombre' => $mod->modulo_nombre,
                    'route'  => $routeMapped,
                    'url'    => $finalUrl,
                    'icon'   => $icon,
                    'active' => request()->is($moduloClaveBD . '*') || request()->is($routeMapped . '*') || request()->routeIs($routeMapped)
                ];
            }
        }


        $view->with('sidebarItems', $sidebarItems);
    }
}




