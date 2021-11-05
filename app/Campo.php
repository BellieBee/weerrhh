<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Campo extends Model
{
    protected $table = 'nombre_campos';  

    protected $fillable = [
    	'salario_base',
		'ajustes',
		'total_salario',
		'catorceavo',
		'prestamo',
		'otras_deducciones',
		'impuestos',
		'total_deducciones',
		'seguridad_social',
		'liquido'
	];
   
    public $timestamps = false;
   
    

   	public function pais()	
   	{
   		return $this->belongsTo('Vanguard\Pais');
   	}
}
