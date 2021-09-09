<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutputsLinesRequest extends FormRequest
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
            'paquete_inventario' => 'required',
            'documento_inv' => 'required',
            'articulo' => 'required',
            'bodega' => 'required',
            'localizacion' => 'required',
            'cantidad' => 'required|digits_between:1,999999',
            'centro_costo' => 'required',
            'softland_user' => 'required'
        ];
    }

    /**
     * Set messages for each error
     */
    public function messages() {
        return [
            'paquete_inventario.required' => 'Es necesario indicar el paquete 
                de inventario', 
            'documento_inv.required' => 'Es necesario indicar documento', 
            'articulo.required' => 'Es necesario indicar el repuesto', 
            'bodega.required' => 'Es necesario indicar la bodega', 
            'localizacion.required' => 'Es necesario indicar la localización', 
            'cantidad.required' => 'Es necesario indicar una cantidad',
            'cantidad.digits_between' => 'La cantidad debe ser mayor 
                o igual a 1 y menor o igual a  999, 999',
            'centro_costo.required' => 'Es necesario indicar el centro de 
                costo', 
            'softland_user.required' => 'Es necesario indicar el usuario 
                de softland',
        ];
    }
}
