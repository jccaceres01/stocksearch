<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarritoRequest extends FormRequest
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
      'articulo_id' => 'required|max:20',
      'bodega_id' => 'required|max:4',
      'localizacion' => 'required|max:8',
      'original' => 'required',
      'cantidad' => 'required'
    ];
  }

  /**
   * Custom Messages
   */
  public function messages() {
    return [
      'articulo_id.required' => 'Debe de incluir el articulo',
      'articulo_id.max' =>
        'El valor maximo para la clave del articulo es: 20 caracteres',
      'bodega_id.required' => 'El identificador de bodega es requerido',
      'bodega_id.max' =>
        'El valor maximo para el identificador de bodega es de 4 caracteres',
      'localizacion.required' => 'La localización es requerida',
      'localizacion.max' =>
        'El valor maximo de caracteres para la localizacion es: 8',
      'original.required' =>
        'Se debe indicar si el repuesto es Original o Aftermarket',
      'cantidad.required' => 'La cantidad es requerida'
    ];
  }
}
