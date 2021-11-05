<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Reunion extends Model
{
    protected $table = 'reuniones';

    protected $primaryKey = 'id';

    protected $fillable = [
    	'fecha_solicitud',
        'oficina_id',
    	'descp_evento',
    	'user_id',
        'salon_id',
        'insumo',
        'fecha',
        'dia_completo',
        'fecha_desde',
        'fecha_hasta'
    ];
   
    public $timestamps = false;

    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function salon()
    {
    	return $this->belongsTo('App\Salon');
    }
}
