<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Planilla extends Model
{
    protected $table = 'planillas';

    protected $primaryKey = 'id';  

    protected $fillable = [        
        'aprobacion_coordinadora',
        'fecha_aprobacion_coordinadora',
        'aprobacion_directora',
        'fecha_aprobacion_directora',
        'tipo_planilla',
        'm_a',
        'porcentaje_pension_pais',
        'porcentaje_seguridad_social_pais',
        'campo_deducciones',
        'confirmada',
        'cambio',
        'oficina_id',
        'administradora_id',
        'coordinadora_id',
        'directora_id'
    ];  

    public function empleados()
    {
        return $this->hasMany('Vanguard\Empleado_planilla_normal');
    }
    public function deducciones()
    {
        return $this->hasMany('Vanguard\Deduccion');
    }
    public function aportes()
    {
        return $this->hasMany('Vanguard\Aporte');
    }
    public function acumulados()
    {
        return $this->hasMany('Vanguard\Acumulado');
    }
    public function aporte_patronal() 
    {
        return $this->hasMany('Vanguard\Aporte_patronal');
    }    

    public function administradora()
    {
    	return $this->belongsTo('Vanguard\User');
    }

    public function coordinadora()
    {
    	return $this->belongsTo('Vanguard\User');
    }

    public function directora()
    {
    	return $this->belongsTo('Vanguard\User');
    }
    public function oficina()
    {
        return $this->belongsTo('Vanguard\Oficina');
    }
    
}

