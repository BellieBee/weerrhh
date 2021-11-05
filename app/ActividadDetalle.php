<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class ActividadDetalle extends Model
{
    protected $table = 'compras_actividades_detalle';

    protected $primaryKey = 'id';

    protected $fillable = [

    	'actividad_id',
        'factura',
    	'proyecto_id',
        'cuenta_id',
        'centro_costo_id',
        'linea_presupuestaria_id',
    	'file',
    	'monto',
    ];

    public $timestamps = false;

    public function actividad()
    {
        return $this->belongsTo('Vanguard\Actividad');
    }

    public function proyecto()
    {
    	return $this->belongsTo('Vanguard\Proyecto', 'proyecto_id', 'id');
    }

    public function cuenta()
    {
    	return $this->belongsTo('Vanguard\CuentaContable', 'cuenta_id', 'id');
    }

    public function centroCosto()
    {
        return $this->belongsTo('Vanguard\CentroCosto', 'centro_costo_id', 'id');
    }

    public function lineaPresupuestaria()
    {
        return $this->belongsTo('Vanguard\LineaPresupuestaria', 'linea_presupuestaria_id', 'id');
    }

}
