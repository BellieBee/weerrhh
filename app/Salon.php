<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    protected $table = 'salones_reuniones';

    protected $primaryKey = 'id';

    protected $fillable = [
    	'salon',
        'oficina_id',
    	'cantidad_personas_max'
    ];
   
    public $timestamps = false;

    public function oficina()
    {
    	$this->belongsTo('App\Oficina');
    }

    public function reunion()
    {
    	return $this->hasMany('App\Reunion');
    }
}
