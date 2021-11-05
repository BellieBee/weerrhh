<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{

    protected $table = 'actividades';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [

    	'correlativo',
    	'fecha',
    	'user_id',
    	'admin_id',
    	'oficina_id',
        'bono_salud_id',
    	'actividad',
    	'proyecto_id',
    	'comentarios',
    	'cuenta_id',
    	'tipo_compra_id',
        'proveedor_id',
        'proveedor_user_id',
    	'monto',
    	'tipo_moneda_id',
    	'monto_sk',
    	'monto_usd',
    	'n_factura',
    	'aprobacion_1',
    	'aprobacion_2',
    	'fecha_aprobacion_1',
    	'fecha_aprobacion_2'
    ];

    public function user() 
    {
    	return $this->belongsTo('Vanguard\User');
    }

    public function admin()
    {
        return $this->belongsTo('Vanguard\User', 'admin_id', 'id');
    }

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }

    public function bonosalud()
    {
        return $this->belongsTo('Vanguard\BonoSalud', 'bono_salud_id', 'id');
    }

    public function proyecto()
    {
    	return $this->belongsTo('Vanguard\Proyecto', 'proyecto_id', 'id');
    }

    public function cuenta()
    {
    	return $this->belongsTo('Vanguard\CuentaContable');
    }

    public function tipo_compra()
    {
    	return $this->belongsTo('Vanguard\TipoCompra', 'tipo_compra_id', 'id');
    }

    public function tipo_moneda() 
    {
        return $this->belongsTo('Vanguard\Pais', 'tipo_moneda_id', 'id');
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
        return $this->hasMany('Vanguard\ActividadDocumento', 'actividad_id', 'id');
    }

    public function detalle()
    {
        return $this->hasMany('Vanguard\ActividadDetalle', 'actividad_id', 'id');
    }

    public function decision()
    {
        return $this->hasOne('Vanguard\Decision');
    }

    public function ordencompra()
    {
        return $this->hasOne('Vanguard\OrdenCompra');
    }

    public function pago()
    {
        return $this->hasOne('Vanguard\PagoCompra');
    }

}
