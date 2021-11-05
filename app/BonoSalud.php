<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class BonoSalud extends Model
{
    protected $table = 'bono_salud';

    protected $primaryKey = 'id';

    protected $fillable = [
    	'user_id',
    	'oficina_id',
        'saldo_inicial',
        'tipo_cambio_sk',
        'tipo_cambio_usd',
    	'costo_moneda_local',
    	'costo_sk',
        'saldo_final',
    	'destino',
    	'fecha_solicitud',
    	'documento',
    	'status',
    ];
   
    public $timestamps = false;

    public function user()
    {
    	return $this->belongsTo('Vanguard\User');
    }

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }

    public function documento()
    {
        return $this->hasMany('Vanguard\BonoSaludDocumento');
    }

    public function actividad()
    {
        return $this->hasOne('Vanguard\Actividad', 'bono_salud_id', 'id');
    }
}