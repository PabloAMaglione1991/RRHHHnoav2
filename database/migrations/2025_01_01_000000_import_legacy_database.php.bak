<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportLegacyDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ruta al archivo
        $path = 'c:\Users\pablo\Desktop\fact30_prod2';

        if (File::exists($path)) {
            // Leer contenido
            $sql = File::get($path);
            // Ejecutar SQL (puede fallar si el archivo es gigante, pero es la mejor opcion sin credenciales CLI)
            // DB::unprepared soporta multiples sentencias
            DB::unprepared($sql);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No hay vuelta atras facil para un dump entero
    }
}
