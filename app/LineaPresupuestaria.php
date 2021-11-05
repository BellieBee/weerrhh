<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class LineaPresupuestaria extends Model
{
    protected $table = 'lineas_presupuestarias';

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
        return $this->hasMany('Vanguard\ActividadDetalle', 'linea_presupuestaria_id', 'id');
    }
}
