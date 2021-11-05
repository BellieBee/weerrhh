<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Feriado extends Model
{
    protected $table = 'feriados';

    protected $primaryKey = 'id';

    protected $fillable = [
    	'dia',
        'month_id',
    	'pais_id',
    	'descripcion_feriado',
        'fecha'
    ];
   
    public $timestamps = false;

    public function pais()
    {
    	return $this->belongsTo('Vanguard\Pais');
    }

    public function month()
    {
        return $this->belongsTo('Vanguard\Month');
    }
}
