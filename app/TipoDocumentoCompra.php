<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoCompra extends Model
{
    protected $table = 'tipo_documento_compras';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
    	'nombre',
    ];

    public function actividadDoc()
    {
    	return $this->hasMany('Vanguard\ActividadDocumento', 'tipo_documento_compras_id', 'id');
    }

}
