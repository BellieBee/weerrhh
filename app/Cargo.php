<?php

namespace Vanguard;

use Vanguard\User;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';

    protected $primaryKey = 'id';

    protected $fillable = ['cargo'];
   
    public $timestamps = false;
   
    public function user()
    {
    	return $this->hasMany('Vanguard\User');
   	}

   	public function empleado_planilla_normal()	
   	{
   		return $this->belongsTo('App\Empleado_planilla_normal');
   	}

    public function superior()
    {
      return $this->belongsTo('Vanguard\Cargo', 'superior_id', 'id');
    }

    public function inferiores($userSuperior, $oficina_id)
    {
      $colegasInferiores = array();
      $cargosInferiores = Cargo::where('superior_id', $userSuperior)->get();
      
      foreach ($cargosInferiores as $cargo) {
        
        $colega = User::where('cargo_id', $cargo->id)->where('oficina_id', $oficina_id)->where('status', 1)->pluck('id');

        //dd($colega);

        if(count($colega) == 1) {

          //dd($colega->id);

          array_push($colegasInferiores, $colega[0]);
        }
        elseif (count($colega) > 1) {
          
          foreach ($colega as $col) {
            
            array_push($colegasInferiores, $col);
          }
        }
      }

      //dd($colegasInferiores);

      return $colegasInferiores;

    }
}
