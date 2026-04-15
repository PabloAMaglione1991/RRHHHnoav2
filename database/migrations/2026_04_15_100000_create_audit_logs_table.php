<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_audit_logs', function (Blueprint $col) {
            $col->id();
            $col->unsignedBigInteger('user_id')->nullable();
            $col->string('accion'); // ej: APROBAR_LICENCIA
            $col->string('modelo_tipo')->nullable(); // ej: SolicitudLicencia
            $col->unsignedBigInteger('modelo_id')->nullable();
            $col->text('detalles')->nullable(); // JSON o texto con info extra
            $col->string('ip_address', 45)->nullable();
            $col->string('user_agent')->nullable();
            $col->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_audit_logs');
    }
}
