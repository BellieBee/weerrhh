<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Acumulado extends Model
{
    protected $table = 'acumulados';    

    protected $fillable = [
        'oficina_id', 
        'user_id',
        'empleado_id',
        'year',
        'm_a',
        'catorceavo',
        'total_salario', 
        'aguinaldo',
        'pension',
        'indemnizacion',
        'retiro_trabajador', 
        'planilla_id', 
    ];    

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }

    public function user()
    {
    	return $this->belongsTo('Vanguard\User', 'user_id', 'id');
    }
    public function empleado()
    {
        return $this->belongsTo('Vanguard\Empleado_planilla_normal');
    }
    public function planilla()
    {
        return $this->belongsTo('Vanguard\Planilla');
    }
}
