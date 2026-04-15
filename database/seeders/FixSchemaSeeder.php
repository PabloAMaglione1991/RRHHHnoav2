<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class FixSchemaSeeder extends Seeder
{
    public function run()
    {
        if (Schema::hasTable('t_novedades') && !Schema::hasColumn('t_novedades', 'fijada')) {
            Schema::table('t_novedades', function (Blueprint $table) {
                $table->boolean('fijada')->default(false)->after('contenido');
            });
            $this->command->info('Columna "fijada" agregada correctamente.');
        } else {
            $this->command->info('La columna "fijada" ya existe o la tabla no. Omisión.');
        }
    }
}
