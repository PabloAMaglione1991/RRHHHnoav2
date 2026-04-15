<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContraseniaWeb extends Model
{
    use HasFactory;

    protected $table = 't_contrasenias_web';
    protected $primaryKey = 'cw_tar_id';
    public $timestamps = false; // Maneja su propio timestamp 'cw_fec_pass'

    protected $fillable = ['cw_tar_id', 'cw_pass', 'cw_dias_caduc_pass'];

    // Relación opcional inversa
    public function ageTarj()
    {
        return $this->belongsTo(AgeTarj::class, 'tarj_nro', 'cw_tar_id');
    }
}
