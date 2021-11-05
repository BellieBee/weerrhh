<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class TipoPago extends Model
{
    protected $table = 'tipo_pagos';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
    	'nombre'
    ];

    public function pagos()
    {
    	return $this->hasMany('Vanguard\PagoCompra');
    }
}
