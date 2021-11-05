<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';

    public $timestamps = false;

    protected $fillable = [
    	'oficina_id',
    	'pais_id',
    	'nombre',
        'descripcion',
    	'fecha_inicio',
    	'fecha_fin'
    ];

    public function actividad()
    {
        return $this->hasMany('Vanguard\Actividad', 'proyecto_id', 'id');
    }

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
        return $this->hasMany('Vanguard\ActividadDetalle', 'proyecto_id', 'id');
    }    
}
