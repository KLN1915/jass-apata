<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreServiceRequest extends FormRequest
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
            //Service
            'name' => 'required|unique:services,name',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'chargePeriod' => 'required|in:MENSUAL,ANUAL',
            'lateFee' => 'boolean',

            //Latefee
            // Si lateFee=1 y chargePeriod=12 => solo period_day es requerido
            'period_day' => [
                Rule::requiredIf(fn () => $this->lateFee == 1 && $this->chargePeriod == 'ANUAL'),
                'numeric',
            ],
            // Si lateFee=1 y chargePeriod != 12 => ambos requeridos
            'period_month' => [
                Rule::requiredIf(fn () => $this->lateFee == 1 && $this->chargePeriod != 'ANUAL'),
                'numeric',
            ],
            'latefee_amount' => 'required_if:lateFee,1,numeric|regex:/^\d+(\.\d{1,2})?$/',
        ];
    }

    public function attributes(): array
    {
        return [
            //service
            'name' => 'nombre',
            'price' => 'precio',
            'chargePeriod' => 'periodo',
            //latefee
            'latefee_amount' => 'multa',
            'period_day' => 'día',
            'period_month' => 'mes',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'period_month.numeric' => 'El campo mes es obligatorio',
            'latefee_amount.required_if' => 'El campo multa es obligatorio.',
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->lateFee == 1) {
                $month = (int) $this->input('period_month');
                $day   = (int) $this->input('period_day');

                if ($month && $day) {
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, now()->year);
                    if ($day > $daysInMonth) {
                        $validator->errors()->add('period_day', "El día {$day} no es válido para el mes seleccionado.");
                    }
                }
            }
        });
    }
}