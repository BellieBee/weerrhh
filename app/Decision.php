<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    protected $table = 'decisiones';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
    	'correlativo',
    	'fecha',
    	'actividad_id',
        'oficina_id',
    	'antecedentes',
    	'decision',
    	'proveedor_id',
        'proveedor_user_id',
    	'notas',
    	'justificacion',
    	'aprobacion_1',
    	'aprobacion_2',
        'aprobador_1_id',
        'aprobador_2_id',
    	'fecha_aprobacion_1',
    	'fecha_aprobacion_2',
    ];

    public function actividad() 
    {
    	return $this->belongsTo('Vanguard\Actividad');
    }

    public function oficina()
    {
        return $this->belongsTo('Vanguard\Oficina');
    }

    public function proveedor() 
    {
    	return $this->belongsTo('Vanguard\Proveedor');
    }

    public function proveedorUser()
    {
        return $this->belongsTo('Vanguard\User', 'proveedor_user_id', 'id');
    }

    public function documento()
    {
    	return $this->hasMany('Vanguard\DecisionDocumento');
    }

    public function aprobador_1()
    {
        return $this->belongsTo('Vanguard\User', 'aprobador_1_id', 'id');
    }

    public function aprobador_2()
    {
        return $this->belongsTo('Vanguard\User', 'aprobador_2_id', 'id');
    }

    public function ordencompra()
    {
        return $this->hasOne('Vanguard\OrdenCompra');
    }
}
