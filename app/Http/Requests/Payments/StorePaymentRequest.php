<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class StorePaymentRequest extends FormRequest
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
            // 'contract_id' => 'required|integer|exists:contracts,id',
            // 'debtIds' => 'sometimes|array',
            // 'debtIds.*' => 'exists:debts,id',
            // 'additionalDebt' => 'sometimes|array',
            // 'additionalDebt.*' => 'sometimes|gt:0|decimal:0,2',
            // 'additionalService' => 'array',
            // 'additionalService.*' => 'nullable|gt:0|decimal:0,2'
            'contract_id' => 'required|integer|exists:contracts,id',
            'debtIds' => 'sometimes|array',
            'debtIds.*' => 'exists:debts,id',
            
            'additionalDebt' => 'sometimes|array',
            'additionalDebt.*' => [
                'sometimes',
                'nullable',
                'numeric',
                'gt:0',
                'decimal:0,2',
                function ($attribute, $value, $fail) {
                    // 1. Extraer el ID de la llave del array (additionalDebt.12 -> 12)
                    $id = last(explode('.', $attribute));

                    // 2. Buscar la deuda adicional en la BD
                    $debt = DB::table('additional_debts')->find($id);

                    if (!$debt) {
                        return $fail("La deuda con ID {$id} no existe.");
                    }

                    // 3. Calcular el saldo restante
                    $maxAllowed = $debt->original_amount - $debt->amount_payed;

                    // 4. Validar que el valor ingresado no exceda el saldo
                    if ($value > $maxAllowed) {
                        $fail("El monto pagado excede al monto endeudado (S/.$maxAllowed)");
                    }
                },
            ],

            'additionalService' => 'array',
            'additionalService.*' => 'nullable|gt:0|decimal:0,2'
        ];
    }

    public function attributes(): array
    {
        return [
            'contract_id' => 'contrato',
            'additionalService.*' => 'servicio adicional',
        ];
    }

    /**
     * Lógica extra para validar que no todo esté vacío
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $hasDebtIds = !empty($this->input('debtIds'));
            
            // Filtramos los arrays adicionales para ver si tienen algún valor numérico real
            $hasAdditionalDebt = collect($this->input('additionalDebt'))->filter()->isNotEmpty();
            $hasAdditionalService = collect($this->input('additionalService'))->filter()->isNotEmpty();

            if (!$hasDebtIds && !$hasAdditionalDebt && !$hasAdditionalService) {
                $validator->errors()->add(
                    'main_validation', 
                    'Se debe pagar al menos una deuda, una deuda adicional o un servicio.'
                );
            }
        });
    }
}
