<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Deduccion extends Model
{
    protected $table = 'deducciones';
    
    public $timestamps = false;
   	
   	public function empleado()
    {
        return $this->belongsTo('Vanguard\Empleado_planilla_normal');
    }
    public function planilla()
    {
        return $this->belongsTo('Vanguard\Planilla');
    }
}
