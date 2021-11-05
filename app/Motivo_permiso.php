<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Motivo_permiso extends Model
{
    protected $table = 'motivo_permisos';    
    
    public $timestamps = false;

    protected $fillable = ['id','motivo']; 
}
