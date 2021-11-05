<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class BonoSaludDocumento extends Model
{
    protected $table = 'bono_salud_documentos';      

    protected $fillable = ['bono_salud_id', 'nombre_documento', 'documento'];

    public $timestamps = false;

    public function bono_salud()
    {
    	return $this->belongsTo('Vanguard\BonoSalud');
    }
    
}
