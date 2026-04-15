<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgeTarj extends Model
{
    use HasFactory;

    protected $table = 't_age_tarj';
    protected $primaryKey = 'agetarj_id';
    public $timestamps = false;

    protected $guarded = [];

    public function agente()
    {
        return $this->belongsTo(Agente::class, 'age_id');
    }

    public function contraseniaWeb()
    {
        // Relación via tarjeta numero
        return $this->hasOne(ContraseniaWeb::class, 'cw_tar_id', 'tarj_nro');
    }
}
