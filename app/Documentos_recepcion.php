<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Documentos_recepcion extends Model
{
    protected $table = 'documentos_recepcion';
	protected $fillable = [
        'titulo',       
        'tipo',       
        'prioridad',       
        'descripcion',       
        'recepcion_id',      
    ];

    public function recepcion()
    {
    	return $this->belongsTo('Vanguard\Recepcion');
    }
}
