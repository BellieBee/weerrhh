<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class DecisionDocumento extends Model
{
    protected $table = 'compras_decisiones_documentos';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
    	'decision_id',
    	'fecha',
    	'tipo_documento_compras_id',
    	'file',
    	'proveedor_id',
    ];

    public function decision()
    {
    	return $this->belongsTo('Vanguard\Decision');
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
