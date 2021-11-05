<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class CentroCosto extends Model
{
    protected $table = 'centros_costo';

    public $timestamps = false;

    protected $fillable = [
    	'oficina_id',
    	'pais_id',
    	'codigo',
        'descripcion'
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
        return $this->hasMany('Vanguard\ActividadDetalle', 'centro_costo_id', 'id');
    }
}
