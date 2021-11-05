<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Upload_documento extends Model
{
    protected $table = 'upload_documentos';      

    protected $fillable = ['nombre', 'documento', 'contrato_id'];

    public function contrato()
    {
    	return $this->belongsTo('Vanguard\Contrato');
    }
    
}
