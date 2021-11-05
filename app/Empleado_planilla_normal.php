<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Empleado_planilla_normal extends Model
{
    protected $table = 'empleados_planillas_normales';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'nombre',       
        'n_contrato',       
        'fecha_inicio',       
        'documento',       
        'cargo',
        'fecha_inicio',       
        'dias_trabajados',
        'salario_base',
        'ajuste',
        'total_salario',
        'liquido_recibir',
        'total_aguinaldo',
        'total_pension',
        'total_indemnizacion',
        'planilla_id',
        'user_id'
              
    ];

    public function planilla()
    {
    	return $this->belongsTo('Vanguard\Planilla');
    }

    public function user()
    {
    	return $this->belongsTo('Vanguard\User');
    }
    
    public function deduccion()
    {
        return $this->hasOne('Vanguard\Deduccion','empleado_id');
    }

    public function aporte()
    {
        return $this->hasOne('Vanguard\Aporte','empleado_id');
    }
    public function acumulado()
    {
        return $this->hasOne('Vanguard\Acumulado','empleado_id');
    }
}
