<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 't_roles';
    protected $primaryKey = 'rol_id';
    public $timestamps = false;
    protected $guarded = [];
}
