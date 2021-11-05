<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
    	'oficina_id',
    	'pais_id',
    	'nombre',
    	'email',
    	'telf',
    	'razon_social',
    	'nit',
    	'direccion_fiscal',
    	'giro_negocio',
    	'proveedor_desde',
    	'banco',
    	'n_cuenta_bancaria',
    	'tipo_cuenta',
    	'moneda',
    	'email_notf_pago',
    ];

    public function actividad()
    {
        return $this->hasMany('Vanguard\Actividad', 'proveedor_id', 'id');
    }

    public function decision() 
    {
        return $this->hasMany('Vanguard\Decision', 'proveedor_id', 'id'); 
    }

    public function ordencompra()
    {
        return $this->hasMany('Vanguard\OrdenCompra', 'proveedor_id', 'id');
    }

    public function pago()
    {
        return $this->hasOne('Vanguard\PagoCompra', 'proveedor_id', 'id');
    }

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina', 'oficina_id', 'id');
    }

    public function pais() 
    {
    	return $this->belongsTo('Vanguard\Pais', 'pais_id', 'id');
    }
}
