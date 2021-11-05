<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class TipoCuentaBancaria extends Model
{
    protected $table = 'tipo_cuentas_bancarias';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
    	'nombre'
    ];
}
