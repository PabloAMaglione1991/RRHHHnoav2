# Documentación Técnica: PortalHnoaRRHH v1.0

## 1. Visión General
El Portal de RRHH es una aplicación puente diseñada para modernizar la interacción de los agentes (empleados) con el sistema de gestión de personal legado (Chronos/Factu30). Permite la autogestión de licencias, consulta de fichadas y visualización de novedades de liquidación.

## 2. Arquitectura de Software
- **Core**: Laravel 8.75 (PHP 7.3+)
- **Frontend**: Livewire 2.0 + Alpine.js (TALL Stack parcial)
- **Persistencia**: MySQL (MariaDB compatible)

## 3. Integración de Datos (Modelo Híbrido)
El sistema utiliza dos conexiones de base de datos principales definidas en `config/database.php`:

| Conexión | Propósito | Tecnología |
| :--- | :--- | :--- |
| `mysql` | Datos locales del portal (Usuarios, Roles, Licencias, Caché) | MySQL Local |
| `reloj` | Datos de hardware de fichaje y sistema legado | MySQL Externo (10.12.4.2) |

### Entidades Legadas Cruciales:
- `t_agente`: Tabla maestra de empleados en el sistema legado.
- `t_fichadas`: Registro bruto de marcas de reloj.
- `t_inasist`: Registro de inasistencias y licencias procesadas.
- `t_age_tarj`: Vinculación entre Agente y Número de Tarjeta/Reloj.

## 4. Lógica de Negocio Crítica

### 4.1. Sincronización de Fichadas (`FichadaService`)
El sistema no consulta el reloj en tiempo real para cada vista. En su lugar:
1. Identifica la tarjeta activa del agente (`t_age_tarj`).
2. Busca la última fecha sincronizada localmente.
3. Descarga de la conexión `reloj` solo las fichadas posteriores a esa fecha.
4. Las inserta en la tabla local `t_fichadas`.

### 4.2. Cálculo de Horas y Estados (E/S)
Dado que las fichadas brutas a veces no discriminan entre Entrada (E) y Salida (S), el `FichadaService` aplica una lógica de inferencia:
- **Tolerancia**: 9000 segundos (2.5 horas).
- **Inferencia**: Si una ficihada ocurre dentro del rango de tolerancia de la "Hora de Entrada" definida en el horario del agente, se marca como `E`. Si está cerca de la "Hora de Salida", se marca como `S`.
- **Casos 24hs**: Si el agente tiene entrada y salida a la misma hora (turnos rotativos o 24hs), el sistema alterna el estado basado en la marca anterior.

### 4.3. Reglas de Facturación (`FacturacionService`)
El cálculo de haberes se basa en "Artículos" de inasistencia:
- **Regla 14A (Corta Duración)**:
    - 2 días en el mes -> 25% descuento.
    - 3 días en el mes -> 50% descuento.
    - >3 días en el mes -> 100% descuento.
- **Descuentos Directos**: Códigos como `AUS` (Ausente), `SUS` (Suspendido) o `PAR` (Paro) disparan automáticamente el 100% de descuento.

## 5. Flujo de Licencias
1. El Agente solicita una licencia (Modelo `SolicitudLicencia`).
2. El Jefe/RRHH recibe la notificación en el componente Livewire `GestionarLicencias`.
3. Al aprobar (`LicenciaService::aprobarSolicitud`):
    - Se cambia el estado en el portal.
    - Se mapea el `TipoLicencia` al `codigo_legacy` correspondiente en `t_artic`.
    - **Sincronización Inversa**: Se inyectan registros día por día en la tabla legado `t_inasist` para que el sistema de sueldos viejo lo reconozca.

## 6. Mantenimiento y Troubleshooting
- **Logs**: Errores de conexión con la base `reloj` se registran en `storage/logs/laravel.log`.
- **Caché**: Se recomienda limpiar caché de configuración tras cambios en el archivo `.env` o `config/database.php` mediante `php artisan config:clear`.
