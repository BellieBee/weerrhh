<?php

namespace Vanguard;

use Vanguard\Oficina;
use Vanguard\User;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contratos';

    protected $primaryKey = 'id';    
    
    protected $fillable = [
    	'n_contrato',
    	'fecha',    	
    	'consultoria',
    	'objetivo',
    	'alcance',
    	'actividades',
    	'metodologia',
    	'fecha_inicio',
    	'fecha_fin',
    	'monto_total',
        'monto_total_l',
        'cumplimiento',    	
    	'productos',
    	'fecha_contrato',
        'status',
        'aprobacion_coordinadora',
        'fecha_aprobacion_coordinadora',
        'aprobacion_directora',
    	'fecha_aprobacion_directora',
        'user_id',
        'oficina_id',
        'fecha_contrato',
        'ordencompra_id',
        'proveedor_id',
        'proveedor_user_id',
    ];

    public function user()
    {
    	return $this->belongsTo('Vanguard\User');
    }

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }
    public function pagos()
    {
        return $this->hasMany('Vanguard\Pagos_contrato');
    }
    public function documentos()
    {
        return $this->hasMany('Vanguard\Upload_documento');
    }
    public function adendas()
    {
        return $this->hasMany('Vanguard\Adenda');
    }
    public function coordinadora()
    {
        return $this->belongsTo('Vanguard\User');
    }

    public function directora()
    {
        return $this->belongsTo('Vanguard\User');
    }

    public function direct()
    {
        return $this->belongsTo('Vanguard\User', 'direct_id', 'id');
    }

    public function representante()
    {
        return $this->belongsTo('Vanguard\User', 'representante_id', 'id');
    }

    public function ordencompra()
    {
        return $this->belongsTo('Vanguard\OrdenCompra', 'ordencompra_id', 'id');
    }

    public function pago()
    {
        return $this->hasOne('Vanguard\PagoCompra');
    }

    public function proveedor() 
    {
        return $this->belongsTo('Vanguard\Proveedor');
    }

    public function proveedorUser()
    {
        return $this->belongsTo('Vanguard\User', 'proveedor_user_id', 'id');
    }

    /*public function representantePais($contratos)
    {
        $arrayOficinas = array();

        foreach ($contratos as $key => $contrato) {
            
            $oficinas = Oficina::where('pais_id', $contrato->oficina->pais->id)->get();

            foreach ($oficinas as $key => $oficina) {
                
                $users = User::where('status', 1)->where();
            }
        }
    }*/
}
