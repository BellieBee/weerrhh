<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $primaryKey = 'id';
    
    public $timestamps = false;

    protected $fillable = ['categoria'];

    public function user()
    {
    	return $this->hasMany('App\User');
    }
}
