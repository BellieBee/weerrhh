<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class ActividadDocumento extends Model
{

    protected $table = 'compras_actividades_documentos';

    protected $primaryKey = 'id';

    protected $fillable = [

    	'actividad_id',
    	'fecha',
        'tipo_documento_compras_id',
    	'file',
    	'proveedor_id',
    ];

    public $timestamps = false;

    public function actividad()
    {
        return $this->belongsTo('Vanguard\Actividad');
    }

    public function tipo_documento()
    {
        return $this->belongsTo('Vanguard\TipoDocumentoCompra', 'tipo_documento_compras_id', 'id');
    }

    public function proveedor()
    {
        return $this->belongsTo('Vanguard\Proveedor');
    }
}
