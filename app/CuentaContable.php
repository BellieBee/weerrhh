<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class CuentaContable extends Model
{
    protected $table = 'cuentas_contables';

    public $timestamps = false;

    protected $fillable = [
    	'oficina_id',
    	'pais_id',
    	'nombre',
        'descripcion',
    ];

	public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }

    public function pais()
    {
    	return $this->belongsTo('Vanguard\Pais');
    }

    public function detalle()
    {
        return $this->hasMany('Vanguard\ActividadDetalle', 'cuenta_id', 'id');
    }
}
