<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Aporte_patronal extends Model
{
     protected $table = 'aportes_patronales';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = [
        'empleado_id',
    	'planilla_id',

    	//Bolivia
        'seguro_universitario',
        'afp_prevision',
        'afp_prevision_pnvs',
        'afp_aporte_solidario',
        'provision_aguinaldo',

        //Salvador
        'afp',

        //Nicaragua
        'inatec',

        //Paraguay
        'total_aporte',

        //TODOS
        'seguridad_social'
    ];

    public function planilla() 
    {
    	return $this->belongsTo('Vanguard\Planilla');
    }

    public function empleado()
    {
    	return $this->belongsTo('Vanguard\Empleado_planilla_aguinaldo');
    }

}
