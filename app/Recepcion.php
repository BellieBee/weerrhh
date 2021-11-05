<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
    protected $table = 'recepciones';   

    protected $fillable = ['fecha_recibe','recogido','oficina_id','user_id']; 
   
    
    public function documentos()
    {
        return $this->hasMany('Vanguard\Documentos_recepcion');
    }
    public function user()
    {
    	return $this->belongsTo('Vanguard\User');
    }
    public function oficina()
    {
    	return $this->belongsTo('Vanguard\Oficina');
    }
}
