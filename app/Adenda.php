<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Adenda extends Model
{
    protected $table = 'adendas';      

    protected $fillable = ['fecha_contrato', 'fecha_contrato_nueva', 'fecha_cumplimineto','fecha_cumplimiento_nueva','motivo','contrato_id'];

    public function contrato()
    {
    	return $this->belongsTo('Vanguard\Contrato');
    }
}
