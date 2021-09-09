<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutputsRequests extends FormRequest
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
            'referencia' => 'required',
            'paquete_inventario' => 'required'
        ];
    }

    /**
     * Custom messages
     */
    public function messages() {
        return [
            'referencia.required' => 'Es necesario especificar el número interno',
            'paquete_inventario.required' => 'Es necesario indicar el paquete de inventario'
        ];
    }
}
