<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Profesion extends Model
{
    protected $table = 'profesiones';

    protected $primaryKey = 'id';

    protected $fillable = ['profesion'];
    
    public $timestamps = false;
    
    public function user()
    {
    	return $this->hasMany('Vanguard\User');
    }
}
