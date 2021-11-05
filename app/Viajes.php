<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Viajes extends Model
{
    protected $table = 'viajes';    
    
    protected $fillable = ['fechas','num_dh','dh','aprobacion_directora','user_id','oficina_id', 'destino', 'comentarios'];

    public function user()
    {
    	return $this->belongsTo('Vanguard\User');
    }

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }
}
