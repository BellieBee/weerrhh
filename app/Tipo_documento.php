<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Tipo_documento extends Model
{
    protected $table = 'tipo_documentos';

    protected $primaryKey = 'id';
    public $timestamps = false;
    

    protected $fillable = ['tipo_documento'];

    public function user()
    {
    	return $this->hasMany('App\User');
    }
}
