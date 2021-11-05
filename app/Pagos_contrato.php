<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Pagos_contrato extends Model
{
    protected $table = 'pagos_contratos';      

    protected $fillable = ['monto', 'monto_l', 'producto', 'contrato_id'];

    public function contrato()
    {
    	return $this->belongsTo('Vanguard\Contrato');
    }
}
