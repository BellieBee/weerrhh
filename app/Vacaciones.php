<?php

namespace Vanguard;

use DB;
use Vanguard\Month;
use Vanguard\User;
use Illuminate\Database\Eloquent\Model;

class Vacaciones extends Model
{
    protected $table = 'vacaciones';    
    

    protected $fillable = ['fechas','num_dh','dh','aprobacion_directora','user_id','oficina_id',];

   	public function user()
    {
    	return $this->belongsTo('Vanguard\User');
    }

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }

    public function acumulate($mesDesde, $pais)
    {
        $oficinas_array = array();
        foreach($pais->oficinas as $oficina)
        {
            array_push($oficinas_array, $oficina->id);
        }

        $users = User::whereIn('oficina_id', $oficinas_array)->where('status', 1)->get();
        $solicitudes_vacaciones = 0;
        $vacaciones = 0;

        foreach ($users as $user) 
        {
            if (count($user->planilla) == 0) 
            {
                $user->acumulado_vacaciones;
            }
            else 
            {
                $calculate = $this->conteoPlanilla($user, $mesDesde);

                $count = $calculate['count'];
                $dic = $calculate['dic'];

                $vac_dic = $pais->vac_dic;

                $vacaciones = $user->acumulado_vacaciones + ($pais->vacaciones * $count);

                if($dic != 0 && $count == 0) {
                    $vacaciones = $user->acumulado_vacaciones + ($vac_dic * $dic);
                }

                if($dic != 0 && $count >= 1) {
                    $vacaciones = $user->acumulado_vacaciones + ($pais->vacaciones * $count) + ($vac_dic * $dic);
                }
            }

            //$solicitudes_vacaciones = Vacaciones::whereYear('created_at','>=','2019')->where('user_id', $user->id)->get();
            
            $solicitudes_vacaciones = Vacaciones::whereDate('created_at', '>=', '2019-'.$mesDesde.'-01')
                ->where('user_id', $user->id)
                ->get();

            if (count($solicitudes_vacaciones) > 0) 
            {
                $vacaciones = round($vacaciones - $solicitudes_vacaciones->sum('num_dh'), 2);
            }

            if($vacaciones <= 0) 
            {
                $consult = User::where('id', $user->id)->first();
                $consult->acumulate = 0;
                $consult->save();                    
            }
            else 
            {
                $consult = User::where('id', $user->id)->first();
                $consult->acumulate = $vacaciones;
                $consult->save();   
            }
        }
    }

    private function conteoPlanilla($user, $mesDesde)
    {
        $year = 2019;
        $count = 0;
        $dic = 0;

        foreach($user->planilla as $planilla)
        {
            if((substr($planilla->planilla->m_a, 0, -5) != 'Diciembre') && (substr($planilla->planilla->m_a, -4) >= $year))
            {
                $mes_planilla = Month::where('month', substr($planilla->planilla->m_a, 0, -5))->first();
                if($mes_planilla->id >= $mesDesde)
                {
                    $count++;   
                }
            }
            if((substr($planilla->planilla->m_a, 0, -5) == 'Diciembre') && (substr($planilla->planilla->m_a, -4) >= $year))
            {
                $dic++;
            }
        }

        return [
            'count' => $count,
            'dic' => $dic,
        ];
    }
}
