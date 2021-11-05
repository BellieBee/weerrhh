<?php

namespace Vanguard\Http\Requests;

use Vanguard\Http\Requests\Request;

class FeriadoRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dia' => 'required',
            'month_id' => 'required',
            'descripcion_feriado' => 'required',
            'fecha' => 'required'
        ];
    }
}
