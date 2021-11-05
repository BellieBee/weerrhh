<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $table = 'months';    

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = ['month']; 

    public function feriados()
    {
    	return $this->hasMany('Vanguard\Feriado', 'month_id');
    }
}
