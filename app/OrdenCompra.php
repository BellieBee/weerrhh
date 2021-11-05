<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    protected $table = 'ordenes_compras';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
    	'correlativo',
        'actividad_id',
    	'decision_id',
        'oficina_id',
    	'fecha',
    	'solicitante_id',
    	'proveedor_id',
        'proveedor_user_id',
    	'cuenta_id',
    	'descripcion',
    	'monto',
    	'monto_usd',
    	'monto_sk',
    	'aprobacion_solicitante',
    	'aprobacion_1',
    	'aprobacion_2',
    	'aprobador_1_id',
    	'aprobador_2_id',
    	'fecha_aprobacion_1',
    	'fecha_aprobacion_2',
    ];

    public function actividad()
    {
        return $this->belongsTo('Vanguard\Actividad', 'actividad_id', 'id');
    }

    public function decision()
    {
    	return $this->belongsTo('Vanguard\Decision', 'decision_id', 'id');
    }

    public function oficina()
    {
        return $this->belongsTo('Vanguard\Oficina');
    }

    public function solicitante()
    {
        return $this->belongsTo('Vanguard\User', 'solicitante_id', 'id');
    }

    public function proveedor()
    {
    	return $this->belongsTo('Vanguard\Proveedor', 'proveedor_id', 'id');
    }

    public function proveedorUser()
    {
        return $this->belongsTo('Vanguard\User', 'proveedor_user_id', 'id');
    }

    public function cuenta()
    {
    	return $this->belongsTo('Vanguard\CuentaContable');
    }

    public function aprobador_1()
    {
        return $this->belongsTo('Vanguard\User', 'aprobador_1_id', 'id');
    }

    public function aprobador_2()
    {
        return $this->belongsTo('Vanguard\User', 'aprobador_2_id', 'id');
    }

    public function contrato()
    {
        return $this->hasOne('Vanguard\Contrato', 'ordencompra_id', 'id');
    }

    public function pago()
    {
        return $this->hasOne('Vanguard\PagoCompra', 'orden_compra_id', 'id');
    }
}
