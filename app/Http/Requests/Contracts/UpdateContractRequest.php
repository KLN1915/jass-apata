<?php

namespace App\Http\Requests\Contracts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContractRequest extends FormRequest
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
            //datos obligatorios
            // 'associated_id' => 'required|integer|exists:clients,id',
            'direction_id' => 'required|integer|exists:directions,id',
            'service_id' => 'required|integer|exists:services,id',
            'contractType' => 'required|string|in:existing,new',
            //datos condicionales
            'start_date' => 'required_if:contractType,existing|nullable|date',
            'debt_since' => 'sometimes|digits:4|integer|min:2000|max:' . now()->year,
            'installation_cost' => 'required_if:contractType,existing|gt:0|decimal:0,2',
            'amount_payed' => 'required_if:contractType,existing|gt:0|decimal:0,2|lte:installation_cost',
            'new_installation_cost' => 'required_if:contractType,new|nullable|gt:0|decimal:0,2'
        ];
    }

    public function attributes(): array
    {
        return [
            //datos obligatorios
            'associated_id' => 'asociado',
            'direction_id' => 'dirección',
            'service_id' => 'servicio',
            //datos condicionales
            'start_date' => 'fecha de inicio',
            'debt_since' => 'inicio de deudas',
            'installation_cost' => 'costo de instalación',
            'amount_payed' => 'monto pagado',
            'new_installation_cost' => 'costo de instalación'
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'start_date.required_if' => 'El campo fecha de inicio es obligatorio.',
            'new_installation_cost.required_if' => 'El campo costo de instalación es obligatorio.',
        ];
    }
}
