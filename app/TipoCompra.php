<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class TipoCompra extends Model
{
    protected $table = 'tipo_compra';

    public $timestamps = false;

    protected $fillable = [
    	'valor_generico',
    	'valor_inicial',
    	'valor_final',
    ];

}
