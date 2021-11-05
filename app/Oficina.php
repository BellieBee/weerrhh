<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    protected $table = 'oficinas';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = ['oficina', 'pais_id', 'central', 'direccion', 'telf', 'nit', 'num_patronal', 'moneda_simbolo', 'moneda_nombre'];

    public function user()
    {
    	return $this->hasMany('Vanguard\User');
    }

    public function pais()
    {
    	return $this->belongsTo('Vanguard\Pais', 'pais_id', 'id');
    }

    public function monedaSimbolo() 
    {
    	return $this->hasOne('Vanguard\Pais', 'moneda_simbolo');
    }

    public function monedaNombre()
    {
    	return $this->hasOne('Vanguard\Pais', 'moneda_nombre');
    }

    public function planilla()
    {
        return $this->hasOne('App\Planilla');
    }

    public function planilla_aguinaldo()
    {
        return $this->hasOne('App\Planilla_aguinaldo');
    }

    public function contratos()
    {
        return $this->hasMany('App\Contrato');
    }

    public function reunion()
    {
        return $this->hasMany('App\Reunion');
    }

    public function salon()
    {
        return $this->hasMany('App\Salon');
    }
}
