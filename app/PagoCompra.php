<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class PagoCompra extends Model
{
    protected $table = 'pagos_compras';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
    	'actividad_id',
    	'orden_compra_id',
    	'contrato_id',
    	'oficina_id',
    	'tipopago_id',
    	'proveedor_id',
    	'proveedor_user_id',
        'tramitante_id',
    	'user_id',
    	'concepto',
    	'factura',
    	'cuenta_id',
    	'centro_costo',
    	'linea_presupuesta',
        'moneda_pago',
        'exo_imp',
    	'monto',
        'impuesto',
        'isr',
        'monto_total',
    	'monto_usd',
    	'monto_sk',
    	'fecha',
        'revision',
        'aprobador_id',
        'fecha_revision',
    ];

    public function actividad()
    {
    	return $this->belongsTo('Vanguard\Actividad');
    }

    public function ordencompra()
    {
    	return $this->belongsTo('Vanguard\OrdenCompra', 'orden_compra_id', 'id');
    }

    public function contrato()
    {
    	return $this->belongsTo('Vanguard\Contrato');
    }

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }

    public function tipopago()
    {
    	return $this->belongsTo('Vanguard\TipoPago', 'tipopago_id', 'id');
    }

    public function proveedor()
    {
    	return $this->belongsTo('Vanguard\Proveedor', 'proveedor_id', 'id');
    }

    public function proveedorUser()
    {
        return $this->belongsTo('Vanguard\User', 'proveedor_user_id', 'id');
    }

    public function tramitante()
    {
        return $this->belongsTo('Vanguard\User', 'tramitante_id', 'id');
    }

    public function user()
    {
    	return $this->belongsTo('Vanguard\User');
    }

    public function cuenta()
    {
    	return $this->belongsTo('Vanguard\CuentaContable');
    }

    public function aprobador()
    {
        return $this->belongsTo('Vanguard\User', 'aprobador_id', 'id');
    }
}
