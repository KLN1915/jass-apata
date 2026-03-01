<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
            'namesLastnames' => [
                'sometimes',
                'required',
                'max:100',
                // Rule::unique('history_titulars', 'names_lastnames')->ignore($this->route('client'))
                Rule::unique('history_titulars', 'names_lastnames')
                    // ->ignore($this->route('client'), 'client_id')
            ],
            'dni' => [
                'nullable',
                'digits:8',
                // Rule::unique('history_titulars', 'dni')->ignore($this->route('client'))
                Rule::unique('history_titulars', 'dni')
                    // ->ignore($this->route('client'), 'client_id')
            ],

            //Client
            'phoneNumber' => [
                'nullable',
                'numeric',
                'digits:9',
                Rule::unique('clients', 'phone_number')->ignore($this->route('client')),
            ],            
            'datebirth' => 'nullable|date|after:31-12-1899',
            'grade' => 'nullable|in:SIN NIVEL,PRE-ESCOLAR,PRIMARIA,SECUNDARIA,SUPERIOR',
        
            //Occupation
            'occupation' => 'nullable|string|max:25',

            //Directions
            'direction_ids' => 'array',
            'direction_ids.*' => 'nullable|',
            'directions'      => 'array',
            'directions.*'    => [
                'required',
                'string',
                'max:100',
                // Rule::unique('directions','name')->ignore($this->route('client')),
                Rule::unique('directions', 'name')
                    ->ignore($this->route('client'), 'client_id'), // ignora el mismo client_id
            ], 
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
            'namesLastnames' => 'nombres y apellidos',

            //Client
            'phoneNumber' => 'celular',
            'datebirth' => 'fecha de nacimiento',
            'grade' => 'instrucción',

            //Occupation
            'occupationName' => 'ocupación',

            //Directions
            'directions.*'              => 'dirección',
            'zone_id.*'                 => 'barrio',
            'cant_beneficiaries.*'      => '# habitantes',
            'permanence.*'              => 'permanencia',
            'material.*'                => 'material',
            'drains.*'                  => 'sumideros',
        ];
    }
}
