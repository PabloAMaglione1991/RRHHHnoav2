<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToAgentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_agente', function (Blueprint $table) {
            if (!Schema::hasColumn('t_agente', 'muros')) {
                $table->string('muros', 20)->default('INTRA')->nullable()->after('age_observ');
            }
            if (!Schema::hasColumn('t_agente', 'no_registra_horario')) {
                $table->boolean('no_registra_horario')->default(false)->after('muros');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_agente', function (Blueprint $table) {
            if (Schema::hasColumn('t_agente', 'muros')) {
                $table->dropColumn('muros');
            }
            if (Schema::hasColumn('t_agente', 'no_registra_horario')) {
                $table->dropColumn('no_registra_horario');
            }
        });
    }
}
