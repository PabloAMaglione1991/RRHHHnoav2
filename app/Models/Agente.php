<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Agente extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $table = 't_agente';
    protected $primaryKey = 'age_id';
    public $timestamps = false;


    /**
     * ATRIBUTOS QUE SE PUEDEN CARGAR MASIVAMENTE
     */
    protected $fillable = [
        'age_nombre',
        'age_apell1',
        'age_numint',
        'age_numdoc',
        'age_cuil',
        'age_domip',
        'age_tele1',
        'age_tele2',
        'age_fecnac',
        'age_activo',
        'tdep_id',
        'jefe_age_id',
        'tsex_id',
        'tpai_id',
        'tpro_id',
        'tloc_id'
    ];


    // Relación con Tarjeta (para Facturación)
    public function ageTarj()
    {
        return $this->hasOne(AgeTarj::class, 'age_id', 'age_id')->where('agetarj_activa', 1);
    }


    public function roles()
    {
        return $this->belongsToMany(Rol::class, 't_agente_roles', 'age_id', 'rol_id');
    }


    /**
     * Verifica si el agente tiene uno de los roles especificados.
     */
    public function hasRole($roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }


        $userRoles = $this->roles->pluck('rol_nombre')->map(function ($role) {
            return strtoupper($role);
        })->toArray();


        foreach ($roles as $role) {
            if (in_array(strtoupper($role), $userRoles)) {
                return true;
            }
        }


        return false;
    }


    public function isAdmin()
    {
        return $this->hasRole('ADMIN');
    }


    public function isRrhh()
    {
        return $this->hasRole(['ADMIN', 'RRHH']);
    }


    public function isGestorNovedades()
    {
        return $this->hasRole(['ADMIN', 'GESTOR_NOVEDADES']);
    }


    // Relación con Departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'tdep_id');
    }


    // Relación con Jefe
    public function jefe()
    {
        return $this->belongsTo(Agente::class, 'jefe_age_id');
    }


    // Relación con Agentes a cargo (Subordinados)
    public function subordinados()
    {
        return $this->hasMany(Agente::class, 'jefe_age_id');
    }


    // Accessor para Nombre Completo
    public function getNombreCompletoAttribute()
    {
        return trim($this->age_nombre . ' ' . $this->age_apell1);
    }
}




