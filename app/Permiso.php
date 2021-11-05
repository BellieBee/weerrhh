<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';  
    

    protected $fillable = ['tipo','motivo','fecha_inicio','fecha_fin','num_dh','dh','aprobacion_coordinadora','user_id','oficina_id'];

    public function user()
    {
    	return $this->belongsTo('Vanguard\User');
    }

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }
}
