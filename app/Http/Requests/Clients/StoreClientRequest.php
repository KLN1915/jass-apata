<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //History titular
            'namesLastnames' => 'required|string|max:100|unique:history_titulars,names_lastnames',
            'dni' => 'nullable|numeric|digits:8|unique:history_titulars,dni',

            //Client
            'phoneNumber' => 'nullable|numeric|digits:9|unique:clients,phone_number',
            'datebirth' => 'nullable|date|after:31-12-1899',
            'grade' => 'nullable|in:SIN NIVEL,PRE-ESCOLAR,PRIMARIA,SECUNDARIA,SUPERIOR',

            //Occupation
            'occupation' => 'nullable|string|max:25',

            //Directions
            // Solo se validan si se envía algún dato, de lo contrario se ignoran
            'directions'      => 'array',
            // 'directions.*'    => 'required|string|max:100|unique:directions,name',
            'directions.*'    => 'required|string|max:100',
            'zone_id'        => 'array',
            'zone_id.*'      => 'required|exists:zones,id', 
            'cant_beneficiaries'   => 'array',
            'cant_beneficiaries.*' => 'required|numeric|min:1|max:255',
            'permanence'   => 'array',
            'permanence.*' => 'required|numeric|min:1|max:255',            
            'material'   => 'array',
            'material.*' => 'required|in:RUSTICO,NOBLE,MIXTO',
            'drains'   => 'array',
            'drains.*' => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            //History titular
            'namesLastnames'            => 'nombres y apellidos',
            // 'dni'                    => 'dni',
            
            //Client
            'phoneNumber'               => 'celular',
            'datebirth'                 => 'fecha de nacimiento',
            'grade'                     => 'instrucción',

            //Occupation
            'occupationName'            => 'ocupación',

            //Directions
            'directions.*'              => 'dirección',
            'zone_id.*'                 => 'barrio',
            'cant_beneficiaries.*'      => '# habitantes',
            'permanence.*'              => 'permanencia',
            'material.*'                => 'material',
            'drains.*'                  => 'sumideros',
        ];
    }

    /**
     * Custom validation messages.
     */
    // public function messages(): array
    // {
    //     // return [
    //     //     //History titular
    //     //     'namesLastnames.required' => 'El campo nombres y apellidos es obligatorio.',

    //     //     //Client
    //     //     'phoneNumber.numeric' => 'El campo celular debe ser numérico',
    //     //     'phoneNumber.digits' => 'El campo celular debe tener 9 dígitos.',
    //     //     'phoneNumber.unique' => 'Este celular ya existe',
    //     //     'datebirth.after' => 'El campo fecha de nacimiento debe ser una fecha posterior a 31-12-1899.'
    //     // ];

    //     return [
    //     '*.required' => 'Este campo es obligatorio.',
    //     '*.string' => 'Este campo debe ser un texto.',
    //     '*.numeric' => 'Debe ser un valor numérico.',
    //     '*.date' => 'Debe ser una fecha válida.',
    //     '*.digits' => 'Debe tener :digits dígitos.',
    //     '*.max' => 'No debe exceder :max caracteres.',
    //     '*.in' => 'Debe seleccionar una opción válida.',
    //     '*.unique' => 'Este valor ya existe.',
    //     '*.exists' => 'La opción seleccionada no es válida.',
    //     '*.boolean' => 'Debe ser verdadero o falso.',
    //     '*.after' => 'Debe ser una fecha posterior a :date.',
    //     '*.min' => 'Debe ser mínimo :min.',
    // ];
    // }
}
