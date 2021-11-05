<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $table = 'insumos';

    protected $primaryKey = 'id';

    protected $fillable = ['insumo'];
   
    public $timestamps = false;
}
